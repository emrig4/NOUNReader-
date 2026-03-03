<?php

namespace App\Modules\Subscription\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Modules\Subscription\Models\Pricing;


class PricingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('pricings')->truncate();

        Pricing::create([
            'title' => 'Basic',
            'slug' => 'basic',
            'description' => '',
            'plan_id' => 'PLN_76ak5a8v3v0ky0g',
            'note' => '',
            'amount' => 482, //ranc per month - 1 dollar - 100 Ranc (ranc = cent in dollar)
            'features' =>'Upto 10 Reads, 5 Access to Downloads, 20 Plagiarism' ,
        ]);


        Pricing::create([
            'title' => 'Standard',
            'slug' => 'standard',
            'description' => '',
            'plan_id' => 'PLN_76ak5a8v3v0ky0g',
            'note' => '',
            'amount' => 843, //ranc per month - 1 dollar - 100 Ranc (ranc = cent in dollar)
            'features' => ' Upto 25 Reads, 15 Access to Downloads, 50 Plagiarism',
        ]);


        Pricing::create([
            'title' => 'Pro',
            'slug' => 'pro',
            'description' => '',
            'plan_id' => 'PLN_76ak5a8v3v0ky0g',
            'note' => '',
            'amount' => 1204, //readprojecttopics credit (ranc) per month - 1 dollar - 100 Ranc (ranc = cent in dollar)
            'features' => 'Upto 50 Reads, 25 Access to Downloads, 300 Plagiarism',
        ]);
    }
}

