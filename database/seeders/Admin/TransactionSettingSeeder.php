<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\TransactionSetting;
use Illuminate\Database\Seeder;

class TransactionSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transaction_settings = array(
            array('id' => '1', 'admin_id' => '1', 'slug' => 'topup', 'title' => 'Game Top Up Charges', 'fixed_charge' => '1.00', 'percent_charge' => '1.00', 'min_limit' => '0.00', 'max_limit' => '50000.00', 'monthly_limit' => '50000.00', 'daily_limit' => '5000.00', 'status' => '1', 'created_at' => NULL, 'updated_at' => '2023-06-17 23:18:11')
        );

        TransactionSetting::insert($transaction_settings);
    }
}
