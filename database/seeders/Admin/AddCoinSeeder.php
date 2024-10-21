<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\AddCoin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddCoinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $add_coins = array(
            array('id' => '2', 'coin' => '10', 'price' => '10', 'created_at' => '2023-06-21 04:48:50', 'updated_at' => '2023-06-23 17:50:52'),
            array('id' => '3', 'coin' => '50', 'price' => '50', 'created_at' => '2023-06-23 17:51:07', 'updated_at' => '2023-06-23 17:51:07'),
            array('id' => '4', 'coin' => '100', 'price' => '100', 'created_at' => '2023-06-23 17:51:20', 'updated_at' => '2023-06-23 17:51:20')
        );

        AddCoin::insert($add_coins);
    }
}
