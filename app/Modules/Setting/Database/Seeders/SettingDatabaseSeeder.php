<?php

namespace App\Modules\Setting\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Setting\Models\Setting;
use App\Modules\Payment\Models\Currency;


class SettingDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Setting::setMany([
            'active_theme' => 'AirdgeReaders',
            'supported_locales' => ['en'],
            'default_locale' => 'en',
            'default_timezone' => 'UTC',
            'auto_approve_user' => '1',
            'cookie_bar_enabled' => '1',
            'enable_resource_report' => true,
            'enable_registrations' => true,
            'enable_reviews' => true,
            'auto_approve_reviews' => true,
            'cynoebook_copyright_text' => 'Copyright © {{ site_name }} {{ year }}. All rights reserved.',
            'allowed_file_types' => ['pdf','epub','docx','doc','txt','mp3','wav'],

            'supported_currencies' => ['NGN','USD'],
            'default_currency' => 'NGN',
            'user_currency' => null,

            'ngn_rate' => Currency::whereCode('NGN')->first()->rate,
            'ranc_rate' => Currency::whereCode('RANC')->first()->rate,
            'usd_rate' => Currency::whereCode('USD')->first()->rate,

            'tawk_widget' => '',
            'mailchamp_settings' => '',

            'ranc_per_onread' => 50,
            'resource_owner_percent_onread' => 0.1,
            'resource_owner_percent_ondownload' => 0.9,
            'resource_owner_percent_onpurchase' => 0.9,
            'readprojecttopics_user_id' => 1,



        ]);
    }
}

