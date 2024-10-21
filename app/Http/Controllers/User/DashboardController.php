<?php

namespace App\Http\Controllers\User;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Constants\PaymentGatewayConst;

class DashboardController extends Controller
{
    public function index()
    {
        $page_title = "Dashboard";
        $transactions = Transaction::auth()->whereIn("type", ['ADD-MONEY', 'TOP-UP'])->orderByDesc("id")->take(3)->get();

        $start = strtotime(date('Y-m-01'));
        $end = strtotime(date('Y-m-31'));

        $month_day = [];

        //add money chart
        $add_money_panding_data = [];
        $add_money_completed_data = [];
        $add_money_canceled_data = [];
        //top up chart
        $top_up_review_data = [];
        $top_up_panding_data = [];
        $top_up_completed_data = [];
        $top_up_settled_data = [];

        while ($start <= $end) {
            $start_date = date('Y-m-d', $start);

            //Add Money
            $panding = Transaction::Auth()
                ->where('type', PaymentGatewayConst::TYPEADDMONEY)
                ->where('status', 2)
                ->whereDate('created_at', $start_date)
                ->count();
            $complated = Transaction::Auth()
                ->where('type', PaymentGatewayConst::TYPEADDMONEY)
                ->where('status', 6)
                ->whereDate('created_at', $start_date)
                ->count();
            $canceled = Transaction::Auth()
                ->where('type', PaymentGatewayConst::TYPEADDMONEY)
                ->where('status', 7)
                ->whereDate('created_at', $start_date)
                ->count();
            //add money
            $add_money_panding_data[] = $panding;
            $add_money_completed_data[] = $complated;
            $add_money_canceled_data[] = $canceled;
            //Top Up
            $review = Transaction::Auth()
                ->where('type', PaymentGatewayConst::TYPETOPUP)
                ->where('status', 1)
                ->whereDate('created_at', $start_date)
                ->count();
            $panding = Transaction::Auth()
                ->where('type', PaymentGatewayConst::TYPETOPUP)
                ->where('status', 2)
                ->whereDate('created_at', $start_date)
                ->count();
            $complated = Transaction::Auth()
                ->where('type', PaymentGatewayConst::TYPETOPUP)
                ->where('status', 6)
                ->whereDate('created_at', $start_date)
                ->count();
            $settled = Transaction::Auth()
                ->where('type', PaymentGatewayConst::TYPETOPUP)
                ->where('status', 5)
                ->whereDate('created_at', $start_date)
                ->count();

            //top up
            $top_up_review_data[] = $review;
            $top_up_panding_data[] = $panding;
            $top_up_completed_data[] = $complated;
            $top_up_settled_data[] = $settled;


            $month_day[] = date('Y-m-d', $start);
            $start = strtotime('+1 day', $start);
        }

        // Chart one
        $add_money_chart = [
            'add_money_pending'  => $add_money_panding_data,
            'add_money_completed'  => $add_money_completed_data,
            'add_money_canceled' => $add_money_canceled_data,
        ];
        // Chart two
        $top_up_chart = [
            'top_up_review'  => $top_up_review_data,
            'top_up_pending'  => $top_up_panding_data,
            'top_up_completed'  => $top_up_completed_data,
            'top_up_settled' => $top_up_settled_data,
        ];
        $data = [
            'add_money_chart' => $add_money_chart,
            'month_day' => $month_day,
            'top_up_chart' => $top_up_chart
        ];


        return view('user.dashboard', compact(
            "page_title",
            'transactions',
            'data'
        ));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('index');
    }
}
