<?php

/**
 * Auto Credit Diagnostic Script
 * Run this to check the current status of auto credit system
 * 
 * Usage: php debug_auto_credit.php
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Modules\Setting\Models\Setting;
use App\Services\AutoCreditService;
use App\Models\User;

echo "🔍 AUTO CREDIT SYSTEM DIAGNOSTIC\n";
echo "================================\n\n";

// Check auto credit settings
echo "1. AUTO CREDIT SETTINGS:\n";
echo "------------------------\n";

$autoCreditEnabled = Setting::get('auto_credit_enabled', false);
$autoCreditAmount = Setting::get('auto_credit_amount', 0);
$autoCreditMessage = Setting::get('auto_credit_message', '');

echo "Auto Credit Enabled: " . ($autoCreditEnabled ? '✅ YES' : '❌ NO') . "\n";
echo "Credit Amount: " . $autoCreditAmount . " RANC\n";
echo "Welcome Message: " . $autoCreditMessage . "\n\n";

if (!$autoCreditEnabled || $autoCreditAmount <= 0) {
    echo "⚠️  ISSUE FOUND: Auto credit is disabled or amount is 0!\n";
    echo "Fix: Run the seeder to enable auto credit settings.\n\n";
}

// Check recent users and their credit status
echo "2. RECENT USERS CREDIT STATUS:\n";
echo "-------------------------------\n";

$recentUsers = User::orderBy('created_at', 'desc')->limit(5)->get();

if ($recentUsers->isEmpty()) {
    echo "No users found in the system.\n\n";
} else {
    foreach ($recentUsers as $user) {
        $hasAutoCredit = \DB::table('wallet_transactions')
            ->where('user_id', $user->id)
            ->where('description', 'LIKE', '%First-time user auto credit%')
            ->exists();
            
        $walletBalance = \DB::table('subscription_wallets')
            ->where('user_id', $user->id)
            ->value('ranc') ?? 0;
            
        echo "User ID: {$user->id}\n";
        echo "Email: {$user->email}\n";
        echo "Created: {$user->created_at}\n";
        echo "Auto Credit Received: " . ($hasAutoCredit ? '✅ YES' : '❌ NO') . "\n";
        echo "Wallet Balance: {$walletBalance} RANC\n";
        echo "---\n";
    }
    echo "\n";
}

// Test auto credit service
echo "3. AUTO CREDIT SERVICE TEST:\n";
echo "----------------------------\n";

try {
    $autoCreditService = new AutoCreditService();
    
    $isEnabled = $autoCreditService->isAutoCreditEnabled();
    $amount = $autoCreditService->getAutoCreditAmount();
    
    echo "Service Reports Enabled: " . ($isEnabled ? '✅ YES' : '❌ NO') . "\n";
    echo "Service Reports Amount: {$amount} RANC\n\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n\n";
}

// Provide fix recommendations
echo "4. FIX RECOMMENDATIONS:\n";
echo "-----------------------\n";

if (!$autoCreditEnabled) {
    echo "🔧 To enable auto credit, run this command:\n";
    echo "   php artisan db:seed --class=AutoCreditSettingsSeeder\n\n";
}

echo "5. TESTING INSTRUCTIONS:\n";
echo "------------------------\n";
echo "To test the auto credit system:\n\n";

echo "A) For Regular Registration:\n";
echo "   1. Go to /register\n";
echo "   2. Fill the registration form\n";
echo "   3. Check if user receives 500 credits\n\n";

echo "B) For Email Verification Flow:\n";
echo "   1. Go to /verification/register\n";
echo "   2. Enter email and submit\n";
echo "   3. Check email for verification code\n";
echo "   4. Enter code and set password\n";
echo "   5. Check if user receives 500 credits\n\n";

echo "6. ADMIN CHECK:\n";
echo "---------------\n";
echo "As admin, you can check auto credit settings at:\n";
echo "   /admin/auto-credit\n\n";

echo "Diagnostic complete! ✅\n";