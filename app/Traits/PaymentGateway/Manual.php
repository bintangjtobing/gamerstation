<?php

namespace App\Traits\PaymentGateway;

use Exception;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use App\Models\TemporaryData;
use Illuminate\Support\Carbon;
use App\Models\UserNotification;
use Illuminate\Support\Facades\DB;
use App\Constants\NotificationConst;
use App\Http\Helpers\PaymentGateway;
use Illuminate\Support\Facades\Auth;
use App\Constants\PaymentGatewayConst;
use Illuminate\Support\Facades\Session;
use App\Traits\ControlDynamicInputFields;
use Illuminate\Support\Facades\Validator;
use App\Http\Helpers\Api\PaymentGatewayApi;
use App\Models\Admin\PaymentGatewayCurrency;
use App\Http\Helpers\Api\Helpers as ApiResponse;
use App\Http\Controllers\User\AddMoneyController;
use App\Models\Admin\PaymentGateway as PaymentGatewayModel;

trait Manual
{
    use ControlDynamicInputFields;
    public function manualInit($output = null)
    {
        if (!$output) $output = $this->output;
        $gatewayAlias = $output['gateway']['alias'];
        $identifier = generate_unique_string("transactions", "trx_id", 16);

        $this->manualJunkInsert($identifier);
        Session::put('identifier', $identifier);
        Session::put('output', $output);

        if ('ORDER' == $output['type']) {
            return redirect()->route('top.up.manual.payment');
        }
        return redirect()->route('user.add.money.manual.payment');
    }

    public function manualJunkInsert($response)
    {
        $output = $this->output;

        // $data = [
        //     'gateway'   => $output['gateway']->id,
        //     'currency'  => $output['currency']->id,
        //     'amount'    => json_decode(json_encode($output['amount']), true),
        //     'response'  => $response,
        // ];
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

        return TemporaryData::create([
            'type'          => PaymentGatewayConst::MANUA_GATEWAY,
            'identifier'    => $response,
            'data'          => $data,
        ]);
    }
    public function manualPaymentConfirmed(Request $request)
    {


        $output = session()->get('output');

        $tempData = Session::get('identifier');
        $hasData = TemporaryData::where('identifier', $tempData)->first();
        $gateway = PaymentGatewayModel::manual()->where('slug', PaymentGatewayConst::add_money_slug())->where('id', $hasData->data->gateway)->first();
        $payment_fields = $gateway->input_fields ?? [];

        $validation_rules = $this->generateValidationRules($payment_fields);
        $payment_field_validate = Validator::make($request->all(), $validation_rules)->validate();
        $get_values = $this->placeValueWithFields($payment_fields, $payment_field_validate);


        try {
            $inserted_id = $this->insertRecordManual($output, $get_values, $hasData);
            $this->insertChargesManual($output, $inserted_id);
            $this->insertDeviceManual($output, $inserted_id);
            $this->removeTempDataManual($output);

            if ("ORDER" == $output['type']) {
                $order_token = $output['billingTempData']['identifier'];
                TemporaryData::where('identifier', $order_token)->delete();
                return redirect()->route("index")->with(['success' => ['Top up request send to admin successfully']]);
            }

            return redirect()->route("user.add.money.index")->with(['success' => ['Add Money request send to admin successfully']]);
        } catch (Exception $e) {
            return back()->with(['error' => [$e->getMessage()]]);
        }
    }


    public function insertRecordManual($output, $get_values, $hasData)
    {


        $trx_id = generate_unique_string("transactions", "trx_id", 16);
        $token = $this->output['tempData']['identifier'] ?? "";

        DB::beginTransaction();
        try {
            if ("ORDER" == $output['type']) {

                $get_values['manual_user_data'] = $get_values;
                $getValuesBillingTempData = ($hasData->data->billingTempData->data ?? array_merge(((array)$output['billingTempData']->data), $get_values));

                // dd($getValuesBillingTempData);

                $id = DB::table("transactions")->insertGetId([
                    'user_id'                       =>  Auth::guard(get_auth_guard())->user()->id,
                    'user_wallet_id'                => $output['wallet']->id,
                    'payment_gateway_currency_id'   => $output['currency']->id,
                    'type'                          => PaymentGatewayConst::TYPETOPUP,
                    'trx_id'                        => $trx_id,
                    'request_amount'                => $output['amount']->requested_amount,
                    'payable'                       => $output['amount']->total_amount,
                    'available_balance'             => $output['wallet']->balance,
                    'remark'                        => ucwords(remove_speacial_char(PaymentGatewayConst::TYPETOPUP, " ")) . " With " . $output['gateway']->name,
                    'details'                       => json_encode($getValuesBillingTempData),
                    'status'                        => 1,
                    'created_at'                    => now(),
                ]);
            } else {
                $id = DB::table("transactions")->insertGetId([
                    'user_id'                       => auth()->user()->id,
                    'user_wallet_id'                => $output['wallet']->id,
                    'payment_gateway_currency_id'   => $output['currency']->id,
                    'type'                          => PaymentGatewayConst::TYPEADDMONEY,
                    'trx_id'                        => $trx_id,
                    'request_amount'                => $output['amount']->requested_amount,
                    'payable'                       => $output['amount']->total_amount,
                    'available_balance'             => $output['wallet']->balance,
                    'remark'                        => ucwords(remove_speacial_char(PaymentGatewayConst::TYPEADDMONEY, " ")) . " With " . $output['gateway']->name,
                    'details'                       => json_encode($get_values),
                    'status'                        => 1,
                    'created_at'                    => now(),
                ]);
            }


            DB::commit();
        } catch (Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
        return $id;
    }


    public function insertChargesManual($output, $id)
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
            if ($output['type'] == 'ADD-MONEY') {
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
                    'message'       => "TopUp " . $output['amount']->requested_amount . " " . get_default_currency_code() . ' request to admin',
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

    public function insertDeviceManual($output, $id)
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

    public function removeTempDataManual($output)
    {
        $token = session()->get('identifier');
        TemporaryData::where("identifier", $token)->delete();
    }

    //for api
    public function manualInitApi($output = null)
    {
        if (!$output) $output = $this->output;
        $gatewayAlias = $output['gateway']['alias'];
        $identifier = generate_unique_string("transactions", "trx_id", 16);
        if ('ORDER' == $output['type']) {
            $this->manualJunkInsertApi($identifier, $output);
        }
        $this->manualJunkInsert($identifier);
        $response = [
            'trx' => $identifier,
        ];
        return $response;
    }

    public function manualJunkInsertApi($response, $output)
    {
        $output = $output;
        $recharge = $output['request_data']['recharge'];
        $recharge_coin = explode('|', $recharge)[0];

        $data = [
            'gateway'   => $output['gateway']->id,
            'currency'  => $output['currency']->id,
            'amount'    => json_decode(json_encode($output['amount']), true),
            'billingTempData'  => $output['billingTempData'],
            'response'  => $response,
            'type' => $output['type'],
            'recharge_coin' => $recharge_coin ?? '',
            'request_data' => $output['request_data'] ?? '',
        ];

        return TemporaryData::create([
            'type'          => PaymentGatewayConst::MANUA_GATEWAY,
            'identifier'    => $response,
            'data'          => $data,
        ]);
    }
    public function manualPaymentConfirmedApi(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'track' => 'required',
        ]);
        if ($validator->fails()) {
            $error =  ['error' => $validator->errors()->all()];
            return ApiResponse::validation($error);
        }
        $track = $request->track;
        $hasData = TemporaryData::where('identifier', $track)->first();
        if (!$hasData) {
            $error = ['error' => ["Sorry, your payment information is invalid"]];
            return ApiResponse::error($error);
        }

        $gateway = PaymentGatewayModel::manual()->where('id', $hasData->data->gateway)->first();
        $payment_fields = $gateway->input_fields ?? [];

        $validation_rules = $this->generateValidationRules($payment_fields);
        $validator2 = Validator::make($request->all(), $validation_rules);


        if ($validator2->fails()) {
            $message =  ['error' => $validator2->errors()->all()];
            return ApiResponse::error($message);
        }
        $validated = $validator2->validate();
        $get_values = $this->placeValueWithFields($payment_fields, $validated);
        $payment_gateway_currency = PaymentGatewayCurrency::where('id', $hasData->data->currency)->first();

        $gateway_request = ['currency' => $payment_gateway_currency->alias, 'amount'  => $hasData->data->amount->requested_amount];
        if (isset($hasData->data->billingTempData->type)) {
            if ('ORDER' == $hasData->data->billingTempData->type) {

                $output = PaymentGatewayApi::init($gateway_request)->type('ORDER')->gateway()->get();
            }
        } else {
            $output = PaymentGatewayApi::init($gateway_request)->type('ADD-MONEY')->gateway()->get();
        }

        try {
            $trx_id = 'AM' . getTrxNum();

            $user = auth()->user();

            $inserted_id = $this->insertRecordManual($output, $get_values, $hasData ?? []);
            $this->insertChargesManual($output, $inserted_id);
            $this->insertDeviceManual($output, $inserted_id);
            $this->removeTempDataManual($output);


            if (isset($hasData->data->billingTempData->type)) {
                if ('ORDER' == $hasData->data->billingTempData->type) {

                    $manual_token = $hasData->identifier;
                    TemporaryData::where('identifier', $manual_token)->delete();

                    $order_token = $hasData->data->billingTempData->identifier;
                    TemporaryData::where('identifier', $order_token)->delete();

                    $message =  ['success' => ['Top Up Successful']];
                }
            } else {
                $message =  ['success' => ['Add Money Successful']];
            }
            $hasData->delete();
            return ApiResponse::onlysuccess($message);
        } catch (Exception $e) {
            $error = ['error' => [$e->getMessage()]];
            return ApiResponse::error($error);
        }
    }
}
