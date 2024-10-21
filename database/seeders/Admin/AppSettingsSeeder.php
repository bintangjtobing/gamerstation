<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\AppOnboardScreens;
use App\Models\Admin\AppSettings;
use Illuminate\Database\Seeder;

class AppSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $app_settings = array(
            array('id' => '1', 'version' => '2.3.0', 'splash_screen_image' => 'bc316d0c-7513-45ed-b9c3-df67a88d5404.webp', 'url_title' => 'App Title', 'android_url' => 'https://play.google.com/store', 'iso_url' => 'https://www.apple.com/app-store/', 'created_at' => '2023-05-16 05:59:38', 'updated_at' => '2023-10-30 04:39:11')
        );

        AppSettings::insert($app_settings);

        $app_onboard_screens = array(
            array('id' => '1', 'title' => 'Make Every Second Count', 'sub_title' => 'Earn money by selling game coins using game shop', 'image' => '921717cb-eb9e-41ee-a3e4-66cdd9375c89.webp', 'status' => '1', 'last_edit_by' => '1', 'created_at' => '2023-06-23 16:35:09', 'updated_at' => '2023-09-11 06:18:23'),
            array('id' => '2', 'title' => 'Make Every Second Count', 'sub_title' => 'Earn money by selling game coins using game shop', 'image' => '62ba1a59-19fc-42a6-9594-f737033b56a1.webp', 'status' => '1', 'last_edit_by' => '1', 'created_at' => '2023-09-11 06:18:42', 'updated_at' => '2023-09-11 06:18:42'),
            array('id' => '3', 'title' => 'Make Every Second Count', 'sub_title' => 'Earn money by selling game coins using game shop', 'image' => '8ba979c3-e691-4fdf-aa65-c5338a18ee8f.webp', 'status' => '1', 'last_edit_by' => '1', 'created_at' => '2023-09-11 06:18:56', 'updated_at' => '2023-09-11 06:18:56')
        );

        AppOnboardScreens::insert($app_onboard_screens);
    }
}
