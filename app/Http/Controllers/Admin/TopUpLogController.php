<?php

namespace App\Http\Controllers\Admin;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\UserNotification;
use App\Constants\NotificationConst;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Validator;



class TopUpLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = "All Logs";
        $transactions = Transaction::with('user')->where('type', 'TOP-UP')->orderBy('id', 'desc')->paginate(20);

        return view('admin.sections.top-up.index', compact(
            'page_title',
            'transactions'
        ));
    }

    public function reviewPayment()
    {
        $page_title = "Pending Logs";
        $transactions = Transaction::with('user')->where('type', 'TOP-UP')->where('status', 1)->orderBy('id', 'desc')->paginate(20);

        return view('admin.sections.top-up.index', compact(
            'page_title',
            'transactions'
        ));
    }
    public function pending()
    {
        $page_title = "Pending Logs";
        $transactions = Transaction::with('user')->where('type', 'TOP-UP')->where('status', 2)->orderBy('id', 'desc')->paginate(20);

        return view('admin.sections.top-up.index', compact(
            'page_title',
            'transactions'
        ));
    }
    public function confirmPayment()
    {
        $page_title = "confirm Logs";
        $transactions = Transaction::with('user')->where('type', 'TOP-UP')->where('status', 3)->orderBy('id', 'desc')->paginate(20);

        return view('admin.sections.top-up.index', compact(
            'page_title',
            'transactions'
        ));
    }
    public function onhold()
    {
        $page_title = "On Hold";
        $transactions = Transaction::with('user')->where('type', 'TOP-UP')->where('status', 4)->orderBy('id', 'desc')->paginate(20);

        return view('admin.sections.top-up.index', compact(
            'page_title',
            'transactions'
        ));
    }
    public function settled()
    {
        $page_title = "Settled Logs";
        $transactions = Transaction::with('user')->where('type', 'TOP-UP')->where('status', 5)->orderBy('id', 'desc')->paginate(20);

        return view('admin.sections.top-up.index', compact(
            'page_title',
            'transactions'
        ));
    }
    public function completed()
    {
        $page_title = "Completed Logs";
        $transactions = Transaction::with('user')->where('type', 'TOP-UP')->where('status', 6)->orderBy('id', 'desc')->paginate(20);

        return view('admin.sections.top-up.index', compact(
            'page_title',
            'transactions'
        ));
    }
    public function canceled()
    {
        $page_title = "Canceled Logs";
        $transactions = Transaction::with('user')->where('type', 'TOP-UP')->where('status', 7)->orderBy('id', 'desc')->paginate(20);

        return view('admin.sections.top-up.index', compact(
            'page_title',
            'transactions'
        ));
    }


    public function failed()
    {
        $page_title = "Failed Logs";
        $transactions = Transaction::with('user')->where('type', 'TOP-UP')->where('status', 8)->orderBy('id', 'desc')->paginate(20);
        return view('admin.sections.top-up.index', compact(
            'page_title',
            'transactions'
        ));
    }
    public function refunded()
    {
        $page_title = "Refunded Logs";
        $transactions = Transaction::with('user')->where('type', 'TOP-UP')->where('status', 9)->orderBy('id', 'desc')->paginate(20);
        return view('admin.sections.top-up.index', compact(
            'page_title',
            'transactions'
        ));
    }
    public function delayed()
    {
        $page_title = "Delayed Logs";
        $transactions = Transaction::with('user')->where('type', 'TOP-UP')->where('status', 10)->orderBy('id', 'desc')->paginate(20);
        return view('admin.sections.top-up.index', compact(
            'page_title',
            'transactions'
        ));
    }


    public function topUpDetails($id)
    {
        $data = Transaction::where('id', $id)->with(
            'user:id,firstname,email,username,full_mobile',
            'currency:id,name,alias,payment_gateway_id,currency_code,rate',
        )->where('type', 'TOP-UP')->first();
        $page_title = "Top Up details for" . '  ' . $data->trx_id;
        return view('admin.sections.top-up.details', compact(
            'page_title',
            'data'
        ));
    }
    /**
     * This method for approved add money
     * @method PUT
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\Request Response
     */
    public function approved(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = Transaction::where('id', $request->id)->where('status', 2)->where('type', 'TOP-UP')->first();

        try {
            $data->status = 1;
            $data->save();

            if (!empty($data->user_id)) {
                $notification_content = [
                    'title'         => "Top Up",
                    'message'       => "Your " . get_amount($data->details->tempData->recharge_coin[0]) . ' ' . $data->details->tempData->coin_type . " has been successful",
                    'time'      => Carbon::now()->diffForHumans(),
                    'image'         => files_asset_path('profile-default'),
                ];


                UserNotification::create([
                    'type'      => NotificationConst::TOP_UP,
                    'user_id'  =>  $data->user_id,
                    'message'   => $notification_content,
                ]);
            }

            return redirect()->back()->with(['success' => ['Sell coin request approved successfully']]);
        } catch (\Exception $e) {
            return back()->with(['error' => [$e->getMessage()]]);
        }
    }

    /**
     * This method for reject add money
     * @method PUT
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\Request Response
     */
    // public function rejected(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'id' => 'required|integer',
    //         'reject_reason' => 'required|string',
    //     ]);
    //     if ($validator->fails()) {
    //         return back()->withErrors($validator)->withInput();
    //     }
    //     $data = Transaction::where('id', $request->id)->where('status', 2)->where('type', 'TOP-UP')->first();
    //     $reject['status'] = 4;
    //     $reject['reject_reason'] = $request->reject_reason;
    //     try {
    //         $data->fill($reject)->save();

    //         // $userWallet = UserWallet::where('user_id', $data->user_id)->first();
    //         if (!empty($data->user_id)) {
    //             //notification
    //             $notification_content = [
    //                 'title'         => "Top Up",
    //                 'message'       => "Your " . get_amount($data->details->tempData->recharge_coin[0]) . ' ' . $data->details->tempData->coin_type . " has been canceled",
    //                 'time'      => Carbon::now()->diffForHumans(),
    //                 'image'         => files_asset_path('profile-default'),
    //             ];

    //             UserNotification::create([
    //                 'type'      => NotificationConst::TOP_UP,
    //                 'user_id'  =>  $data->user_id,
    //                 'message'   => $notification_content,
    //             ]);
    //         }
    //         return redirect()->back()->with(['success' => ['Top up request rejected successfully']]);
    //     } catch (\Exception $e) {
    //         return back()->with(['error' => [$e->getMessage()]]);
    //     }
    // }

    function statusUpdate(Request $request, $id)
    {
        $validated = Validator::make($request->all(), ['status' => 'integer'])->validate();

        $transaction_id = Transaction::findOrFail($id);
        try {
            $transaction_id->update($validated);
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
