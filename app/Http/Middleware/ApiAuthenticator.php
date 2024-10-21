<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Auth\Middleware\Authenticate;
use App\Http\Helpers\Api\Helpers;

class ApiAuthenticator extends Authenticate
{

    /**
     * Determine if the user is authenticated and authorized to access the requested resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     * @throws \Illuminate\Validation\UnauthorizedException
     */
    protected function authenticate($request, array $guards)
    {

        // if ($this->auth->guard('api')->check()) {
        //     return $this->auth->shouldUse('api');
        // }
        // throw new UnauthorizedException('sorry');


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
