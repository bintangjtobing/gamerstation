<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use App\Models\UserWallet;
use App\Models\Transaction;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use App\Models\TemporaryData;
use App\Models\Admin\Currency;
use App\Models\UserNotification;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\BasicSettings;
use App\Constants\NotificationConst;
use App\Http\Controllers\Controller;
use App\Models\Admin\PaymentGateway;
use App\Constants\PaymentGatewayConst;
use App\Traits\ControlDynamicInputFields;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\PaymentGatewayCurrency;
use App\Http\Helpers\Api\Helpers as ApiResponse;

class MoneyOutController extends Controller
{
    use ControlDynamicInputFields;
    public function moneyOutInfo()
    {
        $user = auth()->user();
        $userWallet = UserWallet::where('user_id', $user->id)->get()->map(function ($data) {
            return [
                'balance' => getAmount($data->balance, 2),
                'currency' => get_default_currency_code(),
            ];
        })->first();

        $transactions = Transaction::auth()->moneyOut()->latest()->take(5)->get()->map(function ($item) {
            $statusInfo = [
                "success" =>      1,
                "pending" =>      2,
                "rejected" =>     3,
            ];
            return [
                'id' => $item->id,
                'trx' => $item->trx_id,
                'gateway_name' => $item->currency->gateway->name,
                'gateway_currency_name' => $item->currency->name,
                'transaction_type' => $item->type,
                'request_amount' => getAmount($item->request_amount, 2) . ' ' . get_default_currency_code(),
                'payable' => getAmount($item->payable, 2) . ' ' . $item->user_wallet->currency->code,
                'exchange_rate' => '1 ' . get_default_currency_code() . ' = ' . getAmount($item->currency->rate, 2) . ' ' . $item->currency->currency_code,
                'total_charge' => getAmount($item->charge->total_charge, 2) . ' ' . $item->user_wallet->currency->code,
                'current_balance' => getAmount($item->available_balance, 2) . ' ' . get_default_currency_code(),
                'status' => $item->stringStatus->value,
                'date_time' => $item->created_at,
                'status_info' => (object)$statusInfo,
                'rejection_reason' => $item->reject_reason ?? "",

            ];
        });

        $gateways = PaymentGateway::where('status', 1)->where('slug', PaymentGatewayConst::money_out_slug())->get()->map(function ($gateway) {
            $currencies = PaymentGatewayCurrency::where('payment_gateway_id', $gateway->id)->get()->map(function ($data) {
                return [
                    'id' => $data->id,
                    'payment_gateway_id' => $data->payment_gateway_id,
                    'type' => $data->gateway->type,
                    'name' => $data->name,
                    'alias' => $data->alias,
                    'currency_code' => $data->currency_code,
                    'currency_symbol' => $data->currency_symbol,
                    'image' => $data->image,
                    'min_limit' => getAmount($data->min_limit, 2),
                    'max_limit' => getAmount($data->max_limit, 2),
                    'percent_charge' => getAmount($data->percent_charge, 2),
                    'fixed_charge' => getAmount($data->fixed_charge, 2),
                    'rate' => getAmount($data->rate, 2),
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                ];
            });
            return [
                'id' => $gateway->id,
                'image' => $gateway->image,
                'slug' => $gateway->slug,
                'code' => $gateway->code,
                'type' => $gateway->type,
                'alias' => $gateway->alias,
                'supported_currencies' => $gateway->supported_currencies,
                'input_fields' => $gateway->input_fields ?? null,
                'status' => $gateway->status,
                'currencies' => $currencies

            ];
        });
        $data = [
            'base_curr'    => get_default_currency_code(),
            'base_curr_rate'    => getAmount(1, 2),
            'default_image'    => "public/backend/images/default/default.webp",
            "image_path"  =>  "public/backend/images/payment-gateways",
            'userWallet'   =>   (object)$userWallet,
            'gateways'   => $gateways,
            'transactionss'   =>   $transactions,
        ];
        $message =  ['success' => [__('Money Out Information!')]];
        return ApiResponse::success($message, $data);
    }
    public function moneyOutInsert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|gt:0',
            'gateway' => 'required'
        ]);
        if ($validator->fails()) {
            $error =  ['error' => $validator->errors()->all()];
            return ApiResponse::validation($error);
        }
        $basic_setting = BasicSettings::first();
        $user = auth()->user();
        if ($basic_setting->kyc_verification) {
            if ($user->kyc_verified == 0) {
                $error = ['error' => ['Please submit kyc information!']];
                return ApiResponse::error($error);
            } elseif ($user->kyc_verified == 2) {
                $error = ['error' => ['Please wait before admin approved your kyc information']];
                return ApiResponse::error($error);
            } elseif ($user->kyc_verified == 3) {
                $error = ['error' => ['Admin rejected your kyc information, Please re-submit again']];
                return ApiResponse::error($error);
            }
        }

        $userWallet = UserWallet::where('user_id', $user->id)->where('status', 1)->first();

        $gate = PaymentGatewayCurrency::whereHas('gateway', function ($gateway) {
            $gateway->where('slug', PaymentGatewayConst::money_out_slug());
            $gateway->where('status', 1);
        })->where('alias', $request->gateway)->first();
        if (!$gate) {
            $error = ['error' => ['Invalid Gateway!']];
            return ApiResponse::error($error);
        }
        $baseCurrency = Currency::default();
        if (!$baseCurrency) {
            $error = ['error' => ['Default Currency Not Setup Yet!']];
            return ApiResponse::error($error);
        }
        $amount = $request->amount;

        $min_limit =  $gate->min_limit / $gate->rate;
        $max_limit =  $gate->max_limit / $gate->rate;
        if ($amount < $min_limit || $amount > $max_limit) {
            $error = ['error' => ['Please follow the transaction limit!']];
            return ApiResponse::error($error);
        }
        //gateway charge


        //exchange rate
        $base_rate = $baseCurrency->rate;
        $gateway_rate = $gate->rate;
        $exchange_rate = $base_rate / $gateway_rate;
        //charge
        $fixed_charge = $gate->fixed_charge * $exchange_rate;
        $percent_charge =  ($amount / 100) * $gate->percent_charge;
        $total_charge = $fixed_charge + $percent_charge;
        //will gate
        $will_gate = $amount / $exchange_rate;
        //paybale
        $paybale = $amount + $total_charge;
        //conversion_amount
        $conversion_amount = $amount * $gateway_rate;


        // $fixedCharge = $gate->fixed_charge *  $gate->rate;
        // $charge = $fixedCharge + $percent_charge;
        // $conversion_amount = $request->amount * $gate->rate;
        // $will_get = $conversion_amount -  $charge;

        //base_cur_charge
        $baseFixedCharge = $gate->fixed_charge *  $baseCurrency->rate;
        $basePercent_charge = ($request->amount / 100) * $gate->percent_charge;
        $base_total_charge = $baseFixedCharge + $basePercent_charge;
        $reduceAbleTotal = $amount + $base_total_charge;
        if ($reduceAbleTotal > $userWallet->balance) {
            $error = ['error' => ['Insuficiant Balance!']];
            return ApiResponse::error($error);
        }

        $insertData = [
            'user_id' => $user->id,
            'wallet_id' => $userWallet->id,
            'trx_id' =>  generate_unique_string('transactions', 'trx_id', 16),
            // 'trx_id' => 'MO' . getTrxNum(),
            'amount' =>  $amount,
            'base_cur_charge' => $base_total_charge,
            'base_cur_rate' => $baseCurrency->rate,
            'gateway_id' => $gate->gateway->id,
            'gateway_currency_id' => $gate->id,
            'gateway_currency' => strtoupper($gate->currency_code),
            'gateway_percent_charge' => $percent_charge,
            'gateway_fixed_charge' => $fixed_charge,
            'gateway_charge' => $total_charge,
            'gateway_rate' => $gate->rate,
            'conversion_amount' => $conversion_amount,
            'will_get' => $will_gate,
        ];
        $identifier = generate_unique_string("transactions", "trx_id", 16);
        $inserted = TemporaryData::create([
            // 'type'          => PaymentGatewayConst::TYPEMONEYOUT,
            'type'          => PaymentGatewayConst::TYPEWITHDRAW,
            'identifier'    => $identifier,
            'data'          => $insertData,
        ]);
        if ($inserted) {
            $payment_gateway = PaymentGateway::where('id', $gate->payment_gateway_id)->first();
            $payment_informations = [
                'trx' =>  $identifier,
                'gateway_currency_name' =>  $gate->name,
                'request_amount' => getAmount($request->amount, 2) . ' ' . get_default_currency_code(),
                'exchange_rate' => $exchange_rate . ' ' . $gate->currency_code,
                // 'exchange_rate' => "1" . ' ' . get_default_currency_code() . ' = ' . getAmount($gate->rate) . ' ' . $gate->currency_code,
                'conversion_amount' =>  getAmount($conversion_amount, 2) . ' ' . $gate->currency_code,
                'total_charge' => getAmount($total_charge, 2) . ' ' . get_default_currency_code(),
                'will_get' => getAmount($will_gate, 2) . ' ' . $gate->currency_code,
                'payable' => getAmount($paybale, 2) . ' ' . get_default_currency_code(),

            ];

            $data = [
                'payment_informations' => $payment_informations,
                'gateway_type' => $payment_gateway->type,
                'gateway_currency_name' => $gate->name,
                'alias' => $gate->alias,
                'details' => $payment_gateway->desc ?? null,
                'input_fields' => $payment_gateway->input_fields ?? null,
                'url' => route('api.v1.user.money.out.confirmed'),
                'method' => "post",
            ];
            $message =  ['success' => ['Money Out Inserted Successfully']];
            return ApiResponse::success($message, $data);
        } else {
            $error = ['error' => ['Something is wrong!']];
            return ApiResponse::error($error);
        }
    }
    public function moneyOutConfirmed(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trx'  => "required",
        ]);
        if ($validator->fails()) {
            $error =  ['error' => $validator->errors()->all()];
            return ApiResponse::validation($error);
        }
        $track = TemporaryData::where('identifier', $request->trx)->where('type', PaymentGatewayConst::TYPEWITHDRAW)->first();
        // $track = TemporaryData::where('identifier', $request->trx)->where('type', PaymentGatewayConst::TYPEMONEYOUT)->first();
        if (!$track) {
            $error = ['error' => ["Sorry, your payment information is invalid"]];
            return ApiResponse::error($error);
        }
        $moneyOutData =  $track->data;
        $gateway = PaymentGateway::where('id', $moneyOutData->gateway_id)->first();
        $payment_fields = $gateway->input_fields ?? [];
        $validation_rules = $this->generateValidationRules($payment_fields);
        $validator2 = Validator::make($request->all(), $validation_rules);
        if ($validator2->fails()) {
            $message =  ['error' => $validator2->errors()->all()];
            return ApiResponse::error($message);
        }
        $validated = $validator2->validate();
        $get_values = $this->placeValueWithFields($payment_fields, $validated);
        try {
            $inserted_id = $this->insertRecordManual($moneyOutData, $gateway, $get_values);

            $this->insertChargesManual($moneyOutData, $inserted_id);
            $this->insertDeviceManual($moneyOutData, $inserted_id);
            $track->delete();
            $message =  ['success' => ['Money Out request send to admin successfully']];
            return ApiResponse::onlysuccess($message);
        } catch (Exception $e) {
            $error = ['error' => ["Sorry,something is wrong"]];
            return ApiResponse::error($error);
        }
    }
    public function insertRecordManual($moneyOutData, $gateway, $get_values)
    {
        // $trx_id = $moneyOutData->trx_id ?? 'MO' . getTrxNum();
        $trx_id = $moneyOutData->trx_id ??  generate_unique_string('transactions', 'trx_id', 16);

        $authWallet = UserWallet::where('id', $moneyOutData->wallet_id)->where('user_id', $moneyOutData->user_id)->first();
        $afterCharge = ($authWallet->balance - ($moneyOutData->amount + $moneyOutData->base_cur_charge));
        DB::beginTransaction();
        try {
            $id = DB::table("transactions")->insertGetId([
                'user_id'                       => auth()->user()->id,
                'user_wallet_id'                => $moneyOutData->wallet_id,
                'payment_gateway_currency_id'   => $moneyOutData->gateway_currency_id,
                'type'                          => PaymentGatewayConst::TYPEWITHDRAW,
                // 'type'                          => PaymentGatewayConst::TYPEMONEYOUT,
                'trx_id'                        => $trx_id,
                'request_amount'                => $moneyOutData->amount,
                'payable'                       => ($moneyOutData->amount + $moneyOutData->base_cur_charge),
                'available_balance'             => $afterCharge,
                'remark'                        => ucwords(remove_speacial_char(PaymentGatewayConst::TYPEWITHDRAW, " ")) . " by " . $gateway->name,
                // 'remark'                        => ucwords(remove_speacial_char(PaymentGatewayConst::TYPEMONEYOUT, " ")) . " by " . $gateway->name,
                'details'                       => json_encode($get_values),
                'status'                        => 2,
                'created_at'                    => now(),
            ]);
            $this->updateWalletBalanceManual($id, $authWallet, $afterCharge);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $error = ['error' => ["Sorry,something is wrong"]];
            return ApiResponse::error($error);
        }
        return $id;
    }
    public function insertChargesManual($moneyOutData, $id)
    {
        DB::beginTransaction();
        try {
            DB::table('transaction_charges')->insert([
                'transaction_id'    => $id,
                'percent_charge'    => $moneyOutData->gateway_percent_charge,
                'fixed_charge'      => $moneyOutData->gateway_fixed_charge,
                'total_charge'      => $moneyOutData->gateway_charge,
                'created_at'        => now(),
            ]);
            DB::commit();

            //notification
            $notification_content = [
                'title'         => "Money Out",
                'message'       => "Your Money Out request send to admin " . $moneyOutData->amount . ' ' . get_default_currency_code() . " successful",
                'image'         => files_asset_path('profile-default'),
            ];

            UserNotification::create([
                'type'      => NotificationConst::MONEY_OUT,
                'user_id'  =>  auth()->user()->id,
                'message'   => $notification_content,
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $error = ['error' => ["Sorry,something is wrong"]];
            return ApiResponse::error($error);
        }
    }
    public function insertDeviceManual($output, $id)
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
            $error = ['error' => ["Sorry,something is wrong"]];
            return ApiResponse::error($error);
        }
    }
    public function updateWalletBalanceManual($transaction_id, $authWalle, $afterCharge)
    {
        $transaction = Transaction::find($transaction_id);
        if ($transaction->status == 1) {
            $authWalle->update([
                'balance'   => $afterCharge,
            ]);
        }
    }
}
