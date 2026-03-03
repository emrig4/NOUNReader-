<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class EmailVerificationTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email verification functionality';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🧪 Testing Email Verification System...');
        
        try {
            // Test 1: Check if User model has email verification fields
            $this->line('📋 Test 1: Checking User model fields...');
            $user = User::first();
            if (!$user) {
                $this->error('❌ No users found in database. Please create a test user first.');
                return 1;
            }
            
            $this->info("✅ User found: {$user->name} ({$user->email})");
            
            // Test 2: Check email verification status
            $this->line('📧 Test 2: Checking email verification status...');
            $isVerified = $user->hasVerifiedEmail();
            $this->info("Email verified: " . ($isVerified ? "✅ Yes" : "❌ No"));
            
            // Test 3: Check helper functions
            $this->line('🔧 Test 3: Testing helper functions...');
            
            if (function_exists('is_email_verified')) {
                $helperResult = is_email_verified($user);
                $this->info("✅ is_email_verified() helper working: " . ($helperResult ? "Verified" : "Not Verified"));
            } else {
                $this->error("❌ is_email_verified() helper function not found");
            }
            
            // Test 4: Check routes
            $this->line('🛣️ Test 4: Testing email verification routes...');
            $testUrl = route('verify.email.form');
            $this->info("✅ Email verification form route: {$testUrl}");
            
            // Test 5: Check email template
            $this->line('📧 Test 5: Checking email template...');
            $templatePath = resource_path('views/emails/verify-email.blade.php');
            if (file_exists($templatePath)) {
                $this->info("✅ Email template found: {$templatePath}");
            } else {
                $this->error("❌ Email template missing: {$templatePath}");
            }
            
            // Test 6: Check Mailable class
            $this->line('📤 Test 6: Testing Mailable class...');
            if (class_exists('\App\Mail\VerifyEmail')) {
                $this->info("✅ VerifyEmail Mailable class exists");
            } else {
                $this->error("❌ VerifyEmail Mailable class missing");
            }
            
            // Test 7: Check config
            $this->line('⚙️ Test 7: Checking configuration...');
            $config = config('email-verification');
            if ($config) {
                $this->info("✅ Email verification config loaded");
                $this->line("   - Enabled: " . ($config['enabled'] ? "Yes" : "No"));
                $this->line("   - Required: " . ($config['require_verification'] ? "Yes" : "No"));
            } else {
                $this->error("❌ Email verification config missing");
            }
            
            $this->info('🎉 Email verification system test completed!');
            
        } catch (\Exception $e) {
            $this->error('❌ Error during testing: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}