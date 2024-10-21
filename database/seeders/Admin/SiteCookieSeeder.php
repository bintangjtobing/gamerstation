<?php

// namespace Database\Seeders\Update;
namespace Database\Seeders\Admin;

use App\Models\Admin\SiteSections;
use Illuminate\Database\Seeder;

class SiteCookieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'status'    => true,
            'link'      => 'page/privacy-policy',
            'desc'      => 'We may use cookies or any other tracking technologies when you visit our website, including any other media form, mobile website, or mobile application related or connected to help customize the Site and improve your experience.',
        ];
        $cookie = SiteSections::siteCookie();
        $cookie->value = $data;
        $cookie->status = true;
        $cookie->save();
    }
}
