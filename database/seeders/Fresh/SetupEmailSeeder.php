<?php

namespace Database\Seeders\Fresh;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SetupEmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $env_modify_keys = [
            "MAIL_MAILER"       => "",
            "MAIL_HOST"         => "",
            "MAIL_PORT"         => "",
            "MAIL_USERNAME"     => "",
            "MAIL_PASSWORD"     => "",
            "MAIL_ENCRYPTION"   => "",
            "MAIL_FROM_ADDRESS" => "",
            "MAIL_FROM_NAME"    => "",
        ];

        modifyEnv($env_modify_keys);
    }
}
