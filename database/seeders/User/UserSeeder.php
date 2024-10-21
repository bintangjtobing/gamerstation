<?php

namespace Database\Seeders\User;

use App\Models\User;
use App\Models\UserWallet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [

            [
                'firstname'         => "Test",
                'lastname'          => "User",
                'email'             => "user@appdevs.net",
                'username'          => "testuser",
                'status'            => true,
                'password'          => Hash::make("appdevs"),
                'email_verified'    => true,
                'sms_verified'      => true,
                'created_at'        => now(),
            ],
        ];

        User::insert($data);

        $user_wallets = array(
            array('id' => '1', 'user_id' => '1', 'currency_id' => '1', 'balance' => '0.00000000', 'status' => '1', 'created_at' => '2023-08-21 05:07:50', 'updated_at' => NULL)
        );

        UserWallet::insert($user_wallets);
    }
}
