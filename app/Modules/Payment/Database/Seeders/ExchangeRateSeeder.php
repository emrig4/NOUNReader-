<?php
namespace App\Modules\Payment\Database\Seeders;

use Illuminate\Database\Seeder;
// use App\Modules\Payment\Helpers\ExchangeRateHelper; // Commented out

class ExchangeRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ExchangeRateHelper::getRates(); // Commented out to skip API call
        echo "ExchangeRateSeeder skipped for local development.\n";
    }
}
