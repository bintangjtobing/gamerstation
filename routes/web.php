<?php

use App\Http\Controllers\Api\V1\AddMoneyController as UserAddMoneyController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\TopUpGameController;
use App\Http\Controllers\User\AddMoneyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/cc', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('optimize');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
    return "Cleared!";
});

Route::controller(SiteController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('topup', 'topup')->name('topup');
    Route::get('about', 'about')->name('about');
    Route::get('faq', 'faq')->name('faq');
    Route::get('contact', 'contact')->name('contact');
    Route::get('change/{lang?}', 'changeLanguage')->name('lang');

    Route::get('blog', 'blog')->name('blog');
    Route::get('blog/details/{id}/{slug}', 'blogDetails')->name('blog.details');
    Route::get('blog/by/category/{id}/{slug}', 'blogByCategory')->name('blog.by.category');
});

Route::controller(TopUpGameController::class)->prefix('top-up')->name('top.up.')->group(function () {
    Route::get('details/{slug}', 'detailsTopUp')->name('details');
    Route::post('submit', 'TopUpSubmit')->name('submit');
    Route::get('preview/{token}', 'preview')->name('preview');
    Route::post('preview/submit', 'previewSubmit')->name('preview.submit');
    Route::get('checkout/{token}', 'checkout')->name('checkout');

    Route::post('order', 'order')->name('order')->middleware('auth');
    Route::get('success/response/paypal/{gateway}', 'success')->name('payment.success');
    Route::get("cancel/response/paypal/{gateway}", 'cancel')->name('payment.cancel');
    // Controll AJAX Resuest
    Route::post("xml/currencies", "getCurrenciesXml")->name("xml.currencies");
    Route::get('payment/{gateway}', 'payment')->name('payment');
    Route::get('stripe/payment/success/{trx}', 'stripePaymentSuccess')->name('stripe.payment.success');
    Route::get('flutterwave/callback', 'flutterwaveCallback')->name('flutterwave.callback');
    //manual gateway
    Route::get('manual/payment', 'manualPayment')->name('manual.payment');
    Route::post('manual/payment/confirmed', 'manualPaymentConfirmed')->name('manual.payment.confirmed');
    //global
    Route::post("callback/response/{gateway}", 'callback')->name('payment.callback')->withoutMiddleware(['web', 'banned.user', 'auth', 'verification.guard', 'user.google.two.factor']);
    //Razorpay
    //redirect with Btn Pay
    Route::get('redirect/btn/checkout/{gateway}', 'redirectBtnPay')->name('payment.btn.pay')->withoutMiddleware(['auth', 'verification.guard', 'user.google.two.factor']);

    Route::get('success/response/{gateway}', 'successGlobal')->name('payment.global.success');
    Route::get("cancel/response/{gateway}", 'cancelGlobal')->name('payment.global.cancel');
    // POST Route For Unauthenticated Request
    Route::post('success/response/{gateway}', 'postSuccess')->name('payment.global.success')->withoutMiddleware(['auth', 'banned.user', 'verification.guard', 'user.google.two.factor']);
    Route::post('cancel/response/{gateway}', 'postCancel')->name('payment.global.cancel')->withoutMiddleware(['auth', 'banned.user', 'verification.guard', 'user.google.two.factor']);
    //search
    Route::post('search', 'search')->name('search');
});

Route::get('page/{slug}', [SiteController::class, 'usefullLink'])->name('usefullLink');
Route::post('/subscribe', [SubscribeController::class, 'subscribe'])->name('subscribe');
Route::post('/message', [MessageController::class, 'message'])->name('message');


//for sslcommerz callback urls(web)
Route::controller(AddMoneyController::class)->prefix("add-money")->name("add.money.")->group(function () {
    //sslcommerz
    Route::post('sslcommerz/success', 'sllCommerzSuccess')->name('ssl.success');
    Route::post('sslcommerz/fail', 'sllCommerzFails')->name('ssl.fail');
    Route::post('sslcommerz/cancel', 'sllCommerzCancel')->name('ssl.cancel');
});
//for sslcommerz callback urls(api)
Route::controller(UserAddMoneyController::class)->prefix("api-add-money")->name("api.add.money.")->group(function () {
    //sslcommerz
    Route::post('sslcommerz/success', 'sllCommerzSuccess')->name('ssl.success');
    Route::post('sslcommerz/fail', 'sllCommerzFails')->name('ssl.fail');
    Route::post('sslcommerz/cancel', 'sllCommerzCancel')->name('ssl.cancel');
});
