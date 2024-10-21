<?php

namespace App\Http\Middleware;

use App\Constants\PaymentGatewayConst;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'user/username/check',
        'user/check/email',
        //sslcommerz for web and api
        '/add-money/sslcommerz/success',
        '/add-money/sslcommerz/cancel',
        '/add-money/sslcommerz/fail',
        '/add-money/sslcommerz/ipn',
        '/api-add-money/sslcommerz/success',
        '/api-add-money/sslcommerz/cancel',
        '/api-add-money/sslcommerz/fail',
        '/api-add-money/sslcommerz/ipn',
        //Razorpay for addmoney
        'user/add-money/success/response/' . PaymentGatewayConst::RAZORPAY,
        'user/add-money/cancel/response/' . PaymentGatewayConst::RAZORPAY,
        //Razorpay for donation
        'top-up/success/response/' . PaymentGatewayConst::RAZORPAY,
        'top-up/cancel/response/' . PaymentGatewayConst::RAZORPAY,

    ];
}
