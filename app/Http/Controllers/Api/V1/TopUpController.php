<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use App\Models\UserWallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TemporaryData;
use App\Models\Admin\Currency;
use Illuminate\Support\Carbon;
use App\Models\Admin\TopUpGame;
use App\Models\UserNotification;
use App\Http\Helpers\Api\Helpers;
use App\Models\TransactionCharge;
use Illuminate\Support\Facades\DB;
use App\Constants\NotificationConst;
use App\Http\Controllers\Controller;
use App\Models\Admin\PaymentGateway;
use Illuminate\Support\Facades\Auth;
use App\Traits\PaymentGateway\Manual;
use App\Traits\PaymentGateway\Stripe;
use App\Constants\PaymentGatewayConst;
use App\Models\Admin\TransactionSetting;
use App\Traits\ControlDynamicInputFields;
use Illuminate\Support\Facades\Validator;
use App\Http\Helpers\Api\PaymentGatewayApi;
use App\Models\Admin\PaymentGatewayCurrency;
use App\Http\Helpers\Api\Helpers as ApiResponse;

class TopUpController extends Controller
{
    use ControlDynamicInputFields, Stripe, Manual;
    function topUpList()
    {
        $lang = selectedLang();

        $top_up_games = TopUpGame::where('status', 1)->latest()->limit(12)->get();

        $header_slider_slug = \Illuminate\Support\Str::slug(\App\Constants\SiteSectionConst::HEADER_SLIDERS_SECTION);
        $header_slider = \App\Models\Admin\SiteSections::getData($header_slider_slug)->first();

        if (isset($header_slider->value->items)) {
            $header_slider = $header_slider->value->items;
            $header_item = [];
            foreach ($header_slider ?? [] as $value) {
                $title = isset($value->language->$lang) ? $value->language->$lang->item_title : $value->language->$lang->item_title;
                $description = isset($value->language->$lang) ? $value->language->$lang->item_description : $value->language->$lang->item_description;
                $header_item[] = [
                    'id'    => $value->id,
                    'title' => $title,
                    'description'   => $description,
                    'image' => $value->image,
                ];
            }
        } else {
            $header_item = null;
        }
        $header_data = [
            'base_url' => url('/'),
            'image_path' => get_files_public_path('site-section'),
            'default_image' => get_files_public_path('default'),
            'data' => $header_item,
        ];

        $data = [
            'base_ur' => url('/'),
            'image_path' => 'public/backend/images/top-up-game',
            'top_up_games' => $top_up_games,
            'heder_slider' => $header_data,
        ];
        $message =  ['success' => [__('Top Up List!')]];
        return ApiResponse::success($message, $data);
    }

    function details($id)
    {
        $topup = TopUpGame::findOrFail($id);
        $message =  ['success' => [__('Top Up Details!')]];
        $data = [
            'base_ur' => url('/'),
            'image_path' => 'public/backend/images/top-up-game',
            'topup' => $topup,
        ];
        return ApiResponse::success($message, $data);
    }
    function purchaseDetails($id)
    {
        $topup = TopUpGame::findOrFail($id);

        $gateways = PaymentGateway::where('status', 1)->where('slug', PaymentGatewayConst::add_money_slug())->get()->map(function ($gateway) {
            $currencies = PaymentGatewayCurrency::where('payment_gateway_id', $gateway->id)->get()->map(function ($data) {
                return [
                    'id'                 => $data->id,
                    'payment_gateway_id' => $data->payment_gateway_id,
                    'type'               => $data->gateway->type,
                    'name'               => $data->name,
                    'alias'              => $data->alias,
                    'currency_code'      => $data->currency_code,
                    'currency_symbol'    => $data->currency_symbol,
                    'image'              => $data->image,
                    'min_limit'          => getAmount($data->min_limit, 2),
                    'max_limit'          => getAmount($data->max_limit, 2),
                    'percent_charge'     => getAmount($data->percent_charge, 2),
                    'fixed_charge'       => getAmount($data->fixed_charge, 2),
                    'rate'               => getAmount($data->rate, 2),
                    'created_at'         => $data->created_at,
                    'updated_at'         => $data->updated_at,
                ];
            });

            return [
                'id'                   => $gateway->id,
                'image'                => $gateway->image,
                'slug'                 => $gateway->slug,
                'code'                 => $gateway->code,
                'type'                 => $gateway->type,
                'alias'                => $gateway->alias,
                'supported_currencies' => $gateway->supported_currencies,
                'status'               => $gateway->status,
                'currencies'           => $currencies
            ];
        });


        $user_wallet = [];


        if (true == Auth::guard(get_auth_guard())->check()) {
            $user_id = Auth::guard(get_auth_guard())->user()->id;
            if (!empty($user_id)) {
                $user_wallet = UserWallet::where('user_id', $user_id)->first();
            }
        }

        $data = [
            'get_default_currency_symbol' => get_default_currency_symbol(),
            'default_currency_code' => get_default_currency_code(),
            'input_fields' => $topup->input_fields,
            'gateways' => $gateways,
            'user_wallet' => $user_wallet,
            'wallet_balance' => 'wallet_balance'

        ];

        $message = ['success' => [__('Purchase Details!')]];
        return ApiResponse::success($message, $data);
    }

    function purchaseSubmit(Request $request)
    {

        $top_up_id = $request->top_up_id;
        $top_up = TopUpGame::findOrFail($top_up_id)->input_fields->input_fields_player_id ?? [];
        $validation_rules = $this->generateValidationRulesForTopup($top_up);
        $validated = Validator::make($request->all(), $validation_rules)->validate();
        $get_values = $this->placeValueWithFieldsTopUp($top_up, $validated);

        $recharge = $request->recharge;
        $recharge = explode('|', $recharge);

        $top_up_game_charge = TransactionSetting::first();
        //chareg
        $top_up_game_fix_charge = $top_up_game_charge->fixed_charge;
        $top_up_game_percent_charge = ($top_up_game_charge->percent_charge * $recharge[1]) / 100;
        $top_up_game_total_charge = $top_up_game_fix_charge + $top_up_game_percent_charge;
        //payable
        $payable = $top_up_game_total_charge + $recharge[1];

        $alias = $request->currency;
        if ($alias == "wallet_balance" && true == (Auth::guard(get_auth_guard())->check())) {

            $transaction_charge = TransactionSetting::first();
            $trx_id = generate_unique_string("transactions", "trx_id", 16);
            // $payable = $tempData->data->payable;

            $user_id = Auth::guard(get_auth_guard())->user()->id;
            $userWallet = UserWallet::where('user_id', $user_id)->first();
            $user_balance = $userWallet->balance;
            if ($user_balance < $payable) {
                return ApiResponse::error(['error' => ['Insufficient Balance']]);
            }

            try {
                $data =
                    [
                        'tempData' => [
                            'player_id' => $get_values,
                            'recharge_coin' => $recharge,
                            'total_charge' => $top_up_game_total_charge,
                            'payable' => $payable,
                            'coin_type' => $request->coin_type,
                            'currency_code' => get_default_currency_code(),
                            'currency' => $alias
                        ]
                    ];
                $updated_balance = $userWallet->balance -=  $payable;
                $userWallet->save();

                $id = DB::table("transactions")->insertGetId([
                    'user_id'                       => $user_id,
                    'user_wallet_id'                => $userWallet->id,
                    'payment_gateway_currency_id'   => null,
                    'type'                          =>  "TOP-UP",
                    'trx_id'                        => $trx_id,
                    'request_amount'                => $recharge[1],
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
                    'user_id'  =>  $user_id,
                    'message'   => $notification_content,
                ]);
            } catch (\Exception $e) {
                $message = ['error' => [__('Something went wrong! Please try again.')]];
                return ApiResponse::error($message);
            }

            $message = ['success' => [__('Top up successfully')]];
            return ApiResponse::success($message, ['data']);
        }

        $amount = $recharge[1];
        $payment_gateways_currencies = PaymentGatewayCurrency::where('alias', $alias)->whereHas('gateway', function ($gateway) {
            // $gateway->where('slug', PaymentGatewayConst::add_money_slug());
            $gateway->where('status', 1);
        })->first();
        if (!$payment_gateways_currencies) {
            $error = ['error' => ['Gateway Information is not available. Please provide payment gateway currency alias']];
            return ApiResponse::error($error);
        }
        $defualt_currency = Currency::default();

        if (Auth::guard(get_auth_guard())->check()) {
            $user_id = Auth::guard(get_auth_guard())->user()->id;
            $user_wallet = UserWallet::where([['user_id', $user_id], ['currency_id', $defualt_currency->id]])->first();
            if (!$user_wallet) {
                $error = ['error' => [__('User wallet not found!')]];
                return ApiResponse::error($error);
            }
        }

        if ($amount < ($payment_gateways_currencies->min_limit / $payment_gateways_currencies->rate) || $amount > ($payment_gateways_currencies->max_limit / $payment_gateways_currencies->rate)) {
            $error = ['error' => [__('Please follow the transaction limit')]];
            return ApiResponse::error($error);
        }
        $request->merge([
            'amount' => $amount
        ]);

        $data = [
            'tempData' => [
                'player_id' => $get_values,
                'recharge_coin' => $recharge,
                'total_charge' => $top_up_game_total_charge,
                'payable' => $payable,
                'coin_type' => $request->coin_type,
                'currency' => get_default_currency_code(),
                'type' => 'ORDER',
            ]
        ];
        $token = generate_unique_string("temporary_datas", "identifier", 16);

        $billingTempData = TemporaryData::create([
            'type'          => 'ORDER',
            'identifier'    => $token,
            'data'          => $data
        ]);

        try {

            $instance = PaymentGatewayApi::init($request->all())->type('ORDER')->billingTempData($billingTempData)->gateway()->api()->get();
            $trx = $instance['response']['id'] ?? $instance['response']['trx'] ?? $instance['response']['reference_id'] ?? $instance['response']['order_id'] ?? $instance['response']['tokenValue'] ?? $instance['response']['url'] ?? $instance['response']['temp_identifier'] ?? '';
            $temData = TemporaryData::where('identifier', $trx)->first();
            if (!$temData) {
                $error = ['error' => ["Invalid Request"]];
                return ApiResponse::error($error);
            }
            $payment_gateway_currency = PaymentGatewayCurrency::where('id', $temData->data->currency)->first();
            $payment_gateway = PaymentGateway::where('id', $temData->data->gateway)->first();
            if ($payment_gateway->type == "AUTOMATIC") {
                if ($temData->type == PaymentGatewayConst::STRIPE) {
                    $card = [
                        [
                            'field_name' => "name",
                            'label_name' => "Name",
                        ],
                        [
                            'field_name' => "cardNumber",
                            'label_name' => "Card Number",
                        ],
                        [
                            'field_name' => "cardExpiry",
                            'label_name' => "Expire Date",
                        ],
                        [
                            'field_name' => "cardCVC",
                            'label_name' => "CVC Code",
                        ],
                    ];
                    $card2 = (array) $card;
                    $payment_informations = [
                        'trx' =>  $temData->identifier,
                        'gateway_currency_name' =>  $payment_gateway_currency->name,
                        'request_amount' => getAmount($temData->data->amount->requested_amount, 2) . ' ' . $temData->data->amount->default_currency,
                        'exchange_rate' => "1" . ' ' . $temData->data->amount->default_currency . ' = ' . getAmount($temData->data->amount->sender_cur_rate) . ' ' . $temData->data->amount->sender_cur_code,
                        'total_charge' => getAmount($temData->data->amount->total_charge, 2) . ' ' . $temData->data->amount->sender_cur_code,
                        // 'will_get' => getAmount($temData->data->amount->will_get, 2) . ' ' . $temData->data->amount->default_currency,
                        'payable_amount' =>  getAmount($temData->data->amount->total_amount, 2) . ' ' . $temData->data->amount->sender_cur_code,
                    ];

                    $data = [
                        'gateway_type' => $payment_gateway->type,
                        'gateway_currency_name' => $payment_gateway_currency->name,
                        'alias' => $payment_gateway_currency->alias,
                        'identify' => $temData->type,
                        'payment_informations' => $payment_informations,
                        'url' => @$temData->data->response->link . "?prefilled_email=" . @$user->email,
                        'method' => "get",
                    ];
                    $message =  ['success' => [__('Top Up Inserted Successfully')]];
                    return ApiResponse::success($message, $data);
                } else if ($temData->type == PaymentGatewayConst::PAYPAL) {
                    $payment_informations = [
                        'trx' =>  $temData->identifier,
                        'gateway_currency_name' =>  $payment_gateway_currency->name,
                        'request_amount' => getAmount($temData->data->amount->requested_amount, 2) . ' ' . $temData->data->amount->default_currency,
                        'exchange_rate' => "1" . ' ' . $temData->data->amount->default_currency . ' = ' . getAmount($temData->data->amount->sender_cur_rate) . ' ' . $temData->data->amount->sender_cur_code,
                        'total_charge' => getAmount($temData->data->amount->total_charge, 2) . ' ' . $temData->data->amount->sender_cur_code,
                        // 'will_get' => getAmount($temData->data->amount->will_get, 2) . ' ' . $temData->data->amount->default_currency,
                        'payable_amount' =>  getAmount($temData->data->amount->total_amount, 2) . ' ' . $temData->data->amount->sender_cur_code,
                    ];
                    $data = [
                        'gategay_type' => $payment_gateway->type,
                        'gateway_currency_name' => $payment_gateway_currency->name,
                        'alias' => $payment_gateway_currency->alias,
                        'identify' => $temData->type,
                        'payment_informations' => $payment_informations,
                        'url' => @$temData->data->response->links,
                        'method' => "get",
                    ];
                    $message =  ['success' => [__('Top Up Inserted Successfully')]];
                    return ApiResponse::success($message, $data);
                } elseif ($temData->type == PaymentGatewayConst::SSLCOMMERZ) {
                    $payment_informations = [
                        'trx' =>  $temData->identifier,
                        'gateway_currency_name' =>  $payment_gateway_currency->name,
                        'request_amount' => getAmount($temData->data->amount->requested_amount, 4) . ' ' . $temData->data->amount->default_currency,
                        'exchange_rate' => "1" . ' ' . $temData->data->amount->default_currency . ' = ' . getAmount($temData->data->amount->sender_cur_rate, 4) . ' ' . $temData->data->amount->sender_cur_code,
                        'total_charge' => getAmount($temData->data->amount->total_charge, 4) . ' ' . $temData->data->amount->sender_cur_code,
                        // 'will_get' => getAmount($temData->data->amount->will_get, 4) . ' ' . $temData->data->amount->default_currency,
                        'payable_amount' =>  getAmount($temData->data->amount->total_amount, 4) . ' ' . $temData->data->amount->sender_cur_code,
                    ];
                    $data = [
                        'gateway_type' => $payment_gateway->type,
                        'gateway_currency_name' => $payment_gateway_currency->name,
                        'alias' => $payment_gateway_currency->alias,
                        'identify' => $temData->type,
                        'payment_informations' => $payment_informations,
                        'url' => @$temData->data->response->link . "?prefilled_email=" . @$user->email,
                        'method' => "get",
                    ];
                    $message =  ['success' => [__('Top Up Inserted Successfully')]];
                    return Helpers::success($message, $data);
                } else if ($temData->type == PaymentGatewayConst::FLUTTER_WAVE) {
                    $payment_informations = [
                        'trx' =>  $temData->identifier,
                        'gateway_currency_name' =>  $payment_gateway_currency->name,
                        'request_amount' => getAmount($temData->data->amount->requested_amount, 4) . ' ' . $temData->data->amount->default_currency,
                        'exchange_rate' => "1" . ' ' . $temData->data->amount->default_currency . ' = ' . getAmount($temData->data->amount->sender_cur_rate, 4) . ' ' . $temData->data->amount->sender_cur_code,
                        'total_charge' => getAmount($temData->data->amount->total_charge, 4) . ' ' . $temData->data->amount->sender_cur_code,
                        // 'will_get' => getAmount($temData->data->amount->will_get, 4) . ' ' . $temData->data->amount->default_currency,
                        'payable_amount' =>  getAmount($temData->data->amount->total_amount, 4) . ' ' . $temData->data->amount->sender_cur_code,
                    ];
                    $data = [
                        'gateway_type' => $payment_gateway->type,
                        'gateway_currency_name' => $payment_gateway_currency->name,
                        'alias' => $payment_gateway_currency->alias,
                        'identify' => $temData->type,
                        'payment_informations' => $payment_informations,
                        'url' => @$temData->data->response->link,
                        'method' => "get",
                    ];
                    $message =  ['success' => [__('Top Up Inserted Successfully')]];
                    return Helpers::success($message, $data);
                } else if ($temData->type == PaymentGatewayConst::RAZORPAY) {
                    $payment_informations = [
                        'trx' =>  $temData->identifier,
                        'gateway_currency_name' =>  $payment_gateway_currency->name,
                        'request_amount' => getAmount($temData->data->amount->requested_amount, 4) . ' ' . $temData->data->amount->default_currency,
                        'exchange_rate' => "1" . ' ' . $temData->data->amount->default_currency . ' = ' . getAmount($temData->data->amount->sender_cur_rate, 4) . ' ' . $temData->data->amount->sender_cur_code,
                        'total_charge' => getAmount($temData->data->amount->total_charge, 4) . ' ' . $temData->data->amount->sender_cur_code,
                        // 'will_get' => getAmount($temData->data->amount->will_get, 4) . ' ' . $temData->data->amount->default_currency,
                        'payable_amount' =>  getAmount($temData->data->amount->total_amount, 4) . ' ' . $temData->data->amount->sender_cur_code,
                    ];
                    $data = [
                        'gateway_type' => $payment_gateway->type,
                        'gateway_currency_name' => $payment_gateway_currency->name,
                        'alias' => $payment_gateway_currency->alias,
                        'identify' => $temData->type,
                        'payment_informations' => $payment_informations,
                        'url' =>  $instance['response']['redirect_url'],
                        'method' => "get",
                    ];
                    $message =  ['success' => [__('Top Up Inserted Successfully')]];
                    return Helpers::success($message, $data);
                } else if ($temData->type == PaymentGatewayConst::UDDOKTAPAY) {
                    $payment_informations = [
                        'trx' =>  $temData->identifier,
                        'gateway_currency_name' =>  $payment_gateway_currency->name,
                        'request_amount' => getAmount($temData->data->amount->requested_amount, 4) . ' ' . $temData->data->amount->default_currency,
                        'exchange_rate' => "1" . ' ' . $temData->data->amount->default_currency . ' = ' . getAmount($temData->data->amount->sender_cur_rate, 4) . ' ' . $temData->data->amount->sender_cur_code,
                        'total_charge' => getAmount($temData->data->amount->total_charge, 4) . ' ' . $temData->data->amount->sender_cur_code,
                        // 'will_get' => getAmount($temData->data->amount->will_get, 4) . ' ' . $temData->data->amount->default_currency,
                        'payable_amount' =>  getAmount($temData->data->amount->total_amount, 4) . ' ' . $temData->data->amount->sender_cur_code,
                    ];
                    $data = [
                        'gateway_type' => $payment_gateway->type,
                        'gateway_currency_name' => $payment_gateway_currency->name,
                        'alias' => $payment_gateway_currency->alias,
                        'identify' => $temData->type,
                        'payment_informations' => $payment_informations,
                        'url' => @$instance['response']['payment_url'],
                        'method' => "get",
                    ];
                    $message =  ['success' => [__('Top Up Inserted Successfully')]];
                    return Helpers::success($message, $data);
                }
            } elseif ($payment_gateway->type == "MANUAL") {

                $payment_informations = [
                    'trx' =>  $temData->identifier,
                    'gateway_currency_name' =>  $payment_gateway_currency->name,
                    'request_amount' => getAmount($temData->data->amount->requested_amount, 2) . ' ' . $temData->data->amount->default_currency,
                    'recharge_coin' => getAmount($temData->data->recharge_coin[0], 2) . ' ' . $temData->data->request_data->coin_type,
                    'exchange_rate' => "1" . ' ' . $temData->data->amount->default_currency . ' = ' . getAmount($temData->data->amount->sender_cur_rate) . ' ' . $temData->data->amount->sender_cur_code,
                    'total_charge' => getAmount($temData->data->amount->total_charge, 2) . ' ' . $temData->data->amount->sender_cur_code,
                    // 'will_get' => getAmount($temData->data->amount->will_get, 2) . ' ' . $temData->data->amount->default_currency,
                    'payable_amount' =>  getAmount($temData->data->amount->total_amount, 2) . ' ' . $temData->data->amount->sender_cur_code,
                    'player_data' => $temData->data->billingTempData->data->tempData,
                ];
                $data = [
                    'gateway_type' => $payment_gateway->type,
                    'gateway_currency_name' => $payment_gateway_currency->name,
                    'alias' => $payment_gateway_currency->alias,
                    'identify' => $temData->type,
                    'details' => $payment_gateway->desc ?? null,
                    'input_fields' => $payment_gateway->input_fields ?? null,
                    'payment_informations' => $payment_informations,
                    'url' => route('api.v1.topup.manual.payment.confirmed'),
                    'method' => "post",
                ];
                $message =  ['success' => [__('Top Up Inserted Successfully')]];
                return ApiResponse::success($message, $data);
            } else {
                $error = ['error' => [__("Something is wrong")]];
                return ApiResponse::error($error);
            }
        } catch (\Exception $e) {
            $error = ['error' => [$e->getMessage()]];
            return ApiResponse::error($error);
        }
        // return $instance;
    }

    public function redirectBtnPay(Request $request, $gateway)
    {
        return PaymentGatewayApi::init([])->type('ADD-MONEY')->handleBtnPay($gateway, $request->all());
        try {
        } catch (Exception $e) {
            $message = ['error' => [$e->getMessage()]];
            return Helpers::error($message);
        }
    }
    public function successGlobal(Request $request, $gateway)
    {
        try {
            $token = PaymentGatewayApi::getToken($request->all(), $gateway);
            $temp_data = TemporaryData::where("identifier", $token)->first();

            if (!$temp_data) {
                if (Transaction::where('callback_ref', $token)->exists()) {
                    $message = ['error' => [__('Transaction request sended successfully!')]];
                    return Helpers::error($message);
                } else {
                    $message = ['error' => [__('Transaction failed. Record didn\'t saved properly. Please try again')]];
                    return Helpers::error($message);
                }
            }

            $update_temp_data = json_decode(json_encode($temp_data->data), true);
            $update_temp_data['callback_data']  = $request->all();
            $temp_data->update([
                'data'  => $update_temp_data,
            ]);
            $temp_data = $temp_data->toArray();
            $instance = PaymentGatewayApi::init($temp_data)->type(PaymentGatewayConst::TYPEADDMONEY)->responseReceive($temp_data['type']);

            // return $instance;
        } catch (Exception $e) {
            $message = ['error' => [$e->getMessage()]];
            return Helpers::error($message);
        }
        $message = ['success' => [__('Top Up Successfully')]];
        return Helpers::onlysuccess($message);
    }
    public function cancelGlobal(Request $request, $gateway)
    {
        $token = PaymentGatewayApi::getToken($request->all(), $gateway);
        $temp_data = TemporaryData::where("identifier", $token)->first();
        try {
            if ($temp_data != null) {
                $temp_data->delete();
            }
        } catch (Exception $e) {
            // Handel error
        }
        $message = ['success' => [__('Top Up Canceled Successfully')]];
        return Helpers::error($message);
    }

    public function postSuccess(Request $request, $gateway)
    {
        try {
            $token = PaymentGatewayApi::getToken($request->all(), $gateway);
            $temp_data = TemporaryData::where("identifier", $token)->first();
            if ($temp_data && $temp_data->data->creator_guard != 'api') {
                Auth::guard($temp_data->data->creator_guard)->loginUsingId($temp_data->data->creator_id);
            }
        } catch (Exception $e) {
            $message = ['error' => [$e->getMessage()]];
            return Helpers::error($message);
        }

        return $this->successGlobal($request, $gateway);
    }

    public function postCancel(Request $request, $gateway)
    {
        try {
            $token = PaymentGatewayApi::getToken($request->all(), $gateway);
            $temp_data = TemporaryData::where("identifier", $token)->first();
            if ($temp_data && $temp_data->data->creator_guard != 'api') {
                Auth::guard($temp_data->data->creator_guard)->loginUsingId($temp_data->data->creator_id);
            }
        } catch (Exception $e) {
            $message = ['error' => [$e->getMessage()]];
            return Helpers::error($message);
        }

        return $this->cancelGlobal($request, $gateway);
    }
}
