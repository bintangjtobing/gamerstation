<?php

namespace App\Traits\PaymentGateway;

use Exception;
use Illuminate\Support\Str;
use App\Models\TemporaryData;
use Illuminate\Support\Carbon;
use App\Models\UserNotification;
use Illuminate\Support\Facades\DB;
use App\Constants\NotificationConst;
use Illuminate\Support\Facades\Auth;
use App\Constants\PaymentGatewayConst;
use App\Models\Admin\AdminNotification;
use App\Models\Admin\BasicSettings;
use App\Notifications\User\AddMoney\ApprovedMail;
use Illuminate\Support\Facades\Config;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Session;
use KingFlamez\Rave\Facades\Rave as Flutterwave;
use App\Events\User\NotificationEvent as UserNotificationEvent;
use App\Notifications\User\TopUp\PaymentMail;

trait FlutterwaveTrait
{
    public function flutterwaveInit($output = null)
    {
        if (!$output) $output = $this->output;

        $credentials = $this->getFlutterCredentials($output);

        $this->flutterwaveSetSecreteKey($credentials);

        //This generates a payment reference
        $reference = Flutterwave::generateReference();

        $amount = $output['amount']->total_amount ? number_format($output['amount']->total_amount, 2, '.', '') : 0;

        if (auth()->guard(get_auth_guard())->check()) {
            $user = auth()->guard(get_auth_guard())->user();
            $user_email = $user->email;
            $user_phone = $user->full_mobile ?? '';
            $user_name = $user->firstname . ' ' . $user->lastname ?? '';
        }
        if (Auth::guard(get_auth_guard())->check()) {
            $return_url = route('user.add.money.flutterwave.callback');
        } else {
            $return_url = route('top.up.flutterwave.callback');
        }


        // Enter the details of the payment
        $data = [
            'payment_options' => 'card,banktransfer',
            'amount'          => $amount,
            'email'           => $user_email,
            'tx_ref'          => $reference,
            'currency'        => $output['currency']['currency_code'] ?? "NGN",
            'redirect_url'    => $return_url,
            'customer'        => [
                'email'        => $user_email ?? 'demo name',
                "phone_number" => $user_phone ?? '01234567899',
                "name"         => $user_name ?? 'demo@gmail.com'
            ],
            "customizations" => [
                "title"       => $output['type'] == 'ADD-MONEY' ? __('Add Money') : __('TopUp'),
                "description" => dateFormat('d M Y', Carbon::now()),
            ]
        ];

        $payment = Flutterwave::initializePayment($data);
        if ($payment['status'] == "error") {
            throw new Exception($payment['message']);
        };


        $this->flutterWaveJunkInsert($data);

        if ($payment['status'] !== 'success') {
            return;
        }

        return redirect($payment['data']['link']);
    }


    public function flutterWaveJunkInsert($response)
    {
        $output = $this->output;

        $user = auth()->guard(get_auth_guard())->user();
        $creator_table = $creator_id = $wallet_table = $wallet_id = null;


        $creator_table = auth()->guard(get_auth_guard())->user()->getTable();
        $creator_id = auth()->guard(get_auth_guard())->user()->id;
        $wallet_table = $output['wallet']->getTable();
        $wallet_id = $output['wallet']->id;

        if ("ORDER" == $output['type']) {
            $data = [
                'gateway'      => $output['gateway']->id,
                'currency'     => $output['currency']->id,
                'amount'       => json_decode(json_encode($output['amount']), true),
                'response'     => $response,
                'wallet_table'  => $wallet_table,
                'wallet_id'     => $wallet_id,
                'creator_table' => $creator_table,
                'creator_id'    => $creator_id,
                'creator_guard' => get_auth_guard(),
                'type' => $output['type'],
                'billingTempData' => $output['billingTempData']['data'],
            ];
        } else {
            $data = [
                'gateway'      => $output['gateway']->id,
                'currency'     => $output['currency']->id,
                'amount'       => json_decode(json_encode($output['amount']), true),
                'response'     => $response,
                'wallet_table'  => $wallet_table,
                'wallet_id'     => $wallet_id,
                'creator_table' => $creator_table,
                'creator_id'    => $creator_id,
                'creator_guard' => get_auth_guard(),
                'type' => $output['type'],

            ];
        }

        Session::put('identifier', $response['tx_ref']);
        Session::put('output', $output);

        $billing_gateway_tamp_data = TemporaryData::create([
            'type'          => PaymentGatewayConst::FLUTTER_WAVE,
            'identifier'    => $response['tx_ref'],
            'data'          => $data,
        ]);
        if ("ORDER" == $output['type']) {
            $billing_tempData = $output['billingTempData']['identifier'];
            TemporaryData::where('identifier', $billing_tempData)->first()->delete();
        }
        return $billing_gateway_tamp_data;
    }

    // Get Flutter wave credentials
    public function getFlutterCredentials($output)
    {
        $gateway = $output['gateway'] ?? null;
        if (!$gateway) throw new Exception("Payment gateway not available");

        $public_key_sample = ['api key', 'api_key', 'client id', 'primary key', 'public key'];
        $secret_key_sample = ['client_secret', 'client secret', 'secret', 'secret key', 'secret id'];
        $encryption_key_sample = ['encryption_key', 'encryption secret', 'secret hash', 'encryption id'];

        $public_key = '';
        $outer_break = false;

        foreach ($public_key_sample as $item) {
            if ($outer_break == true) {
                break;
            }
            $modify_item = $this->flutterwavePlainText($item);
            foreach ($gateway->credentials ?? [] as $gatewayInput) {
                $label = $gatewayInput->label ?? "";
                $label = $this->flutterwavePlainText($label);
                if ($label == $modify_item) {
                    $public_key = $gatewayInput->value ?? "";
                    $outer_break = true;
                    break;
                }
            }
        }

        $secret_key = '';
        $outer_break = false;
        foreach ($secret_key_sample as $item) {
            if ($outer_break == true) {
                break;
            }
            $modify_item = $this->flutterwavePlainText($item);
            foreach ($gateway->credentials ?? [] as $gatewayInput) {
                $label = $gatewayInput->label ?? "";
                $label = $this->flutterwavePlainText($label);

                if ($label == $modify_item) {
                    $secret_key = $gatewayInput->value ?? "";
                    $outer_break = true;
                    break;
                }
            }
        }

        $encryption_key = '';
        $outer_break = false;
        foreach ($encryption_key_sample as $item) {
            if ($outer_break == true) {
                break;
            }
            $modify_item = $this->flutterwavePlainText($item);
            foreach ($gateway->credentials ?? [] as $gatewayInput) {
                $label = $gatewayInput->label ?? "";
                $label = $this->flutterwavePlainText($label);

                if ($label == $modify_item) {
                    $encryption_key = $gatewayInput->value ?? "";
                    $outer_break = true;
                    break;
                }
            }
        }

        return (object) [
            'public_key'     => $public_key,
            'secret_key'     => $secret_key,
            'encryption_key' => $encryption_key,
        ];
    }

    public function flutterwavePlainText($string)
    {
        $string = Str::lower($string);
        return preg_replace("/[^A-Za-z0-9]/", "", $string);
    }

    public function flutterwaveSetSecreteKey($credentials)
    {
        Config::set('flutterwave.secretKey', $credentials->secret_key);
        Config::set('flutterwave.publicKey', $credentials->public_key);
        Config::set('flutterwave.secretHash', $credentials->encryption_key);
    }

    public function flutterwaveSuccess($output = null)
    {
        if (!$output) $output = $this->output;
        $token = $this->output['tempData']['identifier'] ?? "";
        if (empty($token)) throw new Exception('Transaction failed. Record didn\'t saved properly. Please try again.');
        return $this->createTransactionFlutterwave($output);
    }

    public function createTransactionFlutterwave($output)
    {
        $basic_setting = BasicSettings::first();
        $user = auth()->user();
        $trx_id = 'AM' . getTrxNum();
        $inserted_id = $this->insertRecordFlutterwave($output, $trx_id);
        $this->insertChargesFlutterwace($output, $inserted_id);
        $this->insertDeviceFlutterWave($output, $inserted_id);
        $this->removeTempDataFlutterWave($output);

        if ($this->requestIsApiUser()) {
            // logout user
            $api_user_login_guard = $this->output['api_login_guard'] ?? null;
            if ($api_user_login_guard != null) {
                auth()->guard($api_user_login_guard)->logout();
            }
        }

        if ($basic_setting->email_notification == true) {

            if ($output['tempData']['data']->type == 'ADD-MONEY') {
                $user->notify(new ApprovedMail($user, $output, $trx_id));
            } else {
                $user->notify(new PaymentMail($user, $output, $trx_id));
            }
        }
    }

    public function updateWalletBalanceFlutterWave($output)
    {
        $update_amount = $output['wallet']->balance + $output['amount']->requested_amount;

        $output['wallet']->update([
            'balance'   => $update_amount,
        ]);
    }

    public function insertRecordFlutterwave($output, $trx_id)
    {
        $token = $this->output['tempData']['identifier'] ?? "";

        DB::beginTransaction();
        try {
            if (Auth::guard(get_auth_guard())->check()) {
                $user_id = auth()->guard(get_auth_guard())->user()->id;
                $user_wallet_id = $output['wallet']->id;
                $available_balance = $output['wallet']->balance + $output['amount']->requested_amount;
                $payment_gateway_currency_id = $output['currency']->id;
            } else {
                $user_id = null;
                $user_wallet_id = null;
                $available_balance = 0;
                $payment_gateway_currency_id = null;
            }

            if ("ORDER" == $output['tempData']['data']->type) {
                //Topup
                $id = DB::table("transactions")->insertGetId([
                    'user_id'                       => $user_id,
                    'user_wallet_id'                => $user_wallet_id,
                    'payment_gateway_currency_id'   => $payment_gateway_currency_id,
                    'type'                          => "TOP-UP",
                    'trx_id'                        => $trx_id,
                    'request_amount'                => $output['amount']->requested_amount,
                    'payable'                       => $output['amount']->total_amount,
                    'available_balance'             => $available_balance,
                    'remark'                        => ucwords(remove_speacial_char($output['type'], " ")) . " With " . $output['gateway']->name,
                    'details'                       => json_encode($output['tempData']['data']->billingTempData),
                    'status'                        => 2,
                    'created_at'                    => now(),
                ]);
            } else {
                // Add money
                $id = DB::table("transactions")->insertGetId([
                    'user_id'                       => $user_id,
                    'user_wallet_id'                => $output['wallet']->id,
                    'payment_gateway_currency_id'   => $output['currency']->id,
                    'type'                          => $output['type'],
                    'trx_id'                        => $trx_id,
                    'request_amount'                => $output['amount']->requested_amount,
                    'payable'                       => $output['amount']->total_amount,
                    'available_balance'             => $output['wallet']->balance + $output['amount']->requested_amount,
                    'remark'                        => ucwords(remove_speacial_char($output['type'], " ")) . " With " . $output['gateway']->name,
                    'details'                       => 'Flutter Wave Payment Successfull',
                    'status'                        => 2,
                    'created_at'                    => now(),
                ]);
                $this->updateWalletBalanceFlutterWave($output);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
        return $id;
    }

    public function insertChargesFlutterwace($output, $id)
    {
        if (Auth::guard(get_auth_guard())->check()) {
            $user = auth()->guard(get_auth_guard())->user();
        }
        DB::beginTransaction();
        try {
            DB::table('transaction_charges')->insert([
                'transaction_id'    => $id,
                'percent_charge'    => $output['amount']->percent_charge,
                'fixed_charge'      => $output['amount']->fixed_charge,
                'total_charge'      => $output['amount']->total_charge,
                'created_at'        => now(),
            ]);
            DB::commit();

            if ($output['tempData']['data']->type == 'ADD-MONEY') {
                //notification
                $notification_content = [
                    'title'         => "Add Money",
                    'message'       => "Your Wallet (" . $output['wallet']->currency->code . ") balance  has been added " . $output['amount']->requested_amount . ' ' . $output['wallet']->currency->code,
                    'time'          => Carbon::now()->diffForHumans(),
                    'image'         => get_image($user->image, 'user-profile'),
                ];
            } else {
                //notification
                $notification_content = [
                    'title'         => "TopUp",
                    'message'       => "TopUp " . $output['amount']->requested_amount . " " . get_default_currency_code() . ' has been successfully',
                    'time'          => Carbon::now()->diffForHumans(),
                    'image'         => get_image($user->image, 'user-profile'),
                ];
            }


            UserNotification::create([
                'type'      => NotificationConst::BALANCE_ADDED,
                'user_id'  =>  auth()->user()->id,
                'message'   => $notification_content,
            ]);

            //Push Notifications
            event(new UserNotificationEvent($notification_content, $user));
            send_push_notification(["user-" . $user->id], [
                'title'     => $notification_content['title'],
                'body'      => $notification_content['message'],
                'icon'      => $notification_content['image'],
            ]);

            if ($output['tempData']['data']->type == 'ADD-MONEY') {
                //admin notification
                $notification_content['title'] = 'Add Money ' . $output['amount']->requested_amount . ' ' . $output['amount']->default_currency . ' By ' . $output['currency']->name . ' (' . $user->username . ')';
                AdminNotification::create([
                    'type'      => NotificationConst::BALANCE_ADDED,
                    'admin_id'  => 1,
                    'message'   => $notification_content,
                ]);
            }


            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function insertDeviceFlutterWave($output, $id)
    {
        $client_ip = request()->ip() ?? false;
        $location = geoip()->getLocation($client_ip);
        $agent = new Agent();

        $mac = "";

        DB::beginTransaction();
        try {
            DB::table("transaction_devices")->insert([
                'transaction_id' => $id,
                'ip'            => $client_ip,
                'mac'           => $mac,
                'city'          => $location['city'] ?? "",
                'country'       => $location['country'] ?? "",
                'longitude'     => $location['lon'] ?? "",
                'latitude'      => $location['lat'] ?? "",
                'timezone'      => $location['timezone'] ?? "",
                'browser'       => $agent->browser() ?? "",
                'os'            => $agent->platform() ?? "",
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function removeTempDataFlutterWave($output)
    {
        TemporaryData::where("identifier", $output['tempData']['identifier'])->delete();
    }


    // ********* For API **********
    public function flutterwaveInitApi($output = null)
    {
        if (!$output) $output = $this->output;
        $credentials = $this->getFlutterCredentials($output);
        $this->flutterwaveSetSecreteKey($credentials);

        //This generates a payment reference
        $reference = Flutterwave::generateReference();

        $amount = $output['amount']->total_amount ? number_format($output['amount']->total_amount, 2, '.', '') : 0;

        if (auth()->guard(get_auth_guard())->check()) {
            $user = auth()->guard(get_auth_guard())->user();
            $user_email = $user->email;
            $user_phone = $user->full_mobile ?? '';
            $user_name = $user->firstname . ' ' . $user->lastname ?? '';
        }

        $return_url = route('api.v1.api.flutterwave.callback', "r-source=" . PaymentGatewayConst::APP);

        // Enter the details of the payment
        $data = [
            'payment_options' => 'card,banktransfer',
            'amount'          => $amount,
            'email'           => $user_email,
            'tx_ref'          => $reference,
            'currency'        => $output['currency']['currency_code'] ?? "NGN",
            'redirect_url'    => $return_url,
            'customer'        => [
                'email'        => $user_email,
                "phone_number" => $user_phone,
                "name"         => $user_name
            ],
            "customizations" => [
                "title"       => $output['type'] == 'ADD-MONEY' ? __('Add Money') : __('Payment'),
                "description" => dateFormat('d M Y', Carbon::now()),
            ]
        ];

        $payment = Flutterwave::initializePayment($data);
        $data['link'] = $payment['data']['link'];
        $data['trx'] = $data['tx_ref'];

        $this->flutterWaveJunkInsert($data);

        if ($payment['status'] !== 'success') {
            // notify something went wrong
            return;
        }
        return $data;
    }
}
