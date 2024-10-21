<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\BasicSettings;
use Illuminate\Database\Seeder;

class BasicSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $basic_settings = array(
            array('id' => '1', 'web_version' => '2.3.0', 'site_name' => 'Game Shop', 'site_title' => 'Online Coin Selling Platform', 'base_color' => '#ff4800', 'secondary_color' => '#ea5455', 'otp_exp_seconds' => '3600', 'timezone' => 'Asia/Dhaka', 'user_registration' => '1', 'secure_password' => '0', 'agree_policy' => '1', 'force_ssl' => '0', 'email_verification' => '1', 'sms_verification' => '0', 'email_notification' => '1', 'push_notification' => '0', 'kyc_verification' => '0', 'site_logo_dark' => '3cbc3d65-a79e-4ff7-bf05-32e4d3531a0f.webp', 'site_logo' => '1d0ebe0b-0282-410b-849d-53e9c518702f.webp', 'site_fav_dark' => '4ed7eda8-7e3d-453c-bead-0874123e93e2.webp', 'site_fav' => 'fefe29c6-c80b-459c-aa89-1da504fb2ef4.webp', 'mail_config' => '{"method":"smtp","host":"appdevs.net","port":"465","encryption":"ssl","username":"system@appdevs.net","password":"QP2fsLk?80Ac","from":"system@appdevs.net","app_name":"Game Shop"}', 'mail_activity' => NULL, 'push_notification_config' => '{"method":"pusher","instance_id":"809313fc-1f5c-4d0b-90bc-1c6751b83bbd","primary_key":"58C901DC107584D2F1B78E6077889F1C591E2BC39E9F5C00B4362EC9C642F03F"}', 'push_notification_activity' => NULL, 'broadcast_config' => '{"method":"pusher","app_id":"1539602","primary_key":"39079c30de823f783dbe","secret_key":"78b81e5e7e0357aee3df","cluster":"ap2"}', 'broadcast_activity' => NULL, 'sms_config' => NULL, 'sms_activity' => NULL, 'created_at' => '2023-05-16 05:59:38', 'updated_at' => '2023-10-29 09:04:21')
        );

        BasicSettings::insert($basic_settings);
    }
}
