<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\UserWallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\UserNotification;
use App\Constants\NotificationConst;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AddMoneyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = "All Logs";
        $transactions = Transaction::with(
            'user:id,firstname,email,username,mobile',
            'payment_gateway:id,name',
        )->where('type', 'add-money')->paginate(20);
        return view('admin.sections.add-money.index', compact(
            'page_title',
            'transactions'
        ));
    }

    public function reviewPayment()
    {
        $page_title = "Review Logs";
        $transactions = Transaction::with(
            'user:id,firstname,email,username,mobile',
            'payment_gateway:id,name',
        )->where('type', 'add-money')->where('status', 1)->paginate(20);

        return view('admin.sections.add-money.index', compact(
            'page_title',
            'transactions'
        ));
    }

    public function pending()
    {
        $page_title = "Pending Logs";
        $transactions = Transaction::with(
            'user:id,firstname,email,username,mobile',
            'payment_gateway:id,name',
        )->where('type', 'add-money')->where('status', 2)->paginate(20);

        return view('admin.sections.add-money.index', compact(
            'page_title',
            'transactions'
        ));
    }

    public function confirmPayment()
    {
        $page_title = "Confirm Logs";
        $transactions = Transaction::with(
            'user:id,firstname,email,username,mobile',
            'payment_gateway:id,name',
        )->where('type', 'add-money')->where('status', 3)->paginate(20);

        return view('admin.sections.add-money.index', compact(
            'page_title',
            'transactions'
        ));
    }

    public function onhold()
    {
        $page_title = "On Hold Logs";
        $transactions = Transaction::with(
            'user:id,firstname,email,username,mobile',
            'payment_gateway:id,name',
        )->where('type', 'add-money')->where('status', 4)->paginate(20);

        return view('admin.sections.add-money.index', compact(
            'page_title',
            'transactions'
        ));
    }

    public function settled()
    {
        $page_title = "Settled Logs";
        $transactions = Transaction::with(
            'user:id,firstname,email,username,mobile',
            'payment_gateway:id,name',
        )->where('type', 'add-money')->where('status', 5)->paginate(20);

        return view('admin.sections.add-money.index', compact(
            'page_title',
            'transactions'
        ));
    }

    public function completed()
    {
        $page_title = "Completed Logs";
        $transactions = Transaction::with(
            'user:id,firstname,email,username,mobile',
            'payment_gateway:id,name',
        )->where('type', 'add-money')->where('status', 6)->paginate(20);

        return view('admin.sections.add-money.index', compact(
            'page_title',
            'transactions'
        ));
    }

    public function canceled()
    {
        $page_title = "Canceled Logs";
        $transactions = Transaction::with(
            'user:id,firstname,email,username,mobile',
            'payment_gateway:id,name',
        )->where('type', 'add-money')->where('status', 7)->paginate(20);

        return view('admin.sections.add-money.index', compact(
            'page_title',
            'transactions'
        ));
    }

    public function failed()
    {
        $page_title = "Failed Logs";
        $transactions = Transaction::with(
            'user:id,firstname,email,username,mobile',
            'payment_gateway:id,name',
        )->where('type', 'add-money')->where('status', 8)->paginate(20);

        return view('admin.sections.add-money.index', compact(
            'page_title',
            'transactions'
        ));
    }

    public function refunded()
    {
        $page_title = "Refunded Logs";
        $transactions = Transaction::with(
            'user:id,firstname,email,username,mobile',
            'payment_gateway:id,name',
        )->where('type', 'add-money')->where('status', 9)->paginate(20);

        return view('admin.sections.add-money.index', compact(
            'page_title',
            'transactions'
        ));
    }

    public function delayed()
    {
        $page_title = "Dalayed Logs";
        $transactions = Transaction::with(
            'user:id,firstname,email,username,mobile',
            'payment_gateway:id,name',
        )->where('type', 'add-money')->where('status', 10)->paginate(20);

        return view('admin.sections.add-money.index', compact(
            'page_title',
            'transactions'
        ));
    }

    public function addMoneyDetails($id)
    {
        $data = Transaction::where('id', $id)->with(
            'user:id,firstname,email,username,full_mobile',
            'currency:id,name,alias,payment_gateway_id,currency_code,rate',
        )->where('type', 'add-money')->first();
        $page_title = "Add money details for" . '  ' . $data->trx_id;
        return view('admin.sections.add-money.details', compact(
            'page_title',
            'data'
        ));
    }

    function statusUpdate(Request $request, $id)
    {
        $validated = Validator::make($request->all(), ['status' => 'integer'])->validate();

        $transaction_id = Transaction::findOrFail($id);

        try {
            $transaction_id->update($validated);
            $data = Transaction::where([['id', $id], ['type', 'ADD-MONEY'], ['status', 6]])->first();

            $message = 'Add money status updated';

            if (isset($data)) {
                $userWallet = UserWallet::where('user_id', auth()->user()->id)->first();
                $userWallet->balance +=  $data->request_amount;
                $userWallet->save();

                $message = "Your Wallet (" . $userWallet->currency->code . ") balance  has been added " . get_amount($data->request_amount) . ' ' . $userWallet->currency->code;
            }

            //notification
            $notification_content = [
                'title'         => "Add Money",
                'message'       => $message,
                'time'          => now(),
                'image'         => files_asset_path('profile-default'),
            ];

            UserNotification::create([
                'type'      => NotificationConst::BALANCE_ADDED,
                'user_id'  => $transaction_id->user->id,
                'message'   => $notification_content,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => ['Somthing went wrong!. Please try again.']]);
        }
        return back()->with(['success' => ['Status updated successfully']]);
    }

    public function rejected(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'rejected_id' => 'required|integer',
            'rejection_reason' => 'nullable|string'
        ])->validate();

        $transaction_id = Transaction::findOrFail($validated['rejected_id']);
        try {
            $transaction_id->update([
                'status' => 11,
                'reject_reason' => $validated['rejection_reason']
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => ['Somthing went wrong!. Please try again.']]);
        }

        return back()->with(['success' => ['Rejected successfully']]);
    }
}
