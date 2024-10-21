<?php

namespace App\Traits\PaymentGateway;

use Exception;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use App\Models\TemporaryData;
use Illuminate\Support\Carbon;
use App\Models\UserNotification;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\BasicSettings;
use App\Constants\NotificationConst;
use Illuminate\Support\Facades\Auth;
use App\Constants\PaymentGatewayConst;
use App\Notifications\User\TopUp\PaymentMail;
use App\Notifications\User\AddMoney\ApprovedMail;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

trait Paypal
{
    public function paypalInit($output = null)
    {
        if (!$output) $output = $this->output;
        $credentials = $this->getPaypalCredetials($output);
        $config = $this->paypalConfig($credentials, $output['amount']);
        $paypalProvider = new PayPalClient;
        $paypalProvider->setApiCredentials($config);
        $paypalProvider->getAccessToken();

        if (!Auth::guard(get_auth_guard())->check()) {

            $response = $paypalProvider->createOrder([
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => route('top.up.payment.success', PaymentGatewayConst::PAYPAL),
                    "cancel_url" => route('top.up.payment.cancel', PaymentGatewayConst::PAYPAL),
                ],
                "purchase_units" => [
                    0 => [
                        "amount" => [
                            "currency_code" => $output['amount']->sender_cur_code ?? '',
                            "value" => $output['amount']->total_amount ? number_format($output['amount']->total_amount, 2, '.', '') : 0,
                        ]
                    ]
                ]
            ]);
        } else {
            $response = $paypalProvider->createOrder([
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => route('user.add.money.payment.success', PaymentGatewayConst::PAYPAL),
                    "cancel_url" => route('user.add.money.payment.cancel', PaymentGatewayConst::PAYPAL),
                ],
                "purchase_units" => [
                    0 => [
                        "amount" => [
                            "currency_code" => $output['amount']->sender_cur_code ?? '',
                            "value" => $output['amount']->total_amount ? number_format($output['amount']->total_amount, 2, '.', '') : 0,
                        ]
                    ]
                ]
            ]);
        }



        if (isset($response['id']) && $response['id'] != "" && isset($response['status']) && $response['status'] == "CREATED" && isset($response['links']) && is_array($response['links'])) {
            foreach ($response['links'] as $item) {
                if ($item['rel'] == "approve") {
                    $this->paypalJunkInsert($response);
                    return redirect()->away($item['href']);
                    break;
                }
            }
        }

        if (isset($response['error']) && is_array($response['error'])) {
            throw new Exception($response['error']['message']);
        }

        throw new Exception("Something went worng! Please try again.");
    }

    public function getPaypalCredetials($output)
    {
        $gateway = $output['gateway'] ?? null;
        if (!$gateway) throw new Exception("Payment gateway not available");
        $client_id_sample = ['api key', 'api_key', 'client id', 'primary key'];
        $client_secret_sample = ['client_secret', 'client secret', 'secret', 'secret key', 'secret id'];

        $client_id = '';
        $outer_break = false;
        foreach ($client_id_sample as $item) {
            if ($outer_break == true) {
                break;
            }
            $modify_item = $this->paypalPlainText($item);
            foreach ($gateway->credentials ?? [] as $gatewayInput) {
                $label = $gatewayInput->label ?? "";
                $label = $this->paypalPlainText($label);

                if ($label == $modify_item) {
                    $client_id = $gatewayInput->value ?? "";
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
            $modify_item = $this->paypalPlainText($item);
            foreach ($gateway->credentials ?? [] as $gatewayInput) {
                $label = $gatewayInput->label ?? "";
                $label = $this->paypalPlainText($label);

                if ($label == $modify_item) {
                    $secret_id = $gatewayInput->value ?? "";
                    $outer_break = true;
                    break;
                }
            }
        }

        return (object) [
            'client_id'     => $client_id,
            'client_secret' => $secret_id,
            'mode'          => "sandbox",
        ];
    }

    public function paypalPlainText($string)
    {
        $string = Str::lower($string);
        return preg_replace("/[^A-Za-z0-9]/", "", $string);
    }


    public static function paypalConfig($credentials, $amount_info)
    {
        $config = [
            'mode'    => $credentials->mode ?? 'sandbox',
            'sandbox' => [
                'client_id'         => $credentials->client_id ?? "",
                'client_secret'     => $credentials->client_secret ?? "",
                'app_id'            => "APP-80W284485P519543T",
            ],
            'live' => [
                'client_id'         => $credentials->client_id ?? "",
                'client_secret'     => $credentials->client_secret ?? "",
                'app_id'            => "",
            ],
            'payment_action' => 'Sale', // Can only be 'Sale', 'Authorization' or 'Order'
            'currency'       => $amount_info->sender_cur_code ?? "",
            'notify_url'     => "", // Change this accordingly for your application.
            'locale'         => 'en_US', // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
            'validate_ssl'   => true, // Validate SSL when creating api client.
        ];
        return $config;
    }

    public function paypalJunkInsert($response)
    {

        $output = $this->output;


        if ("ORDER" == $output['type']) {

            $data = [
                'gateway'   => $output['gateway']->id,
                'currency'  => $output['currency']->id,
                'amount'    => json_decode(json_encode($output['amount']), true),
                'response'  => $response,
                'wallet_table'  => $output['wallet']->getTable(),
                'wallet_id'     => $output['wallet']->id,
                'creator_table' => auth()->guard(get_auth_guard())->user()->getTable(),
                'creator_id'    => auth()->guard(get_auth_guard())->user()->id,
                'creator_guard' => get_auth_guard(),
                'type' => $output['type'],
                'billingTempData' => $output['billingTempData']['data'],
            ];
        } else {
            $data = [
                'gateway'   => $output['gateway']->id,
                'currency'  => $output['currency']->id,
                'amount'    => json_decode(json_encode($output['amount']), true),
                'response'  => $response,
                'wallet_table'  => $output['wallet']->getTable(),
                'wallet_id'     => $output['wallet']->id,
                'creator_table' => auth()->guard(get_auth_guard())->user()->getTable(),
                'creator_id'    => auth()->guard(get_auth_guard())->user()->id,
                'creator_guard' => get_auth_guard(),
                'type' => $output['type'],
            ];
        }


        $billing_gateway_tamp_data = TemporaryData::create([
            'type'          => PaymentGatewayConst::PAYPAL,
            'identifier'    => $response['id'],
            'data'          => $data,
        ]);
        if ("ORDER" == $output['type']) {
            $billing_tempData = $output['billingTempData']['identifier'];
            TemporaryData::where('identifier', $billing_tempData)->first()->delete();
        }
        return $billing_gateway_tamp_data;
    }

    public function paypalSuccess($output = null)
    {
        if (!$output) $output = $this->output;
        $token = $this->output['tempData']['identifier'] ?? "";

        $credentials = $this->getPaypalCredetials($output);
        $config = $this->paypalConfig($credentials, $output['amount']);
        $paypalProvider = new PayPalClient;
        $paypalProvider->setApiCredentials($config);
        $paypalProvider->getAccessToken();
        $response = $paypalProvider->capturePaymentOrder($token);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            return $this->paypalPaymentCaptured($response, $output);
        } else {
            throw new Exception('Transaction faild. Payment captured faild.');
        }

        if (empty($token)) throw new Exception('Transaction faild. Record didn\'t saved properly. Please try again.');
    }

    public function paypalPaymentCaptured($response, $output)
    {
        // payment successfully captured record saved to database
        $output['capture'] = $response;
        try {
            $this->createTransaction($output);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return true;
    }

    public function createTransaction($output)
    {
        $basic_setting = BasicSettings::first();
        $user = auth()->user();
        $trx_id = 'AM' . getTrxNum();
        $inserted_id = $this->insertRecord($output);
        $this->insertCharges($output, $inserted_id);
        $this->insertDevice($output, $inserted_id);
        $this->removeTempData($output);

        if ($basic_setting->email_notification == true) {

            if ($output['tempData']['data']->type == 'ADD-MONEY') {
                $user->notify(new ApprovedMail($user, $output, $trx_id));
            } else {
                $user->notify(new PaymentMail($user, $output, $trx_id));
            }
        }
    }

    public function insertRecord($output)
    {
        $trx_id = generate_unique_string("transactions", "trx_id", 16);
        $token = $this->output['tempData']['identifier'] ?? "";

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
        DB::beginTransaction();
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
                    'details'                       => json_encode($output['capture']),
                    'status'                        => 2,
                    'created_at'                    => now(),
                ]);
                $this->updateWalletBalance($output);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
            throw new Exception($e->getMessage());
        }
        return $id;
    }

    public function updateWalletBalance($output)
    {

        $update_amount = $output['wallet']->balance + $output['amount']->requested_amount;
        $output['wallet']->update([
            'balance'   => $update_amount,
        ]);
    }

    public function insertCharges($output, $id)
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
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function insertDevice($output, $id)
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

    public function removeTempData($output)
    {
        $token = $output['capture']['id'];
        TemporaryData::where("identifier", $token)->delete();
    }

    public function paypalInitApi($output = null)
    {
        if (!$output) $output = $this->output;
        $credentials = $this->getPaypalCredetials($output);

        $config = $this->paypalConfig($credentials, $output['amount']);
        $paypalProvider = new PayPalClient;
        $paypalProvider->setApiCredentials($config);
        $paypalProvider->getAccessToken();

        $response = $paypalProvider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('api.v1.api.payment.success', PaymentGatewayConst::PAYPAL . "?r-source=" . PaymentGatewayConst::APP),
                "cancel_url" => route('api.v1.api.payment.cancel', PaymentGatewayConst::PAYPAL . "?r-source=" . PaymentGatewayConst::APP),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => $output['amount']->sender_cur_code ?? '',
                        "value" => $output['amount']->total_amount ? number_format($output['amount']->total_amount, 2, '.', '') : 0,
                    ]
                ]
            ]
        ]);

        if (isset($response['id']) && $response['id'] != "" && isset($response['status']) && $response['status'] == "CREATED" && isset($response['links']) && is_array($response['links'])) {
            foreach ($response['links'] as $item) {
                if ($item['rel'] == "approve") {
                    $this->paypalJunkInsert($response);
                    return $response;
                    break;
                }
            }
        }

        if (isset($response['error']) && is_array($response['error'])) {
            throw new Exception($response['error']['message']);
        }

        throw new Exception("Something went worng! Please try again.");
    }
}
