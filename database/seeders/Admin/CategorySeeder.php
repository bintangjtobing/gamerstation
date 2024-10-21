<?php

namespace Database\Seeders\Admin;

use App\Models\BlogCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $blog_categories = array(
            array('id' => '1', 'admin_id' => '1', 'name' => 'Money', 'data' => '{"language":{"en":{"name":"Money"},"es":{"name":null},"ar":{"name":null}}}', 'slug' => 'money', 'status' => '1', 'created_at' => '2023-09-06 09:54:25', 'updated_at' => '2024-03-27 10:22:54'),
            array('id' => '3', 'admin_id' => '1', 'name' => 'Game', 'data' => '{"language":{"en":{"name":"Game"},"es":{"name":null},"ar":{"name":null}}}', 'slug' => 'game', 'status' => '1', 'created_at' => '2023-09-07 08:27:02', 'updated_at' => '2024-03-27 10:22:54'),

        );

        BlogCategory::insert($blog_categories);
    }
}
