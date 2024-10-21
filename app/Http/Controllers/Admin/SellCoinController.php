<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\UserWallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\UserNotification;
use App\Constants\NotificationConst;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SellCoinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = "All Logs";
        $transactions = Transaction::with('user')->where('type', 'SELL-COIN')->orderBy('id', 'desc')->paginate(20);

        return view('admin.sections.sell-coin.index', compact(
            'page_title',
            'transactions'
        ));
    }
    /**
     * Display All Pending Logs
     * @return view
     */
    public function pending()
    {
        $page_title = "Pending Logs";
        $transactions = Transaction::with('user')->where('type', 'SELL-COIN')->where('status', 2)->orderBy('id', 'desc')->paginate(20);

        return view('admin.sections.sell-coin.index', compact(
            'page_title',
            'transactions'
        ));
    }

    /**
     * Display All Complete Logs
     * @return view
     */
    public function complete()
    {
        $page_title = "Complete Logs";
        $transactions = Transaction::with('user')->where('type', 'SELL-COIN')->where('status', 1)->orderBy('id', 'desc')->paginate(20);
        return view('admin.sections.sell-coin.index', compact(
            'page_title',
            'transactions'
        ));
    }

    /**
     * Display All Canceled Logs
     * @return view
     */
    public function canceled()
    {
        $page_title = "Canceled Logs";
        $transactions = Transaction::with('user')->where('type', 'SELL-COIN')->where('status', 4)->orderBy('id', 'desc')->paginate(20);
        return view('admin.sections.sell-coin.index', compact(
            'page_title',
            'transactions'
        ));
    }
    /**
     * This method for show details of add money
     * @return view $details-add-money-logs
     */
    public function sellCoinDetails($id)
    {
        $data = Transaction::where('id', $id)->with(
            'user:id,firstname,email,username,full_mobile',
            'currency:id,name,alias,payment_gateway_id,currency_code,rate',
        )->where('type', 'SELL-COIN')->first();
        $page_title = "Sell Coin details for" . '  ' . $data->trx_id;
        return view('admin.sections.sell-coin.details', compact(
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

        $data = Transaction::where('id', $request->id)->where('status', 2)->where('type', 'SELL-COIN')->first();

        try {
            //update wallet
            // $userWallet = UserWallet::where('user_id', $data->user_id)->first();
            // $userWallet->balance -=  $data->payable;
            // $userWallet->save();
            //update transaction
            $data->status = 1;
            // $data->available_balance =  $userWallet->balance;
            $data->save();

            $notification_content = [
                'title'         => "Buy Coin",
                'message'       => "Your " . get_amount($data->coin) .  ' coin sell has been successful',
                // 'time'          => $userWallet->updated_at->diffForHumans(),
                'image'         => files_asset_path('profile-default'),
            ];

            UserNotification::create([
                'type'      => NotificationConst::SELL_COIN,
                'user_id'  =>  $data->user_id,
                'message'   => $notification_content,
            ]);

            return redirect()->back()->with(['success' => ['Sell coin request approved successfully']]);
        } catch (Exception $e) {
            return back()->with(['error' => [$e->getMessage()]]);
        }
    }

    /**
     * This method for reject add money
     * @method PUT
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\Request Response
     */
    public function rejected(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'reject_reason' => 'required|string',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = Transaction::where('id', $request->id)->where('status', 2)->where('type', 'SELL-COIN')->first();
        $reject['status'] = 4;
        $reject['reject_reason'] = $request->reject_reason;
        try {
            $data->fill($reject)->save();

            // $userWallet = UserWallet::where('user_id', $data->user_id)->first();
            //notification
            $notification_content = [
                'title'         => "Buy Coin",
                'message'       => "Your " . get_amount($data->coin) .  ' coin sell has been canceled',
                'time'          => 1,
                'image'         => files_asset_path('profile-default'),
            ];

            UserNotification::create([
                'type'      => NotificationConst::SELL_COIN,
                'user_id'  =>  $data->user_id,
                'message'   => $notification_content,
            ]);
            return redirect()->back()->with(['success' => ['Sell coin request rejected successfully']]);
        } catch (Exception $e) {
            return back()->with(['error' => [$e->getMessage()]]);
        }
    }
}
