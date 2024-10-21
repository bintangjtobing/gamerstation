<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Helpers\Api\Helpers as ApiResponse;

class SecurityController extends Controller
{
    public function google2FA()
    {

        $user = Auth::guard(get_auth_guard())->user();
        $qr_code = generate_google_2fa_auth_qr();
        $qr_secrete = $user->two_factor_secret;
        $qr_status = $user->two_factor_status;
        $data = [
            'qr_code'    => $qr_code,
            'qr_secrete' => $qr_secrete,
            'qr_status'  => $qr_status,
            'alert' => "Don't forget to add this application in your google authentication app. Otherwise you can't login in your account.",
        ];
        return ApiResponse::success(['message' => ['Data Fetch Successfully']], $data);
    }
    public function google2FAStatusUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status'        => "required|numeric",
        ]);
        if ($validator->fails()) {
            return ApiResponse::error(['error' => $validator->errors()->all()]);
        }
        $validated = $validator->validated();
        $user = Auth::guard(get_auth_guard())->user();
        try {
            $user->update([
                'two_factor_status'         => $validated['status'],
                'two_factor_verified'       => true,
            ]);
        } catch (\Exception $e) {
            return ApiResponse::error(['error' => [__('Something went wrong! Please try again.')]]);
        }
        return ApiResponse::onlySuccess(['success' => [__('Google 2FA Updated Successfully!')]]);
    }

    public function google2FAVerified(Request $request)
    {

        $request->validate([
            'code'      => "required",
        ]);
        $code = $request->code;
        // $code = implode("", $code);

        $user = auth()->user();
        if (!$user->two_factor_secret) {
            return back()->with(['warning' => ['Your secret key not stored properly. Please contact with system administrator']]);
        }

        if (google_2fa_verify($user->two_factor_secret, $code)) {

            $user->update([
                'two_factor_verified'   => true,

            ]);

            return ApiResponse::onlysuccess(['success' => [__('Google 2FA Verified Successfully')]]);
        }

        return ApiResponse::error(['warning' => [__('Failed to login. Please try again')]]);
    }
}
