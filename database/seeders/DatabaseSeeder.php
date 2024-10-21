<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Database\Seeders\Admin\AddCoinSeeder;
use Database\Seeders\Admin\AdminHasRoleSeeder;
use Illuminate\Database\Seeder;
use Database\Seeders\Admin\AdminSeeder;
use Database\Seeders\Admin\CurrencySeeder;
use Database\Seeders\Admin\SetupKycSeeder;
use Database\Seeders\Admin\SetupSeoSeeder;
use Database\Seeders\Admin\ExtensionSeeder;
use Database\Seeders\Fresh\ExtensionSeeder as FreshExtensionSeeder;
use Database\Seeders\Admin\AppSettingsSeeder;
use Database\Seeders\Admin\SiteSectionsSeeder;
use Database\Seeders\Admin\BasicSettingsSeeder;
use Database\Seeders\Admin\BlogSeeder;
use Database\Seeders\Admin\BlogSeeder as FreshBlogSeeder;
use Database\Seeders\Admin\CategorySeeder;
use Database\Seeders\Admin\CategorySeeder as FreshCategorySeeder;
use Database\Seeders\Fresh\BasicSettingsSeeder as FreshBasicSettingsSeeder;
use Database\Seeders\Fresh\PaymentGatewaySeeder as FreshPaymentGatewaySeeder;
use Database\Seeders\Admin\PaymentGatewaySeeder;
use Database\Seeders\Admin\RoleSeeder;
use Database\Seeders\Admin\SetupEmailSeeder;
use Database\Seeders\Fresh\SetupEmailSeeder as FreshSetupEmailSeeder;
use Database\Seeders\Admin\SetupPageSeeder;
use Database\Seeders\Admin\TopUpGameSeeder;
use Database\Seeders\Admin\TopUpGameSeeder as FreshTopUpGameSeeder;
use Database\Seeders\Admin\TransactionSettingSeeder;
use Database\Seeders\Admin\UsefullLinkSeeder;
use Database\Seeders\Admin\SiteCookieSeeder;
use Database\Seeders\User\UserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // Demo
        // $this->call([
        //     AdminSeeder::class,
        //     RoleSeeder::class,
        //     TransactionSettingSeeder::class,
        //     CurrencySeeder::class,
        //     BasicSettingsSeeder::class,
        //     SetupSeoSeeder::class,
        //     AppSettingsSeeder::class,
        //     SiteSectionsSeeder::class,
        //     SetupKycSeeder::class,
        //     ExtensionSeeder::class,
        //     AdminHasRoleSeeder::class,
        //     UserSeeder::class,
        //     SetupPageSeeder::class,
        //     PaymentGatewaySeeder::class,
        //     SetupEmailSeeder::class,
        //     UsefullLinkSeeder::class,
        //     LanguageSeeder::class,
        //     TopUpGameSeeder::class,
        //     CategorySeeder::class,
        //     BlogSeeder::class,
        //     SiteCookieSeeder::class
        // ]);

        // Fresh
        $this->call([
            AdminSeeder::class,
            RoleSeeder::class,
            TransactionSettingSeeder::class,
            CurrencySeeder::class,
            FreshBasicSettingsSeeder::class,
            SetupSeoSeeder::class,
            AppSettingsSeeder::class,
            SiteSectionsSeeder::class,
            SetupKycSeeder::class,
            FreshExtensionSeeder::class,
            AdminHasRoleSeeder::class,
            SetupPageSeeder::class,
            PaymentGatewaySeeder::class,
            UsefullLinkSeeder::class,
            SetupEmailSeeder::class,
            LanguageSeeder::class,
            FreshTopUpGameSeeder::class,
            CategorySeeder::class,
            BlogSeeder::class,
            SiteCookieSeeder::class
        ]);
    }
}
