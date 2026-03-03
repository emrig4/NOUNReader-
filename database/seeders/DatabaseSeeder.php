<?php

namespace Database\Seeders;
use App\Modules\Resource\Database\Seeders\ResourceFieldSeeder;
use App\Modules\Resource\Database\Seeders\ResourceSubFieldSeeder;
use App\Modules\Resource\Database\Seeders\ResourceTypeSeeder;
use App\Modules\Payment\Database\Seeders\CurrencySeeder;
use App\Modules\Payment\Database\Seeders\ExchangeRateSeeder;
use App\Modules\Subscription\Database\Seeders\PricingTableSeeder;
use App\Modules\Setting\Database\Seeders\SettingDatabaseSeeder;





use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(ResourceFieldSeeder::class);
        $this->call(ResourceSubFieldSeeder::class);
        $this->call(ResourceTypeSeeder::class);

        $this->call(CurrencySeeder::class); //currency
        $this->call(ExchangeRateSeeder::class); //exchangerate

        $this->call(PricingTableSeeder::class); //pricing
        $this->call(SettingDatabaseSeeder::class); //settings

    }
}
