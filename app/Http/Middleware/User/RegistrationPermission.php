<?php

namespace App\Http\Middleware\User;

use App\Http\Helpers\Response;
use App\Providers\Admin\BasicSettingsProvider;
use Closure;
use Illuminate\Http\Request;

class RegistrationPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $basic_settings = BasicSettingsProvider::get();
        if($request->expectsJson()) {
            if($basic_settings->user_registration != true){
                $message = ['error'=>[__("Registration Option Currently Off")]];
                return Response::error($message);
            }
            return $next($request);
        }
        if($basic_settings->user_registration != true) return back()->withInput()->with(['warning' => [__("Registration Option Currently Off")]]);
        return $next($request);
    }
}
