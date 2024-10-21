<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Response;
use App\Models\Admin\AddCoin;
use App\Models\Admin\TransactionSetting;
use App\Models\BuyCoin;
use App\Models\UserWallet;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BuyCoinController extends Controller
{
    public function index()
    {
        $page_title = "Buy Coin";
        $add_coins = AddCoin::orderBy('id', 'desc')->paginate(12);
        return view('user.sections.buy-coin.index', compact('add_coins', 'page_title'));
    }
    public function submit(Request $request)
    {
        $user_id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'login_vender' => 'required',
            'username_or_email' => 'required',
            'password' => 'required',
            'recharge' => 'required',
        ]);
        $validated = $validator->validate();

        $userWallet = UserWallet::where('user_id', $user_id)->first();
        if (!$userWallet) {
            return back()->with(['error' => ['Your wallet not found']]);
        }

        $coinPrice = $request->recharge;
        $exploded = explode("|", $coinPrice);
        $coin = $exploded[0];
        $price = $exploded[1];

        $user_balance = $userWallet->balance;
        if ($user_balance < $price) {
            return back()->with(['error' => ['Don\'t have enough balance']]);
        }

        Session::forget('userWallet');
        Session::forget('coin');
        Session::forget('price');
        Session::forget('buy_coin_request');
        Session::forget('user_id');

        Session::put('coin', $coin);
        Session::put('price', $price);
        Session::put('userWallet', $userWallet);
        Session::put('buy_coin_request', $validated);
        Session::put('user_id', $user_id);

        return redirect()->route('user.buy.coin.preview');
    }
    public function preview()
    {
        $validated = Session::get('buy_coin_request');
        $charge = TransactionSetting::all()->first();
        $fixed_charge = $charge->fixed_charge;
        $percent_charge = ($charge->percent_charge * Session::get('price')) / 100;
        return view('user.sections.buy-coin.preview', compact('validated', 'fixed_charge', 'percent_charge'));
    }


    public function previewSubmit(Request $request)
    {
        $charge = TransactionSetting::all()->first();
        $fixed_charge = $charge->fixed_charge;
        $percent_charge = ($charge->percent_charge * Session::get('price')) / 100;
        $total_charge = $fixed_charge + $percent_charge;
        $coin = Session::get('coin');
        $price = Session::get('price');
        $userWallet = Session::get('userWallet');
        $buy_coin_request = Session::get('buy_coin_request');
        $user_id = Session::get('user_id');

        $buy_coin = BuyCoin::where('id', $user_id)->first();
        if ($buy_coin ? $buy_coin->status == 2 : false) {
            $user_balance = $userWallet->balance;
            $deduct_balance = $user_balance - ($total_charge + $price);
            $userWallet->update([
                'balance' => $deduct_balance
            ]);
        }

        try {
            BuyCoin::create([
                'login_vender' => $buy_coin_request['login_vender'],
                'username_or_email' => $buy_coin_request['username_or_email'],
                'password' => $buy_coin_request['password'],
                'coin' => $coin,
                'price' => $price,
                'charge' => $total_charge,
                'trx_id' => generate_unique_string('transactions', 'trx_id', 16),
                'user_id' => $user_id,
                'total_amount' => $total_charge + $price,
            ]);
        } catch (Exception $e) {
            dd($e->getMessage());
            return back()->with(['error' => ['Somthing was wrong!']]);
        }

        Session::forget('userWallet');
        Session::forget('coin');
        Session::forget('price');
        Session::forget('user_id');
        Session::forget('buy_coin_request');

        return redirect()->route('user.buy.coin.index')->with(['success' => ['Coin purchase successfully']]);
    }
}
