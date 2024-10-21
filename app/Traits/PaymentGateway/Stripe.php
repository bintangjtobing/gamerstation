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
use App\Constants\PaymentGatewayConst;
use App\Models\Admin\AdminNotification;
use App\Notifications\User\TopUp\PaymentMail;
use App\Providers\Admin\BasicSettingsProvider;
use App\Notifications\User\AddMoney\ApprovedMail;
use App\Events\User\NotificationEvent as UserNotificationEvent;


trait Stripe
{
    public function stripeInit($output = null)
    {
        $basic_settings = BasicSettingsProvider::get();
        if (!$output) $output = $this->output;
        $credentials = $this->getStripeCredentials($output);

        $reference = generateTransactionReference();
        $amount = $output['amount']->total_amount ? number_format($output['amount']->total_amount, 2, '.', '') : 0;
        $currency = $output['currency']['currency_code'] ?? "USD";

        if (auth()->guard(get_auth_guard())->check()) {
            $user = auth()->guard(get_auth_guard())->user();
            $user_email = $user->email;
            $user_phone = $user->full_mobile ?? '';
            $user_name = $user->firstname . ' ' . $user->lastname ?? '';
        }
        if (!Auth::guard(get_auth_guard())->check()) {
            $return_url = route('top.up.stripe.payment.success', $reference);
        } else {
            $return_url = route('user.add.money.stripe.payment.success', $reference);
        }

        // Enter the details of the payment
        $data = [
            'payment_options' => 'card',
            'amount'          => $amount,
            'email'           => $user_email,
            'tx_ref'          => $reference,
            'currency'        =>  $currency,
            'redirect_url'    => $return_url,
            'customer'        => [
                'email'        => $user_email,
                "phone_number" => $user_phone,
                "name"         => $user_name
            ],
            "customizations" => [
                "title"       => $output['type'] == 'ADD-MONEY' ? __('Add Money') : __('TopUp'),
                "description" => dateFormat('d M Y', Carbon::now()),
            ]
        ];

        //start stripe pay link
        $stripe = new \Stripe\StripeClient($credentials->secret_key);

        //create product for Product Id
        try {
            $product_id = $stripe->products->create([
                'name' => ($output['type'] == 'ADD-MONEY') ? __('Add Money') : __('TopUp') . '( ' . $basic_settings->site_name . ' )',
            ]);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        //create price for Price Id
        try {
            $price_id = $stripe->prices->create([
                'currency' =>  $currency,
                'unit_amount' => $amount * 100,
                'product' => $product_id->id ?? ""
            ]);
        } catch (Exception $e) {
            throw new Exception("Something Is Wrong, Please Contact With Owner");
        }
        //create payment live links
        try {
            $payment_link = $stripe->paymentLinks->create([
                'line_items' => [
                    [
                        'price' => $price_id->id,
                        'quantity' => 1,
                    ],
                ],
                'after_completion' => [
                    'type' => 'redirect',
                    'redirect' => ['url' => $return_url],
                ],


            ]);
        } catch (Exception $e) {
            throw new Exception("Something Is Wrong, Please Contact With Owner");
        }


        $this->stripeJunkInsert($data);

        return redirect($payment_link->url . "?prefilled_email=" . @$user->email);
    }

    public function getStripeCredentials($output)
    {
        $gateway = $output['gateway'] ?? null;
        if (!$gateway) throw new Exception("Payment gateway not available");
        $client_id_sample = ['publishable_key', 'publishable key', 'publishable-key'];
        $client_secret_sample = ['secret id', 'secret-id', 'secret_id', 'secret key', 'secret_key', 'secret-key'];

        $client_id = '';
        $outer_break = false;
        foreach ($client_id_sample as $item) {
            if ($outer_break == true) {
                break;
            }
            $modify_item = $this->stripePlainText($item);
            foreach ($gateway->credentials ?? [] as $gatewayInput) {
                $label = $gatewayInput->label ?? "";
                $label = $this->stripePlainText($label);

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
            $modify_item = $this->stripePlainText($item);
            foreach ($gateway->credentials ?? [] as $gatewayInput) {
                $label = $gatewayInput->label ?? "";
                $label = $this->stripePlainText($label);

                if ($label == $modify_item) {
                    $secret_id = $gatewayInput->value ?? "";
                    $outer_break = true;
                    break;
                }
            }
        }

        return (object) [
            'publish_key'     => $client_id,
            'secret_key' => $secret_id,

        ];
    }

    public function stripePlainText($string)
    {
        $string = Str::lower($string);
        return preg_replace("/[^A-Za-z0-9]/", "", $string);
    }

    public function stripeJunkInsert($response)
    {
        $output = $this->output;
        $user = auth()->guard(get_auth_guard())->user();
        $creator_table = $creator_id = $wallet_table = $wallet_id = null;

        if (null != $user) {
            $creator_table = auth()->guard(get_auth_guard())->user()->getTable();
            $creator_id = auth()->guard(get_auth_guard())->user()->id;
            $wallet_table = $output['wallet']->getTable();
            $wallet_id = $output['wallet']->id;
        }

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

        $billing_gateway_tamp_data = TemporaryData::create([
            'type'          => PaymentGatewayConst::STRIPE,
            'identifier'    => $response['tx_ref'],
            'data'          => $data,
        ]);

        if ("ORDER" == $output['type']) {
            $billing_tempData = $output['billingTempData']['identifier'];
            TemporaryData::where('identifier', $billing_tempData)->first()->delete();
        }
        return $billing_gateway_tamp_data;
    }

    public function stripeSuccess($output = null)
    {
        if (!$output) $output = $this->output;
        $token = $this->output['tempData']['identifier'] ?? "";
        if (empty($token)) throw new Exception('Transaction failed. Record didn\'t saved properly. Please try again.');
        return $this->createTransactionStripe($output);
    }
    public function createTransactionStripe($output)
    {
        $basic_setting = BasicSettings::first();
        $user = auth()->user();
        $trx_id = 'AM' . getTrxNum();
        $inserted_id = $this->insertRecordStripe($output, $trx_id);
        $this->insertChargesStripe($output, $inserted_id);
        $this->insertDeviceStripe($output, $inserted_id);
        $this->removeTempDataStripe($output);

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

    public function insertRecordStripe($output, $trx_id)
    {

        $trx_id = $trx_id;
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
                //Addmoney
                $id = DB::table("transactions")->insertGetId([
                    'user_id'                       =>  $user_id,
                    'user_wallet_id'                => $output['wallet']->id,
                    'payment_gateway_currency_id'   => $output['currency']->id,
                    'type'                          =>  "ADD-MONEY",
                    'trx_id'                        => $trx_id,
                    'request_amount'                => $output['amount']->requested_amount,
                    'payable'                       => $output['amount']->total_amount,
                    'available_balance'             => $output['wallet']->balance + $output['amount']->requested_amount,
                    'remark'                        => ucwords(remove_speacial_char(PaymentGatewayConst::TYPEADDMONEY, " ")) . " With " . $output['gateway']->name,
                    'details'                       => "Stripe payment successful",
                    'status'                        => 2,
                    'attribute'                      => PaymentGatewayConst::SEND,
                    'created_at'                    => now(),
                ]);
                $this->updateWalletBalanceStripe($output);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
        return $id;
    }

    public function updateWalletBalanceStripe($output)
    {
        $update_amount = $output['wallet']->balance + $output['amount']->requested_amount;
        $output['wallet']->update([
            'balance'   => $update_amount,
        ]);
    }

    public function insertChargesStripe($output, $id)
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

    public function insertDeviceStripe($output, $id)
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

    public function removeTempDataStripe($output)
    {
        TemporaryData::where("identifier", $output['tempData']['identifier'])->delete();
    }

    //for api
    public function stripeInitApi($output = null)
    {
        $basic_settings = BasicSettingsProvider::get();
        if (!$output) $output = $this->output;
        $credentials = $this->getStripeCredentials($output);
        $reference = generateTransactionReference();
        $amount = $output['amount']->total_amount ? number_format($output['amount']->total_amount, 2, '.', '') : 0;
        $currency = $output['currency']['currency_code'] ?? "USD";

        if (auth()->guard(get_auth_guard())->check()) {
            $user = auth()->guard(get_auth_guard())->user();
            $user_email = $user->email;
            $user_phone = $user->full_mobile ?? '';
            $user_name = $user->firstname . ' ' . $user->lastname ?? '';
        }

        if (!Auth::guard(get_auth_guard())->check()) {
            $return_url = route('top.up.stripe.payment.success', $reference);
        }
        $return_url = route('api.v1.api.stripe.payment.success', $reference . "?r-source=" . PaymentGatewayConst::APP);


        // Enter the details of the payment
        $data = [
            'payment_options' => 'card',
            'amount'          => $amount,
            'email'           => $user_email,
            'tx_ref'          => $reference,
            'currency'        =>  $currency,
            'redirect_url'    => $return_url,
            'customer'        => [
                'email'        => $user_email,
                "phone_number" => $user_phone,
                "name"         => $user_name
            ],
            "customizations" => [
                "title"       => $output['type'] == 'ADD-MONEY' ? __('Add Money') : __('TopUp'),
                "description" => dateFormat('d M Y', Carbon::now()),
            ]
        ];

        //start stripe pay link
        $stripe = new \Stripe\StripeClient($credentials->secret_key);

        //create product for Product Id
        try {
            $product_id = $stripe->products->create([
                'name' => ($output['type'] == 'ADD-MONEY') ? __('Add Money') : __('TopUp') . '( ' . $basic_settings->site_name . ' )',
            ]);
        } catch (Exception $e) {
            $error = ['error' => [$e->getMessage()]];
            return Helpers::error($error);
        }
        //create price for Price Id
        try {
            $price_id = $stripe->prices->create([
                'currency' =>  $currency,
                'unit_amount' => $amount * 100,
                'product' => $product_id->id ?? ""
            ]);
        } catch (Exception $e) {
            $error = ['error' => ["Something Is Wrong, Please Contact With Owner"]];
            return Helpers::error($error);
        }
        //create payment live links
        try {

            $payment_link = $stripe->paymentLinks->create([
                'line_items' => [
                    [
                        'price' => $price_id->id,
                        'quantity' => 1,
                    ],
                ],
                'after_completion' => [
                    'type' => 'redirect',
                    'redirect' => ['url' => $return_url],
                ],
            ]);
        } catch (Exception $e) {

            $error = ['error' => ["Something Is Wrong, Please Contact With Owner"]];
            return Helpers::error($error);
        }
        $data['link'] =  $payment_link->url;
        // $data['link'] =  $payment_link->url."?prefilled_email=".@$user->email;
        $data['trx'] =  $reference;

        $this->stripeJunkInsert($data);
        return $data;
    }
}
