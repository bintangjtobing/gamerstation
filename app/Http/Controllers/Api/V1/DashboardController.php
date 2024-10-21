<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\BuyCoin;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Constants\PaymentGatewayConst;
use App\Http\Helpers\Api\Helpers as ApiResponse;
use App\Models\Admin\Currency;

class DashboardController extends Controller
{
    public function dashboard()
    {

        // $transaction = Transaction::auth()->select(['available_balance'])->first();
        // $balance = $transaction->available_balance;


        $total_topup = Transaction::Auth()
            ->where('type', PaymentGatewayConst::TYPETOPUP)
            ->where('status', 6)
            ->sum('request_amount');

        $total_transaction = Transaction::where('user_id', Auth::user()->id)
            ->where('status', 6)
            ->sum('request_amount');

        // $transaction = Transaction::where('user_id', Auth::user()->id)->latest()->get()->map(function ($item) {
        //     return [
        //         'id' => $item->id,
        //         'trx_id' => $item->trx_id,
        //         'balance' => getAmount($item->available_balance, 2) . ' ' . get_default_currency_code(),
        //         'total_transaction' => $item->request_amount->sum(),
        //         'date_time' => $item->created_at,


        //     ];
        // });


        $default_currency_flag = Currency::where('status', 1)->select(['flag'])->first();


        $data = [
            'base_curr'    => get_default_currency_code(),
            'base_url' => url('/'),
            'default_image'    => "public/backend/images/default/default.webp",
            "image_path"  =>  "public/backend/images/payment-gateways",
            'default_currency_flag' => "public/backend/images/currency-flag/" . $default_currency_flag->flag,
            'balance' => authWalletBalance(),
            'total_transaction' => $total_transaction,
            'total_topup' => $total_topup
        ];
        $message =  ['success' => [__('Dashboard Information!')]];
        return ApiResponse::success($message, $data);
    }
}
