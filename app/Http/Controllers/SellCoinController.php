<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\UserWallet;
use Illuminate\Http\Request;
use App\Models\TemporaryData;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\PaymentGateway;
use App\Constants\PaymentGatewayConst;
use App\Models\Admin\Currency;
use App\Traits\ControlDynamicInputFields;
use App\Traits\Transaction as TransactionTrait;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\PaymentGatewayCurrency;
use App\Models\Transaction;
use Illuminate\Support\Facades\Session;

class SellCoinController extends Controller
{
    use ControlDynamicInputFields, TransactionTrait;

    public function index()
    {
        $page_title = 'Sell Coin';
        $payment_gateways = PaymentGateway::moneyOut()->manual()->active()->get();
        $receving_method = PaymentGateway::sellCoin()->manual()->active()->first();
        $user_wallets = UserWallet::auth()->get();
        return view('user.sections.sell-coin.index', compact('page_title', 'payment_gateways', 'user_wallets', 'receving_method'));
    }

    public function receivingMethodSubmit(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'receiving_gateway' => 'required',
            'receiving_amount' => 'required',
        ])->validate();
        $gateway = PaymentGateway::moneyOut()->manual()->where('alias', $validator['receiving_gateway'])->first();
        $senderMethod = PaymentGatewayCurrency::where('payment_gateway_id', $gateway->id)->first();
        $receiverMethodGateway = PaymentGateway::sellCoin()->first();
        $receiverMethod = PaymentGatewayCurrency::whereHas('gateway', function ($gateway) {
            $gateway->where('slug', PaymentGatewayConst::sell_coin_slug());
            $gateway->where('status', 1);
        })->first();


        $payment_fields = $receiverMethodGateway->input_fields ?? [];
        $validation_rules = $this->generateValidationRules($payment_fields);
        $payment_field_validate = Validator::make($request->all(), $validation_rules)->validate();
        $get_values = $this->placeValueWithFields($payment_fields, $payment_field_validate);

        $charges = $this->sellCoinCharges($validator['receiving_amount'], $receiverMethod, $senderMethod); // Withdraw charge
        $data = [
            'receiving_gateway' => $validator['receiving_gateway'],
            'receiving_amount' => $validator['receiving_amount'],
            'receiver_details' => $get_values,
            'charge' => $charges,
        ];
        try {
            $token = generate_unique_string("temporary_datas", "identifier", 16);
            TemporaryData::create([
                'type'          => PaymentGatewayConst::sell_coin_slug(),
                'identifier'    => $token,
                'data'          => $data
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }
        return redirect()->route('user.sell.coin.instruction', $token);
    }


    // public function submit(Request $request)
    // {

    //     $validated = Validator::make($request->all(), [
    //         'gateway'           => "required|exists:payment_gateways,alias",
    //         'amount'            => "required|numeric|gt:0",
    //         'sender_currency'   => "required|string|exists:currencies,code",
    //     ])->validate();

    //     $sender_wallet = UserWallet::auth()->whereHas('currency', function ($query) use ($validated) {
    //         $query->where('code', $validated['sender_currency'])->active();
    //     })->first();


    //     $gateway = PaymentGateway::sellCoin()->gateway($validated['gateway'])->first();
    //     if (!$gateway->isManual()) return back()->with(['error' => ['Gateway isn\'t available for this transaction']]);

    //     $gateway_currency = $gateway->currencies->first();

    //     $charges = $this->sellCoinCharges($validated['amount'], $gateway_currency, $sender_wallet); // Withdraw charge

    //     if ($sender_wallet->balance < $charges->total_payable) return back()->with(['error' => ['Your wallet balance is insufficient']]);

    //     // Store Temp Data
    //     try {
    //         $token = generate_unique_string("temporary_datas", "identifier", 16);
    //         TemporaryData::create([
    //             'type'          => PaymentGatewayConst::sell_coin_slug(),
    //             'identifier'    => $token,
    //             'data'          => [
    //                 'gateway_currency_id'   => $gateway_currency->id,
    //                 'wallet_id'             => $sender_wallet->id,
    //                 'charges'               => $charges,
    //             ],
    //         ]);
    //     } catch (Exception $e) {
    //         return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
    //     }

    //     return redirect()->route('user.sell.coin.instruction', $token);
    // }
    public function sellCoinCharges($amount, $receiverMethod, $senderMethod)
    {
        $baseCurRate = Currency::default()->first();
        $baseCurRate = $baseCurRate->rate;
        $senderCurRate =  $baseCurRate / $senderMethod->rate;
        $receiverCurRate =  $baseCurRate / $receiverMethod->rate;

        $excahngeRate = $receiverCurRate / $senderCurRate;



        $data['exchange_rate']          = $excahngeRate;
        $data['request_amount']         = $amount;

        // $data['sfixed_charge']           = $senderMethod->fixed_charge;
        // $data['spercent_charge']         = ($amount / 100) *  $senderMethod->percent_charge;
        // $data['stotal_charge']           = $data['sfixed_charge'] + $data['spercent_charge'];
        $data['sender_method_code']     = $senderMethod->currency_code;

        $data['rfixed_charge']           = $receiverMethod->fixed_charge;
        $data['rpercent_charge']         = ($amount / 100) *  $receiverMethod->percent_charge;
        $data['rtotal_charge']           = $data['rfixed_charge'] + $data['rpercent_charge'];
        $data['receiver_method_code']   = $receiverMethod->currency_code;
        $data['convirsion_amount']         = $amount - $data['rtotal_charge'];
        $data['will_get']               = $data['convirsion_amount'] * $excahngeRate;

        return (object) $data;
    }

    public function instruction($token)
    {
        $tempData = TemporaryData::where('identifier', $token)->first();
        $gateway = PaymentGateway::moneyOut()->manual()->where('alias', $tempData->data->receiving_gateway)->first();
        if (!$gateway) return redirect()->route('user.sell.coin.index')->with(['error' => ['Invalid Request!']]);
        $gateway_currency = PaymentGatewayCurrency::where('payment_gateway_id', $gateway->id)->first();
        if (!$gateway_currency) return redirect()->route('user.sell.coin.index')->with(['error' => ['Payment gateway currency is invalid!']]);
        $input_fields = $gateway->input_fields;
        if ($input_fields == null || !is_array($input_fields)) return redirect()->route('user.sell.coin.index')->with(['error' => ['This gateway is temporary pause or under maintenance!']]);
        $amount = $tempData->data->charge;
        $page_title = "Sell Coin Instructions";
        return view('user.sections.sell-coin.instructions', compact('page_title', 'gateway', 'token', 'amount'));
    }

    public function instructionSubmit(Request $request, $token)
    {


        $tempData = TemporaryData::where('identifier', $token)->first();

        $gateway_currency_id = $tempData->data->receiving_gateway ?? "";
        if (!$gateway_currency_id) return redirect()->route('user.sell.coin.index')->with(['error' => ['Invalid Request!']]);

        $gateway = PaymentGateway::moneyOut()->manual()->where('alias', $tempData->data->receiving_gateway)->first();
        if (!$gateway) return redirect()->route('user.sell.coin.index')->with(['error' => ['Invalid Request!']]);
        $gateway_currency = PaymentGatewayCurrency::where('payment_gateway_id', $gateway->id)->first();
        if (!$gateway_currency) return redirect()->route('user.sell.coin.index')->with(['error' => ['Payment gateway currency is invalid!']]);

        $dy_validation_rules = $this->generateValidationRules($gateway->input_fields);

        $validated = Validator::make($request->all(), $dy_validation_rules)->validate();
        $get_values = $this->placeValueWithFields($gateway->input_fields, $validated);
        $amount = $tempData->data->charge;

        // Make Transaction
        DB::beginTransaction();
        try {
            $id = DB::table("transactions")->insertGetId([
                'user_id'                       => auth()->user()->id,
                'user_wallet_id'                => null,
                'payment_gateway_currency_id'   => $gateway_currency->id,
                'type'                          => PaymentGatewayConst::TYPESELLCOIN,
                'trx_id'                        => generate_unique_string('transactions', 'trx_id', 16),
                'request_amount'                => $amount->request_amount,
                'payable'                       => 0,
                'available_balance'             => 0,
                'details'                       => json_encode(['sender_input' => $get_values, 'total_info' => $tempData->data]),
                'status'                        => PaymentGatewayConst::STATUSPENDING,
                'attribute'                     => PaymentGatewayConst::SEND,
                'created_at'                    => now(),
            ]);

            // $this->createTransactionChildRecords($id, $amount);

            $transaction_status = Transaction::where('type', 'SELL-COIN')->first();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('user.sell.coin.instruction', $token)->with(['error' => [__('Something went wrong! Please try again.')]]);
        }
        $tempData->delete();

        return redirect()->route('user.sell.coin.index')->with(['success' => ['Transaction success! Please wait for confirmation']]);
    }
}
