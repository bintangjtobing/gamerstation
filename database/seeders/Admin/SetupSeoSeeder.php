<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\SetupSeo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SetupSeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'slug'          => "appdevs",
            'title'         => "AppDevs - Software Solutions",
            'desc'          => "AppDevs Software Solutions is a most rapidly growing, innovative IT services & Consulting company, Providing consulting, Development & maintenance covering information technology, System integration & custom applications development.",
            'tags'          => ['appdevs', 'software solutions'],
            'last_edit_by'  => 1,
        ];

        SetupSeo::firstOrCreate($data);
    }
}
