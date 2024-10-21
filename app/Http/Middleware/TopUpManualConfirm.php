<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Auth\Middleware\Authenticate;
use App\Http\Helpers\Api\Helpers;

class TopUpManualConfirm extends Authenticate
{

    protected function authenticate($request, array $guards)
    {

        //for testing
        if ($this->auth->guard('api')->check()) {
            return $this->auth->shouldUse('api');
        } else {
            return $this->auth->shouldUse('api');
        }
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, ...$guards)
    {
        try {
            $this->authenticate($request, $guards);
        } catch (UnauthorizedException $e) {
            $message = ['error' => ['Unauthorized user']];
            return Helpers::unauthorized($message, $data = null);
        }
        return $next($request);
    }
}
