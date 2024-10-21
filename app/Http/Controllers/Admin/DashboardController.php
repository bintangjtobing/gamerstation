<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use App\Models\BuyCoin;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Models\UserSupportTicket;
use App\Constants\NotificationConst;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Constants\PaymentGatewayConst;
use App\Models\Admin\AdminNotification;
use App\Models\Admin\Currency;
use App\Providers\Admin\BasicSettingsProvider;
use Pusher\PushNotifications\PushNotifications;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = "Dashboard";
        //Addmoney
        $total_addmoney = Transaction::where('type', 'ADD-MONEY')->whereIn('status', [1, 2, 3, 6])->sum('request_amount');
        $success_addmoney = Transaction::where('type', 'ADD-MONEY')->where('status', 1)->sum('request_amount');
        $panding_addmoney = Transaction::where('type', 'ADD-MONEY')->where('status', 2)->sum('request_amount');
        if ($panding_addmoney == 0) {
            $percent_addmoney = 0;
        } else {
            $percent_addmoney = ($success_addmoney / ($success_addmoney + $panding_addmoney)) * 100;
        }

        $total_topup_count = Transaction::where('type', 'TOP-UP')->whereIn('status', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10])->count();

        $total_review_topup_amount = Transaction::where([['type', 'TOP-UP'], ['status', 1]])->sum('request_amount');
        $total_review_topup_count = Transaction::where('type', 'TOP-UP')->where('status', 1)->count();
        if ($total_review_topup_count == 0) {
            $percent_review_topup = 0;
        } else {
            $percent_review_topup = ($total_review_topup_count * 100) / $total_topup_count;
        }
        //Total Panding Topup
        $total_pending_topup_amount = Transaction::where([['type', 'TOP-UP'], ['status', 2]])->sum('request_amount');

        $total_pending_topup_count = Transaction::where([['type', 'TOP-UP'], ['status', 2]])->count();
        if ($total_pending_topup_count == 0) {
            $percent_pending_topup = 0;
        } else {
            $percent_pending_topup = ($total_pending_topup_count * 100) / $total_topup_count;
        }
        //Total Settled Topup
        $total_settled_topup_amount = Transaction::where([['type', 'TOP-UP'], ['status', 5]])->sum('request_amount');

        $total_settled_topup_count = Transaction::where([['type', 'TOP-UP'], ['status', 5]])->count();
        if ($total_settled_topup_count == 0) {
            $percent_settled_topup = 0;
        } else {
            $percent_settled_topup = ($total_settled_topup_count * 100) / $total_topup_count;
        }
        //Total Complete Topup
        $total_complete_topup_amount = Transaction::where([['type', 'TOP-UP'], ['status', 6]])->sum('request_amount');
        $total_complete_topup_count = Transaction::where('type', 'TOP-UP')->where('status', 6)->count();
        if ($total_complete_topup_count == 0) {
            $percent_complete_topup = 0;
        } else {
            $percent_complete_topup = ($total_complete_topup_count * 100) / $total_topup_count;
        }

        //Users
        $total_users = User::toBase()->count();

        $active_users =  User::active()->count();
        $unverified_users = User::EmailUnverified()->count();

        if ($unverified_users == 0 && $active_users != 0) {
            $user_perchant = 100;
        } elseif ($unverified_users == 0 && $active_users == 0) {
            $user_perchant = 0;
        } else {
            $user_perchant = ($active_users / ($active_users + $unverified_users)) * 100;
        }

        //Support Tikets
        $total_tickets = UserSupportTicket::toBase()->count();
        $active_tickets =  UserSupportTicket::active()->count();
        $pending_tickets = UserSupportTicket::Pending()->count();

        if ($pending_tickets == 0 && $active_tickets != 0) {
            $ticket_perchant = 100;
        } elseif ($pending_tickets == 0 && $active_tickets == 0) {
            $ticket_perchant = 0;
        } else {
            $ticket_perchant = ($active_tickets / ($active_tickets + $pending_tickets)) * 100;
        }

        // Chart four | User analysis
        $total_user = User::toBase()->count();
        $unverified_user = User::toBase()->where('sms_verified', 0)->count();
        $active_user = User::toBase()->where('status', 1)->count();
        $banned_user = User::toBase()->where('status', 0)->count();
        $chart_four = [$active_user, $banned_user, $unverified_user, $total_user];

        //charts
        // Monthly Add Money
        $start = strtotime(date('Y-m-01'));
        $end = strtotime(date('Y-m-31'));

        // Add Money
        $pending_data  = [];
        $success_data  = [];
        $canceled_data = [];
        $hold_data     = [];


        // Top Up
        $top_up_review_data  = [];
        $top_up_pending_data  = [];
        $top_up_completed_data  = [];
        $top_up_settled_data = [];

        $month_day  = [];

        while ($start <= $end) {
            $start_date = date('Y-m-d', $start);
            // Monthley add money
            $pending = Transaction::where('type', PaymentGatewayConst::TYPEADDMONEY)
                ->whereDate('created_at', $start_date)
                ->where('status', 2)
                ->orWhere('type', PaymentGatewayConst::TYPEADDBALANCE)
                ->count();
            $success = Transaction::where('type', PaymentGatewayConst::TYPEADDMONEY)
                ->whereDate('created_at', $start_date)
                ->where('status', 6)
                ->orWhere('type', PaymentGatewayConst::TYPEADDBALANCE)
                ->count();
            $canceled = Transaction::where('type', PaymentGatewayConst::TYPEADDMONEY)
                ->whereDate('created_at', $start_date)
                ->where('status', 7)
                ->orWhere('type', PaymentGatewayConst::TYPEADDBALANCE)
                ->count();
            $hold = Transaction::where('type', PaymentGatewayConst::TYPEADDMONEY)
                ->whereDate('created_at', $start_date)
                ->where('status', 4)
                ->orWhere('type', PaymentGatewayConst::TYPEADDBALANCE)
                ->count();
            $pending_data[]  = $pending;
            $success_data[]  = $success;
            $canceled_data[] = $canceled;
            $hold_data[]     = $hold;

            // Monthley top up
            $top_up_review = Transaction::where('type', PaymentGatewayConst::TYPETOPUP)
                ->whereDate('created_at', $start_date)
                ->where('status', 1)
                ->count();
            $top_up_pending = Transaction::where('type', PaymentGatewayConst::TYPETOPUP)
                ->whereDate('created_at', $start_date)
                ->where('status', 2)
                ->count();
            $top_up_completed = Transaction::where('type', PaymentGatewayConst::TYPETOPUP)
                ->whereDate('created_at', $start_date)
                ->where('status', 6)
                ->count();
            $top_up_canceled = Transaction::where('type', PaymentGatewayConst::TYPETOPUP)
                ->whereDate('created_at', $start_date)
                ->where('status', 6)
                ->count();
            $top_up_settled = Transaction::where('type', PaymentGatewayConst::TYPETOPUP)
                ->whereDate('created_at', $start_date)
                ->where('status', 5)
                ->count();
            $top_up_review_data[]  = $top_up_review;
            $top_up_pending_data[]  = $top_up_pending;
            $top_up_completed_data[]  = $top_up_completed;
            $top_up_settled_data[] = $top_up_settled;


            $month_day[] = date('Y-m-d', $start);
            $start = strtotime('+1 day', $start);
        }

        // Chart one
        $chart_one_data = [
            'pending_data'  => $pending_data,
            'success_data'  => $success_data,
            'canceled_data' => $canceled_data,
            'hold_data'     => $hold_data,
        ];

        // Chart four
        $chart_four_data = [
            'review_data'  => $top_up_review_data,
            'pending_data'  => $top_up_pending_data,
            'completed_data'  => $top_up_completed_data,
            'settled_data' => $top_up_settled_data,
        ];

        $data = [
            'chart_one_data' => $chart_one_data,
            'chart_four_data' => $chart_four_data,
            'month_day'        => $month_day,
        ];
        $latest_add_moneys = Transaction::where('type', PaymentGatewayConst::TYPEADDMONEY)->whereIn('status', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10])->limit(5)->latest()->get();

        $default_currency = Currency::default();


        return view('admin.sections.dashboard.index', compact(
            'page_title',
            'total_addmoney',
            'success_addmoney',
            'panding_addmoney',
            'percent_addmoney',
            'total_users',
            'active_users',
            'unverified_users',
            'user_perchant',
            'total_tickets',
            'active_tickets',
            'pending_tickets',
            'ticket_perchant',
            'chart_four',
            'data',

            'latest_add_moneys',

            'total_review_topup_amount',
            'total_review_topup_count',
            'percent_review_topup',

            'total_pending_topup_amount',
            'total_pending_topup_count',
            'percent_pending_topup',

            'total_settled_topup_amount',
            'total_settled_topup_count',
            'percent_settled_topup',

            'total_complete_topup_amount',
            'total_complete_topup_count',
            'percent_complete_topup',
            'default_currency'
        ));
    }


    /**
     * Logout Admin From Dashboard
     * @return view
     */
    public function logout(Request $request)
    {

        $push_notification_setting = BasicSettingsProvider::get()->push_notification_config;

        if ($push_notification_setting) {
            $method = $push_notification_setting->method ?? false;

            if ($method == "pusher") {
                $instant_id     = $push_notification_setting->instance_id ?? false;
                $primary_key    = $push_notification_setting->primary_key ?? false;

                if ($instant_id && $primary_key) {
                    $pusher_instance = new PushNotifications([
                        "instanceId"    => $instant_id,
                        "secretKey"     => $primary_key,
                    ]);

                    $pusher_instance->deleteUser("" . Auth::user()->id . "");
                }
            }
        }

        $admin = auth()->user();
        try {
            $admin->update([
                'last_logged_out'   => now(),
                'login_status'      => false,
            ]);
        } catch (Exception $e) {
            // Handle Error
        }

        Auth::guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }


    /**
     * Function for clear admin notification
     */
    public function notificationsClear()
    {
        $admin = auth()->user();

        if (!$admin) {
            return false;
        }

        try {
            $admin->update([
                'notification_clear_at'     => now(),
            ]);
        } catch (Exception $e) {
            $error = ['error' => ['Something went worng! Please try again.']];
            return Response::error($error, null, 404);
        }

        $success = ['success' => ['Notifications clear successfully!']];
        return Response::success($success, null, 200);
    }
}
