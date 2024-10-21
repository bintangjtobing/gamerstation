<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Models\Admin\AppSettings;
use App\Models\Admin\BasicSettings;
use App\Http\Controllers\Controller;
use App\Models\Admin\AppOnboardScreens;
use App\Http\Resources\SplashScreenResource;
use App\Http\Resources\OnboardScreenResource;
use App\Http\Helpers\Api\Helpers;
use App\Http\Helpers\Api\Helpers as ApiResponse;
use Illuminate\Support\Facades\Route;

class AppSettingsController extends Controller
{
    /**
     * Basic Settings Data Fetch
     *
     * @method GET
     * @return \Illuminate\Http\Response
     */

    public function basicSettings()
    {
        $basic_setting = BasicSettings::first();

        $image_path = get_files_public_path('app-images');
        $logo_image_path = get_files_public_path('image-assets');
        $default_logo = get_files_public_path('default');
        $onboard_screen = OnboardScreenResource::collection(AppOnboardScreens::where('status', 1)->get());
        $splash_screen =  new SplashScreenResource(AppSettings::first());
        $all_logo = BasicSettings::select('site_logo_dark', 'site_logo', 'site_fav_dark', 'site_fav')->first();

        $data = [
            'default_logo'    => $default_logo,
            'logo_image_path' => $logo_image_path,
            'image_path'      => $image_path,
            'onboard_screen'  => $onboard_screen,
            'splash_screen'   => $splash_screen,
            'all_logo'        => $all_logo,
            'basic_setting' => $basic_setting,
            'web_links'         => [
                'privacy-policy'    => url('/link/privacy-policy'),
                'about'          => Route::has('about') ? route('about') : url('/'),
                'contact-us'        => Route::has('contact') ? route('contact') : url('/'),

            ],
        ];
        $message = ['success' =>  [__('Data fetched successfully')]];
        return ApiResponse::success($message, $data);
    }
    public function languages()
    {
        try {
            $api_languages = get_api_languages();
        } catch (\Exception $e) {
            $error = ['error' => [$e->getMessage()]];
            return Helpers::error($error);
        }
        $data = [
            'languages' => $api_languages,
        ];
        $message =  ['success' => [__('Language Data Fetch Successfully!')]];
        return Helpers::success($message, $data);
    }
}
