<?php

use App\Http\Controllers\SellCoinController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\WalletController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\AddMoneyController;
use App\Http\Controllers\User\WithdrawController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\GameTopupController;
use App\Http\Controllers\User\SecurityController;
use App\Http\Controllers\User\TransactionController;
use App\Http\Controllers\User\SupportTicketController;


Route::prefix("user")->name("user.")->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('dashboard', 'index')->name('dashboard');
        Route::post('logout', 'logout')->name('logout');
    });

    Route::controller(ProfileController::class)->prefix("profile")->name("profile.")->group(function () {
        Route::get('/', 'index')->name('index');
        Route::put('password/update', 'passwordUpdate')->name('password.update')->middleware('app.mode');
        Route::put('update', 'update')->name('update')->middleware('app.mode');
        Route::post('account/delete/{id}', 'accountDelete')->name('account.delete')->middleware('app.mode');
    });

    Route::controller(GameTopupController::class)->prefix('game-topup')->name('game.topup.')->group(function () {
        Route::get('/', 'index')->name('index');
    });
    Route::controller(SupportTicketController::class)->prefix("support-ticket")->name("support.ticket.")->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('conversation/{encrypt_id}', 'conversation')->name('conversation');
        Route::post('message/send', 'messageSend')->name('messaage.send');
    });

    Route::controller(WalletController::class)->prefix("wallets")->name("wallets.")->group(function () {
        Route::get("/", "index")->name("index");
        Route::post("balance", "balance")->name("balance");
    });

    //add money
    Route::controller(AddMoneyController::class)->prefix("add-money")->name("add.money.")->group(function () {
        Route::get('/', 'index')->name("index");
        Route::post('submit', 'submit')->name('submit');
        Route::get('preview/{token}', 'preview')->name('preview');
        Route::post('preview/submit', 'previewSubmit')->name('preview.submit');

        Route::get('success/response/paypal/{gateway}', 'success')->name('payment.success');
        Route::get("cancel/response/paypal/{gateway}", 'cancel')->name('payment.cancel');
        // Controll AJAX Resuest
        Route::post("xml/currencies", "getCurrenciesXml")->name("xml.currencies");
        Route::get('payment/{gateway}', 'payment')->name('payment');
        //manual gateway
        Route::get('manual/payment', 'manualPayment')->name('manual.payment');
        Route::post('manual/payment/confirmed', 'manualPaymentConfirmed')->name('manual.payment.confirmed');

        Route::get('flutterwave/callback', 'flutterwaveCallback')->name('flutterwave.callback');
        //global
        Route::post("callback/response/{gateway}", 'callback')->name('payment.callback')->withoutMiddleware(['web', 'banned.user', 'auth', 'verification.guard', 'user.google.two.factor']);
        //redirect with Btn Pay
        Route::get('redirect/btn/checkout/{gateway}', 'redirectBtnPay')->name('payment.btn.pay')->withoutMiddleware(['auth', 'verification.guard', 'user.google.two.factor']);

        Route::get('success/response/{gateway}', 'successGlobal')->name('payment.global.success');
        Route::get("cancel/response/{gateway}", 'cancelGlobal')->name('payment.global.cancel');
        // POST Route For Unauthenticated Request
        Route::post('success/response/{gateway}', 'postSuccess')->name('payment.global.success')->withoutMiddleware(['auth', 'banned.user', 'verification.guard', 'user.google.two.factor']);
        Route::post('cancel/response/{gateway}', 'postCancel')->name('payment.global.cancel')->withoutMiddleware(['auth', 'banned.user', 'verification.guard', 'user.google.two.factor']);

        Route::get('stripe/payment/success/{trx}', 'stripePaymentSuccess')->name('stripe.payment.success');
        // UddoktaPay
        Route::get('uddokta/pay/success/{id}', 'udddoktaPaySuccess')->name('uddokta.pay.success');
        Route::get('uddokta/pay/cancel/{id}', 'udddoktaPayCancel')->name('uddokta.pay.cancel');
    });
    //withdraw
    Route::controller(WithdrawController::class)->prefix("withdraw")->name('withdraw.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('submit', 'submit')->name('submit');
        Route::get('instruction/{token}', 'instruction')->name('instruction');
        Route::post('instruction/submit/{token}', 'instructionSubmit')->name('instruction.submit');
    });


    //Sell Coin
    Route::controller(SellCoinController::class)->prefix('sell-coin')->name('sell.coin.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('receive', 'receivingMethodSubmit')->name('receiving.method.submit');
        Route::post('submit', 'submit')->name('submit');
        Route::get('instruction/{token}', 'instruction')->name('instruction');
        Route::post('instruction/submit/{token}', 'instructionSubmit')->name('instruction.submit');
    });
    //transactions
    Route::controller(TransactionController::class)->prefix("transactions")->name("transactions.")->group(function () {
        Route::get('/{slug?}', 'index')->name('index')->whereIn('slug', ['add-money', 'top-up']);
        // Route::get('/{slug?}', 'index')->name('index')->whereIn('slug', ['add-money', 'money-out', 'buy-coin', 'sell-coin', 'withdraw']);
        // Route::get('log/{slug?}','log')->name('log')->whereIn('slug',['add-money','money-out','transfer-money']);
        Route::post('search', 'search')->name('search');
    });

    //google-2fa
    Route::controller(SecurityController::class)->prefix('security')->name('security.')->group(function () {
        Route::get('google/2fa', 'google2FA')->name('google.2fa');
        Route::post('google/2fa/status/update', 'google2FAStatusUpdate')->name('google.2fa.status.update')->middleware('app.mode');
    });
});
