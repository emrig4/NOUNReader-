<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Setting\Models\Setting;

class AutoCreditSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Set default auto credit settings
        Setting::setMany([
            'auto_credit_enabled' => true,        // Enable auto credit for first-time users
            'auto_credit_amount' => 190.00,       // Default credit amount (190 RANC)
            'auto_credit_message' => 'Welcome! You\'ve received {amount} RANC as a first-time user bonus!',
        ]);

        $this->command->info('✅ Auto credit settings have been configured:');
        $this->command->info('   - Auto Credit Enabled: ' . (Setting::get('auto_credit_enabled') ? 'Yes' : 'No'));
        $this->command->info('   - Credit Amount: ' . Setting::get('auto_credit_amount') . ' RANC');
        $this->command->info('   - Welcome Message: ' . Setting::get('auto_credit_message'));
    }
}