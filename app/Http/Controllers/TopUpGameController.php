<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\UserWallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TemporaryData;
use App\Http\Helpers\Response;
use App\Models\Admin\Currency;
use Illuminate\Support\Carbon;
use App\Models\Admin\TopUpGame;
use App\Models\UserNotification;
use App\Models\TransactionCharge;
use Illuminate\Support\Facades\DB;
use App\Constants\NotificationConst;
use App\Models\Admin\PaymentGateway;
use Illuminate\Support\Facades\Auth;
use App\Traits\PaymentGateway\Manual;
use App\Traits\PaymentGateway\Stripe;
use Illuminate\Http\RedirectResponse;
use App\Constants\PaymentGatewayConst;
use Illuminate\Support\Facades\Session;
use App\Models\Admin\TransactionSetting;
use App\Traits\ControlDynamicInputFields;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\PaymentGatewayCurrency;
use KingFlamez\Rave\Facades\Rave as Flutterwave;
use App\Http\Helpers\PaymentGateway as PaymentGatewayHelper;

class TopUpGameController extends Controller
{
    use ControlDynamicInputFields, Stripe, Manual;

    function detailsTopUp($slug)
    {
        $top_up_game = TopUpGame::where('slug', $slug)->first();
        return view('frontend.details-top-up', compact('top_up_game'));
    }
    function TopUpSubmit(Request $request)
    {
        $top_up_id = $request->top_up_id;
        $top_up = TopUpGame::findOrFail($top_up_id)->input_fields->input_fields_player_id ?? [];

        $validation_rules = $this->generateValidationRulesForTopup($top_up);

        $validated = Validator::make($request->all(), $validation_rules)->validate();
        $get_values = $this->placeValueWithFieldsTopUp($top_up, $validated);

        $recharge = $request->recharge;
        $recharge = explode('|', $recharge);

        $top_up_game_charge = TransactionSetting::first();
        //charge
        $top_up_game_fix_charge = $top_up_game_charge->fixed_charge;
        $top_up_game_percent_charge = ($top_up_game_charge->percent_charge * $recharge[1]) / 100;
        $top_up_game_total_charge = $top_up_game_fix_charge + $top_up_game_percent_charge;
        //payable
        $payable = $top_up_game_total_charge + $recharge[1];
        $data = [
            'player_id' => $get_values,
            'recharge_coin' => $recharge,
            'total_charge' => $top_up_game_total_charge,
            'payable' => $payable,
            'coin_type' => $request->coin_type,
            'currency' => get_default_currency_code(),
        ];

        $token = generate_unique_string("temporary_datas", "identifier", 16);
        try {
            TemporaryData::create([
                'type'          => 'TOPUPGAME',
                'identifier'    => $token,
                'data'          => $data
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went wrong!. Please try again']]);
        }
        return redirect()->route('top.up.preview', $token);
    }

    function preview($token)
    {
        $temp_data = TemporaryData::where('identifier', $token)->first();
        if (empty($temp_data)) {
            return redirect()->route('index');
        }
        return view('frontend.preview-top-up', compact('temp_data', 'token'));
    }

    function previewSubmit(Request $request)
    {
        $amount = $request->amount;
        $token = $request->token;
        return redirect()->route('top.up.checkout', $token);
    }

    function checkout($token)
    {
        $temp_data = TemporaryData::where('identifier', $token)->first();
        if (empty($temp_data)) {
            return redirect()->route('index');
        }

        $payment_gateways_currencies = PaymentGatewayCurrency::whereHas('gateway', function ($gateway) {
            $gateway->where('slug', PaymentGatewayConst::add_money_slug());
            $gateway->where('status', 1);
        })->get();

        return view('frontend.checkout-top-up', compact('temp_data', 'token', 'payment_gateways_currencies'));
    }

    function order(Request $request)
    {
        $tempData = $request->token;
        $validated = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'nullable|string',
            'email' => 'required|email',
            'phone_number' => 'nullable',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'zip' => 'nullable',
            'currency' => 'required'
        ])->validate();

        if ($request->currency == "wallet_balance") {
            $tempData = TemporaryData::where('identifier', $tempData)->first();
            $data = [
                'tempData' => $tempData->data,
                'order_details' => $validated,
            ];
            $transaction_charge = TransactionSetting::first();
            $trx_id = generate_unique_string("transactions", "trx_id", 16);
            $payable = $request->payable;
            $user_id = auth()->user()->id;
            $userWallet = UserWallet::where('user_id', $user_id)->first();
            $user_balance = $userWallet->balance;
            if ($user_balance < $payable) {
                return back()->with(['error' => ['Insufficient Balance']]);
            }
            try {
                $updated_balance = $userWallet->balance -=  $payable;
                $userWallet->save();
                $id = DB::table("transactions")->insertGetId([
                    'user_id'                       => auth()->user()->id,
                    'user_wallet_id'                => $userWallet->id,
                    'payment_gateway_currency_id'   => null,
                    'type'                          =>  "TOP-UP",
                    'trx_id'                        => $trx_id,
                    'request_amount'                => $request->amount,
                    'payable'                       => $payable,
                    'available_balance'             => $updated_balance,
                    'remark'                        => ucwords(remove_speacial_char(PaymentGatewayConst::TYPETOPUP, " ")) . " With wallet",
                    'details'                       => json_encode($data),
                    'status'                        => 2,
                    'created_at'                    => now(),
                ]);

                TransactionCharge::create([
                    'transaction_id'    => $id,
                    'percent_charge'    => $transaction_charge->percent_charge,
                    'fixed_charge'      => $transaction_charge->fixed_charge,
                    'total_charge'      => $payable,
                    'created_at'        => now(),
                ]);

                $notification_content = [
                    'title'         => "Top Up",
                    'message'       => "Your " . $request->coin . ' ' . $request->coin_type . " has been successful",
                    'time'      => Carbon::now()->diffForHumans(),
                    'image'         => files_asset_path('profile-default'),
                ];

                UserNotification::create([
                    'type'      => NotificationConst::TOP_UP,
                    'user_id'  =>  auth()->user()->id,
                    'message'   => $notification_content,
                ]);;
                TemporaryData::where('identifier', $tempData['identifier'])->first()->delete();
            } catch (Exception $e) {

                return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
            }

            return redirect()->route('index')->with(['success' => ['Top up successfully']]);
        }
        $tempData = TemporaryData::where('identifier', $tempData)->first();
        if ($tempData == null) return redirect()->route('index');
        $data = [
            'tempData' => $tempData->data,
            'order_details' => $validated,
        ];

        $token = generate_unique_string("temporary_datas", "identifier", 16);
        try {
            DB::beginTransaction();
            $billingTempData = TemporaryData::create([
                'type'          => 'ORDER',
                'identifier'    => $token,
                'data'          => $data
            ]);
            $tempData = TemporaryData::where('identifier', $tempData['identifier'])->delete();
            $instance = PaymentGatewayHelper::init($request->all())->type('ORDER')->billingTempData($billingTempData)->gateway()->render();


            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with(['error' => [$e->getMessage()]]);
        }
        return $instance;
    }

    public function success(Request $request, $gateway)
    {

        $requestData = $request->all();
        $token = $requestData['token'] ?? "";
        $checkTempData = TemporaryData::where("type", $gateway)->where("identifier", $token)->first();
        if (!$checkTempData) return redirect()->route('index')->with(['error' => ['Transaction faild. Record didn\'t saved properly. Please try again.']]);

        $checkTempData = $checkTempData->toArray();

        try {
            PaymentGatewayHelper::init($checkTempData)->type(PaymentGatewayConst::TYPEADDMONEY)->responseReceive();
        } catch (Exception $e) {

            return back()->with(['error' => [$e->getMessage()]]);
        }
        return redirect()->route('index')->with(['success' => ['TopUp Successfully']]);
    }

    public function cancel(Request $request, $gateway)
    {
        $token = session()->get('identifier');
        if ($token) {
            TemporaryData::where("identifier", $token)->delete();
        }
        return redirect()->route('index');
    }

    public function payment($gateway)
    {

        $page_title = "Stripe Payment";
        $tempData = Session::get('identifier');
        $hasData = TemporaryData::where('identifier', $tempData)->where('type', $gateway)->first();
        if (!$hasData) {
            return redirect()->route('top.up.order');
        }
        return view('user.sections.game-topup.automatic.' . $gateway, compact("page_title", "hasData"));
    }

    public function getCurrenciesXml(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'target'        => "required|integer|exists:payment_gateways,code",
        ]);
        if ($validator->fails()) {
            return Response::error($validator->errors(), null, 400);
        }
        $validated = $validator->validate();

        $user_wallets = UserWallet::auth()->get();
        $user_currencies = Currency::whereIn('id', $user_wallets->pluck('currency_id')->toArray())->get();

        try {
            $payment_gateways = PaymentGateway::active()->gateway($validated['target'])->withWhereHas('currencies', function ($q) use ($user_currencies) {
                $q->whereIn("currency_code", $user_currencies->pluck("code")->toArray());
            })->has("currencies")->first();
        } catch (Exception $e) {
            $error = ['error' => ['Something went worng!. Please try again.']];
            return Response::error($error, null, 500);
        }

        if (!$payment_gateways) {
            $error = ['error' => ['Opps! Invalid Payment Gateway']];
            return Response::error($error, null, 404);
        }

        $success = ['success' => ['Request server successfully']];
        return Response::success($success, $payment_gateways, 200);
    }

    public function manualPayment()
    {

        $tempData = Session::get('identifier');
        $hasData = TemporaryData::where('identifier', $tempData)->first();
        $gateway = PaymentGateway::manual()->where('slug', PaymentGatewayConst::add_money_slug())->where('id', $hasData->data->gateway)->first();

        $page_title = "Manual Payment" . ' ( ' . $gateway->name . ' )';
        if (!$hasData) {
            return redirect()->route('index');
        }
        return view('user.sections.game-topup.manual.payment_confirmation', compact("page_title", "hasData", 'gateway'));
    }

    function search(Request $request)
    {
        if ($request->ajax()) {
            $topup_game = TopUpGame::search($request->topup_title)->get();

            $result = '';
            if ($topup_game->isEmpty()) {
                $result .= ' <li>
                    <span class="text-danger text-center">No Result Found</span>
                </li>';
            } else {
                foreach ($topup_game as $topup) {
                    $url = route('top.up.details', $topup->slug);
                    $result .= ' <li>
                                    <a href="' . $url . '">' . $topup->title . '</a>
                                </li>';
                }
            }

            return response()->json($result);
        }
    }

    //stripe success
    public function stripePaymentSuccess($trx)
    {
        $token = $trx;
        $checkTempData = TemporaryData::where("type", PaymentGatewayConst::STRIPE)->where("identifier", $token)->first();
        if (!$checkTempData) return redirect()->route('index')->with(['error' => [__('Transaction failed. The record did not save properly. Please try again.')]]);
        $checkTempData = $checkTempData->toArray();
        try {
            PaymentGatewayHelper::init($checkTempData)->type(PaymentGatewayConst::TYPEADDMONEY)->responseReceive('stripe');
        } catch (Exception $e) {
            return back()->with(['error' => [__("Something is wrong")]]);
        }


        return redirect()->route("index")->with(['success' => ['TopUp Successfully']]);
    }

    public function flutterwaveCallback()
    {
        $status = request()->status;
        //if payment is successful
        if ($status ==  'successful') {

            $transactionID = Flutterwave::getTransactionIDFromCallback();
            $data = Flutterwave::verifyTransaction($transactionID);

            $requestData = request()->tx_ref;
            $token = $requestData;

            $checkTempData = TemporaryData::where("type", 'flutterwave')->where("identifier", $token)->first();

            if (!$checkTempData) return redirect()->route("index")->with(['error' => [__('Transaction failed. The record did not save properly. Please try again.')]]);

            $checkTempData = $checkTempData->toArray();

            try {
                PaymentGatewayHelper::init($checkTempData)->type(PaymentGatewayConst::TYPETOPUP)->responseReceive('flutterWave');
            } catch (Exception $e) {
                return back()->with(['error' => [$e->getMessage()]]);
            }
            return redirect()->route('index')->with(['success' => ['Topup Successfully']]);
        } elseif ($status ==  'cancelled') {
            return redirect()->route('index')->with(['error' => ['Transaction failed']]);
        } else {
            return redirect()->route('index')->with(['error' => ['Transaction failed']]);
        }
    }

    /**
     * Redirect Users for collecting payment via Button Pay (JS Checkout)
     */
    public function redirectBtnPay(Request $request, $gateway)
    {
        try {
            return PaymentGatewayHelper::init([])->type('ADD-MONEY')->handleBtnPay($gateway, $request->all());
        } catch (Exception $e) {
            return redirect()->route('index')->with(['error' => [$e->getMessage()]]);
        }
    }

    public function postSuccess(Request $request, $gateway)
    {
        try {
            $token = PaymentGatewayHelper::getToken($request->all(), $gateway);
            $temp_data = TemporaryData::where("identifier", $token)->first();
            Auth::guard($temp_data->data->creator_guard)->loginUsingId($temp_data->data->creator_id);
        } catch (Exception $e) {
            return redirect()->route('index');
        }
        return $this->successGlobal($request, $gateway);
    }

    public function postCancel(Request $request, $gateway)
    {
        try {
            $token = PaymentGatewayHelper::getToken($request->all(), $gateway);
            $temp_data = TemporaryData::where("identifier", $token)->first();
            Auth::guard($temp_data->data->creator_guard)->loginUsingId($temp_data->data->creator_id);
        } catch (Exception $e) {
            return redirect()->route('index');
        }
        return $this->cancelGlobal($request, $gateway);
    }

    public function successGlobal(Request $request, $gateway)
    {
        try {
            $token = PaymentGatewayHelper::getToken($request->all(), $gateway);
            $temp_data = TemporaryData::where("identifier", $token)->first();
            if (Transaction::where('callback_ref', $token)->exists()) {
                if (!$temp_data) return redirect()->route('index')->with(['success' => [__('Transaction request sended successfully!')]]);
            } else {
                if (!$temp_data) return redirect()->route('index')->with(['error' => [__('Transaction failed. Record didn\'t saved properly. Please try again')]]);
            }

            $update_temp_data = json_decode(json_encode($temp_data->data), true);
            $update_temp_data['callback_data']  = $request->all();
            $temp_data->update([
                'data'  => $update_temp_data,
            ]);
            $temp_data = $temp_data->toArray();
            $instance = PaymentGatewayHelper::init($temp_data)->type(PaymentGatewayConst::TYPEADDMONEY)->responseReceive($temp_data['type']);
            if ($instance instanceof RedirectResponse) return $instance;
        } catch (Exception $e) {

            return back()->with(['error' => [$e->getMessage()]]);
        }
        return redirect()->route("index")->with(['success' => [__('TopUp Successfully')]]);
    }

    public function cancelGlobal(Request $request, $gateway)
    {
        $token = PaymentGatewayHelper::getToken($request->all(), $gateway);
        if ($temp_data = TemporaryData::where("identifier", $token)->first()) {
            $temp_data->delete();
        }
        return redirect()->route('index');
    }

    public function callback(Request $request, $gateway)
    {
        $callback_token = $request->get('token');
        $callback_data = $request->all();
        try {
            PaymentGatewayHelper::init([])->type(PaymentGatewayConst::TYPEADDMONEY)->handleCallback($callback_token, $callback_data, $gateway);
        } catch (Exception $e) {
            // handle Error
            logger($e);
        }
    }
}
