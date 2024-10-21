<?php

namespace App\Traits\PaymentGateway;

use Exception;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use App\Models\TemporaryData;
use Illuminate\Support\Carbon;
use App\Models\UserNotification;
use App\Http\Helpers\Api\Helpers;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\BasicSettings;
use App\Constants\NotificationConst;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Constants\PaymentGatewayConst;
use App\Notifications\User\TopUp\PaymentMail;
use App\Notifications\User\AddMoney\ApprovedMail;

trait UddoktaPay
{
    public function uddoktaPayInit($output = null)
    {
        if (!$output) $output = $this->output;
        $credentials = $this->getUddoktaPayCredetials($output);

        $user = Auth::user();
        $identifier = generate_unique_string("transactions", "trx_id", 16);

        $this->uddoktaPayJunkInsert($identifier);

        $requestData = [
            'full_name'    => $user->fullname,
            'email'        => $user->email,
            'amount'       => $output['amount']->total_amount ? number_format($output['amount']->total_amount, 2, '.', '') : 0,
            'metadata'     => [
                'order_id'   => $identifier,
                'metadata_2' => 'bar',
            ],
            'return_type'   => 'GET',
            'redirect_url' => route('user.add.money.uddokta.pay.success', $identifier),
            'cancel_url'   => route('user.add.money.uddokta.pay.cancel', $identifier),
        ];
        if ($credentials->mode == Str::lower(PaymentGatewayConst::ENV_SANDBOX)) {
            $link_url =  $credentials->sandbox_url;
        } else {
            $link_url =  $credentials->live_url;
        }
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $link_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($requestData),
            CURLOPT_HTTPHEADER => [
                "RT-UDDOKTAPAY-API-KEY: " . $credentials->client_secret,
                "accept: application/json",
                "content-type: application/json"
            ],
        ]);
        $response = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($response, true);

        if (isset($result['status']) && $result['status'] == true) {
            return redirect()->away($result['payment_url']);
        } else {
            return redirect()->route('index')->with(['error' => [$result['message']]]);
        }
    }

    public function getUddoktaPayCredetials($output)
    {
        $gateway = $output['gateway'] ?? null;
        if (!$gateway) throw new Exception("Payment gateway not available");
        $client_secret_sample = ['client_secret', 'client secret', 'secret', 'secret key', 'secret id'];
        $base_url_sample = ['base url', 'base-url', 'api-url', 'base_url', 'sandbox_url', 'production_url'];
        $live_url_sample = ['Live Url', 'live-url', 'live_url', 'production_url'];

        $base_url = '';
        $outer_break = false;
        foreach ($base_url_sample as $item) {
            if ($outer_break == true) {
                break;
            }
            $modify_item = $this->uddoktaPayPlainText($item);
            foreach ($gateway->credentials ?? [] as $gatewayInput) {
                $label = $gatewayInput->label ?? "";
                $label = $this->uddoktaPayPlainText($label);

                if ($label == $modify_item) {
                    $base_url = $gatewayInput->value ?? "";
                    $outer_break = true;
                    break;
                }
            }
        }


        $secret_id = '';
        $outer_break = false;
        foreach ($client_secret_sample as $item) {
            if ($outer_break == true) {
                break;
            }
            $modify_item = $this->uddoktaPayPlainText($item);
            foreach ($gateway->credentials ?? [] as $gatewayInput) {
                $label = $gatewayInput->label ?? "";
                $label = $this->uddoktaPayPlainText($label);

                if ($label == $modify_item) {
                    $secret_id = $gatewayInput->value ?? "";
                    $outer_break = true;
                    break;
                }
            }
        }

        $live_url = '';
        $outer_break = false;
        foreach ($live_url_sample as $item) {
            if ($outer_break == true) {
                break;
            }
            $modify_item = $this->sllPlainText($item);
            foreach ($gateway->credentials ?? [] as $gatewayInput) {
                $label = $gatewayInput->label ?? "";
                $label = $this->sllPlainText($label);

                if ($label == $modify_item) {
                    $live_url = $gatewayInput->value ?? "";
                    $outer_break = true;
                    break;
                }
            }
        }

        $mode = $gateway->env;

        $paypal_register_mode = [
            PaymentGatewayConst::ENV_SANDBOX => "sandbox",
            PaymentGatewayConst::ENV_PRODUCTION => "live",
        ];
        if (array_key_exists($mode, $paypal_register_mode)) {
            $mode = $paypal_register_mode[$mode];
        } else {
            $mode = "sandbox";
        }


        return (object) [
            'sandbox_url'     => $base_url,
            'live_url' => $live_url,
            'client_secret' => $secret_id,
            'mode'          => $mode,
        ];
    }

    public function uddoktaPayPlainText($string)
    {
        $string = Str::lower($string);
        return preg_replace("/[^A-Za-z0-9]/", "", $string);
    }

    public function uddoktaPayJunkInsert($identifier)
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
                'response'     => $identifier,
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
                'gateway'       => $output['gateway']->id,
                'currency'      => $output['currency']->id,
                'amount'        => json_decode(json_encode($output['amount']), true),
                'response'      => $identifier,
                'wallet_table'  => $wallet_table,
                'wallet_id'     => $wallet_id,
                'creator_table' => $creator_table,
                'creator_id'    => $creator_id,
                'type' => $output['type'],
                'creator_guard' => get_auth_guard(),
            ];
        }

        $billing_gateway_tamp_data = TemporaryData::create([
            'type'          => PaymentGatewayConst::UDDOKTAPAY,
            'identifier'    => $identifier,
            'data'          => $data,
        ]);
        if ("ORDER" == $output['type']) {
            $billing_tempData = $output['billingTempData']['identifier'];
            TemporaryData::where('identifier', $billing_tempData)->first()->delete();
        }

        return $billing_gateway_tamp_data;
    }

    public function uddoktapaySuccess($output = null)
    {
        if (!$output) $output = $this->output;
        $token = $this->output['tempData']['identifier'] ?? "";
        if (empty($token)) throw new Exception('Transaction faild. Record didn\'t saved properly. Please try again.');
        return $this->createTransactionUddoktaPay($output);
    }

    public function createTransactionUddoktaPay($output)
    {
        try {
            $basic_setting = BasicSettings::first();
            $user = auth()->user();
            $trx_id = 'AM' . getTrxNum();

            $inserted_id = $this->insertRecordUddoktaPay($output, $trx_id);
            $this->insertChargesUddoktaPay($output, $inserted_id);
            $this->insertDeviceUddoktaPay($output, $inserted_id);
            $this->removeTempDataUddoktaPay($output);

            $user = auth()->user();
            if ($this->requestIsApiUser()) {
                $api_user_login_guard = $this->output['api_login_guard'] ?? null;
                if ($api_user_login_guard != null) {
                    $user = auth()->guard($api_user_login_guard)->user();
                }
            }

            if ($basic_setting->email_notification == true) {

                if ($output['tempData']['data']->type == 'ADD-MONEY') {
                    $user->notify(new ApprovedMail($user, $output, $trx_id));
                } else {
                    $user->notify(new PaymentMail($user, $output, $trx_id));
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return true;
    }

    public function insertRecordUddoktaPay($output, $trx_id)
    {

        $trx_id =  $trx_id;
        DB::beginTransaction();
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

        try {
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
                //Addmoney
                $id = DB::table("transactions")->insertGetId([
                    'user_id'                       => auth()->user()->id,
                    'user_wallet_id'                => $output['wallet']->id,
                    'payment_gateway_currency_id'   => $output['currency']->id,
                    'type'                          => $output['type'],
                    'trx_id'                        => $trx_id,
                    'request_amount'                => $output['amount']->requested_amount,
                    'payable'                       => $output['amount']->total_amount,
                    'available_balance'             => $output['wallet']->balance + $output['amount']->requested_amount,
                    'remark'                        => ucwords(remove_speacial_char($output['type'], " ")) . " With " . $output['gateway']->name,
                    'details'                       => 'UddoktaPay Payment Successful',
                    'status'                        => 2,
                    'attribute'                      => PaymentGatewayConst::SEND,
                    'created_at'                    => now(),
                ]);
                $this->updateWalletBalanceUddoktaPay($output);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }

        return $id;
    }

    public function updateWalletBalanceUddoktaPay($output)
    {
        $update_amount = $output['wallet']->balance + $output['amount']->requested_amount;

        $output['wallet']->update([
            'balance'   => $update_amount,
        ]);
    }

    public function insertChargesUddoktaPay($output, $id)
    {
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

            if (Auth::guard(get_auth_guard())->check()) {
                $user = auth()->guard(get_auth_guard())->user();
            }
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

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function insertDeviceUddoktaPay($output, $id)
    {
        $client_ip = request()->ip() ?? false;
        $location = geoip()->getLocation($client_ip);
        $agent = new Agent();

        // $mac = exec('getmac');
        // $mac = explode(" ",$mac);
        // $mac = array_shift($mac);
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

    public function removeTempDataUddoktaPay($output)
    {
        TemporaryData::where("identifier", $output['tempData']['identifier'])->delete();
    }
    public function uddoktaPayInitApi($output = null)
    {

        if (!$output) $output = $this->output();
        $credentials = $this->getUddoktaPayCredetials($output);
        $user = auth()->guard(get_auth_guard())->user();

        $identifier = generate_unique_string('transactions', 'trx_id', 16);
        $this->uddoktaPayJunkInsert($identifier);

        $requestData = [
            'full_name'    => $user->fullname,
            'email'        => $user->email,
            'amount'       => $output['amount']->total_amount ? number_format($output['amount']->total_amount, 2, '.', '') : 0,
            'metadata'     => [
                'order_id'   => $identifier,
                'metadata_2' => 'bar',
            ],
            'return_type'   => 'GET',
            'redirect_url' => route('api.v1.api.uddokta.pay.success', $identifier . "?r-source=" . PaymentGatewayConst::APP),
            'cancel_url'   => route('api.v1.api.uddokta.pay.cancel', $identifier . "?r-source=" . PaymentGatewayConst::APP),
        ];
        if ($credentials->mode == Str::lower(PaymentGatewayConst::ENV_SANDBOX)) {
            $link_url =  $credentials->sandbox_url;
        } else {
            $link_url =  $credentials->live_url;
        }
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $link_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($requestData),
            CURLOPT_HTTPHEADER => [
                "RT-UDDOKTAPAY-API-KEY: " . $credentials->client_secret,
                "accept: application/json",
                "content-type: application/json"
            ],
        ]);
        $response = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($response, true);

        if (isset($result['status']) && $result['status'] == true) {
            $data['payment_url'] = $result['payment_url'];
            $data['trx'] = $identifier;
            return $data;
        } else {
            $error = ['error' => ["Something Is Wrong, Please Contact With Owner"]];
            return Helpers::error($error);
        }
    }
}
