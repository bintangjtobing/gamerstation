<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\AddMoneyController;
use App\Http\Controllers\Api\V1\SecurityController;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\AppSettingsController;
use App\Http\Controllers\Api\V1\TransactionController;
use App\Http\Controllers\Api\V1\Auth\AuthorizationController;
use App\Http\Controllers\Api\V1\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\V1\TopUpController;

Route::name('api.v1.')->group(function () {

    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {

        // Route::get('basic/settings', [AppSettingsController::class, "basicSettings"]);
        // Route::get('basic/settings', [AppSettingsController::class, "basicSettings"]);

        Route::controller(AppSettingsController::class)->group(function () {
            Route::get('basic/settings', 'basicSettings');
            Route::get('languages', 'languages');
        });

        Route::post('register', [AuthController::class, 'register'])->middleware(['user.registration.permission']);
        Route::post('login', [AuthController::class, 'login']);

        Route::group(['prefix' => 'forgot/password'], function () {
            Route::post('send/otp', [ForgotPasswordController::class, 'sendCode']);
            Route::post('verify',  [ForgotPasswordController::class, 'verifyCode']);
            Route::post('reset', [ForgotPasswordController::class, 'resetPassword']);
        });
        // Route::controller(AddMoneyController::class)->prefix("add-money")->group(function () {
        //     Route::get('success/response/{gateway}', 'success')->name('api.payment.success');
        //     Route::get("cancel/response/{gateway}", 'cancel')->name('api.payment.cancel');
        //     Route::get('/flutterwave/callback', 'flutterwaveCallback')->name('api.flutterwave.callback');
        //     Route::get('razor/callback', 'razorCallback')->name('api.razor.callback');
        //     Route::get('stripe/payment/success/{trx}','stripePaymentSuccess')->name('api.stripe.payment.success');
        // });

        Route::middleware(['auth.api', 'auth:api'])->group(function () {
            Route::get('logout', [AuthorizationController::class, 'logout']);
            Route::post('otp/verify', [AuthorizationController::class, 'verifyCode']);
            Route::post('resend/code', [AuthorizationController::class, 'resendCode']);

            Route::middleware('checkStatusApiUser')->group(function () {

                Route::controller(AddMoneyController::class)->prefix('add-money')->name('add-money.')->group(function () {
                    Route::get('information', 'AddMoneyInformation');
                    Route::post('submit-data', 'submitData');

                    // Automatic
                    // Route::post('stripe/payment/confirm', 'paymentConfirmedApi')->name('stripe.payment.confirmed');
                    // Manual
                    Route::post('manual/payment/confirmed', 'manualPaymentConfirmedApi')->name('manual.payment.confirmed');
                });

                Route::controller(SecurityController::class)->prefix('security')->group(function () {
                    Route::get('google-2fa', 'google2FA');
                    Route::post('google-2fa/status/update', 'google2FAStatusUpdate')->middleware('app.mode');
                    Route::post('google-2fa/verified', 'google2FAVerified')->middleware('app.mode');
                });

                //Dashboard
                Route::controller(DashboardController::class)->name('dashboard.')->group(function () {
                    Route::get('dashboard', 'dashboard')->name('index');
                });

                // User Profile
                Route::controller(ProfileController::class)->prefix('profile')->middleware('app.mode')->group(function () {
                    Route::get('/', 'profile');
                    Route::post('update', 'profileUpdate')->middleware('app.mode');
                    Route::post('password/update', 'passwordUpdate')->middleware('app.mode');
                    Route::get('delete', 'accountDelete')->middleware('app.mode');
                });

                //Transaction Log
                Route::controller(TransactionController::class)->prefix('transaction')->group(function () {
                    Route::get('addmoney', 'addmoney');
                    Route::get('purchase', 'purchase');
                });
            });
        });
    });

    Route::controller(TopUpController::class)->prefix('topup')->name('topup.')->group(function () {
        Route::get('/', 'topUpList');
        Route::get('details/{id}', 'details');
        Route::get('purchase/{id}', 'purchaseDetails');
        Route::post('purchase/submit', 'purchaseSubmit');

        // Automatic
        Route::post('stripe/payment/confirm', 'paymentConfirmedApi')->name('stripe.payment.confirmed');

        // Razor
        //redirect with Btn Pay
        Route::get('redirect/btn/checkout/{gateway}', 'redirectBtnPay')->name('api.payment.btn.pay')->withoutMiddleware(['auth:api', 'auth.api', 'CheckStatusApiUser']);

        // Global Gateway Response Routes
        Route::get('success/response/{gateway}', 'successGlobal')->withoutMiddleware(['auth:api', 'auth.api', 'CheckStatusApiUser'])->name("api.payment.global.success");
        Route::get("cancel/response/{gateway}", 'cancelGlobal')->withoutMiddleware(['auth:api', 'auth.api', 'CheckStatusApiUser'])->name("api.payment.global.cancel");

        // POST Route For Unauthenticated Request
        Route::post('success/response/{gateway}', 'postSuccess')->name('api.payment.global.success')->withoutMiddleware(['auth:api', 'auth.api', 'CheckStatusApiUser']);
        Route::post('cancel/response/{gateway}', 'postCancel')->name('api.payment.global.cancel')->withoutMiddleware(['auth:api', 'auth.api', 'CheckStatusApiUser']);

        // Manual
        Route::post('manual/payment/confirmed', 'manualPaymentConfirmedApi')->name('manual.payment.confirmed')->middleware('topup.manual.confirm.auth.api');

        // Route::get('success/response/{gateway}', 'success')->name('api.payment.success');
        // Route::get("cancel/response/{gateway}", 'cancel')->name('api.payment.cancel');
    });

    Route::controller(AddMoneyController::class)->prefix("add-money")->group(function () {
        Route::get('success/response/paypal/{gateway}', 'success')->name('api.payment.success');
        Route::get("cancel/response/paypal/{gateway}", 'cancel')->name('api.payment.cancel');
        Route::get('/flutterwave/callback', 'flutterwaveCallback')->name('api.flutterwave.callback');
        Route::get('stripe/payment/success/{trx}', 'stripePaymentSuccess')->name('api.stripe.payment.success');

        // UddoktaPay
        Route::get('uddokta/pay/success/{id}', 'udddoktaPaySuccess')->name('api.uddokta.pay.success');
        Route::get('uddokta/pay/cancel/{id}', 'udddoktaPayCancel')->name('api.uddokta.pay.cancel');

        // Razor
        //redirect with Btn Pay
        Route::get('redirect/btn/checkout/{gateway}', 'redirectBtnPay')->name('api.user.add.money.payment.btn.pay')->withoutMiddleware(['auth:api', 'auth.api', 'CheckStatusApiUser']);

        // Global Gateway Response Routes
        Route::get('success/response/{gateway}', 'successGlobal')->withoutMiddleware(['auth:api', 'auth.api', 'CheckStatusApiUser'])->name("api.user.add.money.payment.global.success");
        Route::get("cancel/response/{gateway}", 'cancelGlobal')->withoutMiddleware(['auth:api', 'auth.api', 'CheckStatusApiUser'])->name("api.user.add.money.payment.global.cancel");

        // POST Route For Unauthenticated Request
        Route::post('success/response/{gateway}', 'postSuccess')->name('api.user.add.money.payment.global.success')->withoutMiddleware(['auth:api', 'auth.api', 'CheckStatusApiUser']);
        Route::post('cancel/response/{gateway}', 'postCancel')->name('api.user.add.money.payment.global.cancel')->withoutMiddleware(['auth:api', 'auth.api', 'CheckStatusApiUser']);
    });
});
