<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\User\UserResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Providers\Admin\BasicSettingsProvider;
use App\Http\Helpers\Api\Helpers as ApiResponse;
use GuzzleHttp\Psr7\Request as Psr7Request;

class ProfileController extends Controller
{
    /**
     * Profile Get Data
     *
     * @method GET
     * @return \Illuminate\Http\Response
     */

    public function profile()
    {
        $user = User::where('id', Auth::user()->id)->get()->map(function ($data) {
            $address = [
                'country' => $data->address->country ?? '',
                'city' => $data->address->city ?? '',
                'state' => $data->address->state ?? '',
                'zip' => $data->address->zip ?? '',
                'address' => $data->address->address ?? '',
            ];
            return [
                'id' => $data->id,
                'firstname' => $data->firstname,
                'lastname' => $data->lastname,
                'status' => $data->status,
                'email' => $data->email,
                'address' => (object)$address,
                'email' => $data->email,
                'mobile_code' => $data->mobile_code,
                'mobile' => $data->mobile,
                'username' => $data->username,
                'full_mobile' => $data->full_mobile,
                'image' => $data->image,
                'ver_code' => $data->ver_code,
                'ver_code_send_at' => $data->ver_code_send_at,
                'email_verified_at' => $data->email_verified_at,
                'email_verified' => $data->email_verified,
                'sms_verified' => $data->sms_verified,
                'created_at' => $data->created_at,
                'updated_at' => $data->updated_at,
                'kyc_verified' => $data->kyc_verified,
            ];
        })->first();
        $data = [
            'base_url' => url('/'),
            'default_image' => "public/backend/images/default/profile-default.webp",
            "image_path"    => "public/frontend/user",
            'user'          => (object)$user,
        ];

        $message =  ['success' => [__('User Profile')]];

        return ApiResponse::success($message, $data);
    }

    /**
     * Profile Update
     *
     * @method POST
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function profileUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'    => "required|string|max:60",
            'last_name'     => "required|string|max:60",
            'image'         => "nullable|image|mimes:jpg,png,svg,webp|max:10240",
            'country'       => "nullable|string|max:50",
            'phone'         => "nullable|numeric",
            'phone_code'    => "nullable|numeric",
            'state'         => "nullable|string|max:50",
            'city'          => "nullable|string|max:50",
            'address'       => "nullable|string|max:250",
            'zip_code'      => "nullable|string",
        ]);

        $user = auth()->user();

        if ($validator->fails()) {
            $error = ['error' => [$validator->errors()->all()]];
            return ApiResponse::validation($error);
        }

        $validated = $validator->validated();

        $validated['mobile']        = remove_speacial_char($validated['phone']);
        $validated['mobile_code']   = remove_speacial_char($validated['phone_code']);
        $complete_phone             = $validated['mobile_code'] . $validated['mobile'];
        $validated                  = Arr::except($validated, ['agree', 'phone_code', 'phone']);
        $validated['address']       = [
            'country'   => $validated['country'] ?? "",
            'state'     => $validated['state'] ?? "",
            'city'      => $validated['city'] ?? "",
            'zip'       => $validated['zip_code'] ?? "",
            'address' => $validated['address'],
        ];


        $validated['firstname']   = $validated['first_name'];
        $validated['lastname']    = $validated['last_name'];

        if ($request->hasFile('image')) {

            if ($user->image == null) {
                $oldImage = null;
            } else {
                $oldImage = $user->image;
            }

            $image = upload_file($validated['image'], 'user-profile', $oldImage);
            $upload_image = upload_files_from_path_dynamic([$image['dev_path']], 'user-profile');
            delete_file($image['dev_path']);
            $validated['image']     = $upload_image;
        }

        try {
            $user->update($validated);
        } catch (\Throwable $th) {
            $error = ['error' => [__('Something went wrong! Please try again.')]];
            return ApiResponse::error($error);
        }

        $message =  ['success' => [__('Profile successfully updated!')]];
        return ApiResponse::onlysuccess($message);
    }


    public function passwordUpdate(Request $request)
    {
        $basic_settings = BasicSettingsProvider::get();

        $passowrd_rule = 'required|string|min:6|confirmed';

        if ($basic_settings->secure_password) {
            $passowrd_rule = ["required", Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised(), "confirmed"];
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string|min:6',
            'password' => $passowrd_rule,
        ]);

        if ($validator->fails()) {
            $error =  ['error' => $validator->errors()->all()];
            return ApiResponse::validation($error);
        }

        $validated = $validator->validate();

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            $message = ['error' =>  ['Current password didn\'t match']];
            return ApiResponse::error($message);
        }
        try {
            Auth::user()->update(['password' => Hash::make($validated['password'])]);
            $message = ['success' =>  [__('Password updated successfully!')]];
            return ApiResponse::onlysuccess($message);
        } catch (Exception $ex) {
            info($ex);
            $message = ['error' => [__('Something went wrong! Please try again.')]];
            return ApiResponse::error($message);
        }
    }

    public function accountDelete()
    {
        $user = Auth::user();
        $user->update([
            'status' => 0,
            'email_verified' => 0,
            'two_factor_verified' => 0,
            'two_factor_status' => 0,
        ]);
        $message =  ['success' => [__('Account Delete successfully!')]];
        return ApiResponse::onlysuccess($message);
    }
}
