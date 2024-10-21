<?php

namespace Database\Seeders;

use App\Models\Admin\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = array(
            array('id' => '4','name' => 'English','code' => 'en','status' => '1','last_edit_by' => '1','created_at' => '2023-06-17 10:35:32','updated_at' => NULL,'dir' => 'ltr'),
            array('id' => '5','name' => 'Spanish','code' => 'es','status' => '0','last_edit_by' => '1','created_at' => '2023-06-19 23:41:49','updated_at' => '2023-06-19 23:41:49','dir' => 'ltr'),
            array('id' => '6','name' => 'Arabic','code' => 'ar','status' => '0','last_edit_by' => '1','created_at' => '2023-10-25 11:17:00','updated_at' => '2023-10-28 09:23:58','dir' => 'rtl')
          );


        Language::insert($languages);
    }
}
