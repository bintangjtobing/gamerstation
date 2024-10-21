<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Constants\PaymentGatewayConst;
use App\Http\Resources\AddMoneyLogs;
use App\Http\Helpers\Api\Helpers as ApiResponse;
use App\Http\Resources\TopUpLogs;

class TransactionController extends Controller
{
    public function addmoney(Request $request)
    {
        $addMoney     = Transaction::auth()->where('type', PaymentGatewayConst::TYPEADDMONEY)->orderByDesc("id")->get();

        $transactions = [
            'addMoney'          => AddMoneyLogs::collection($addMoney),
        ];
        $transactions = (object)$transactions;


        $success = ['success' => [__('Add Money transaction logs')]];
        return ApiResponse::success($success, $transactions);
    }
    public function purchase(Request $request)
    {
        $topup     = Transaction::auth()->where('type', PaymentGatewayConst::TYPETOPUP)->orderByDesc("id")->get();

        $transactions = [
            'topup'          => TopUpLogs::collection($topup),
        ];
        $transactions = (object)$transactions;


        $success = ['success' => [__('Top Up transaction logs')]];
        return ApiResponse::success($success, $transactions);
    }
}
