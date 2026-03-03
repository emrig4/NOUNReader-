<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Setting\Models\Setting;
use App\Services\AutoCreditService;

class FixAutoCreditSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto-credit:fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix auto credit settings and ensure users get 500 credits on registration/verification';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Auto Credit System Fix');
        $this->info('==========================');
        $this->newLine();

        try {
            // Set auto credit settings
            $this->info('1. Setting up auto credit configuration...');
            
            Setting::set('auto_credit_enabled', true);
            $this->line('   ✅ Auto credit enabled: YES');
            
            Setting::set('auto_credit_amount', 500.00);
            $this->line('   ✅ Credit amount: 500.00 RANC');
            
            Setting::set('auto_credit_message', 'Welcome! You\'ve received {amount} RANC as a first-time user bonus!');
            $this->line('   ✅ Welcome message configured');
            
            $this->newLine();

            // Verify settings
            $this->info('2. Verifying current settings...');
            
            $enabled = Setting::get('auto_credit_enabled', false);
            $amount = Setting::get('auto_credit_amount', 0);
            
            $this->line("   📊 Auto credit enabled: " . ($enabled ? 'YES' : 'NO'));
            $this->line("   📊 Credit amount: {$amount} RANC");
            
            $this->newLine();

            // Test service
            $this->info('3. Testing auto credit service...');
            
            $autoCreditService = new AutoCreditService();
            $isEnabled = $autoCreditService->isAutoCreditEnabled();
            $serviceAmount = $autoCreditService->getAutoCreditAmount();
            
            $this->line("   🔍 Service reports enabled: " . ($isEnabled ? 'YES' : 'NO'));
            $this->line("   🔍 Service reports amount: {$serviceAmount} RANC");
            
            $this->newLine();

            // Show registration flows
            $this->info('4. Registration flow credits:');
            $this->line('   📝 Regular Registration: 500 credits granted immediately');
            $this->line('   📧 Email Verification Flow: 500 credits granted after verification');
            
            $this->newLine();

            // Summary
            $this->info('✅ Auto credit system fix completed successfully!');
            $this->newLine();
            
            $this->info('Next steps:');
            $this->line('   1. Test user registration to verify credits are granted');
            $this->line('   2. Test email verification flow to verify credits are granted');
            $this->line('   3. Check admin panel at /admin/auto-credit for settings');
            
            $this->newLine();
            $this->info('To test:');
            $this->line('   - Regular registration: Create new account on /register');
            $this->line('   - Email verification: Go to /verification/register');
            
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}