<?php

/**
 * Complete Cleanup - Publications AND Test Users
 * Keeps only the admin user (emrig4@gmail.com)
 * 
 * Run: php complete-cleanup.php
 */

echo "🧹 COMPLETE SITE CLEANUP\n";
echo "========================\n";
echo "Admin email: emrig4@gmail.com\n\n";

$adminEmail = 'emrig4@gmail.com';

// Disable foreign key checks
\DB::statement('SET FOREIGN_KEY_CHECKS=0');

try {
    // Verify admin exists
    $adminExists = \App\Models\User::where('email', $adminEmail)->exists();
    if (!$adminExists) {
        echo "⚠️  Admin user ({$adminEmail}) not found!\n";
        echo "Aborting.\n";
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');
        exit(1);
    }
    
    // ===== CLEAR PUBLICATIONS =====
    echo "📄 Clearing publications...\n";
    
    $tables = [
        'resource_reviews',
        'resource_reports',
        'resource_authors',
        'purchased_resources',
        'entity_files',
        'files',
        'resources',
    ];
    
    foreach ($tables as $table) {
        $count = \DB::table($table)->count();
        \DB::table($table)->delete();
        echo "  ✓ {$table}: deleted {$count} records\n";
    }
    
    // ===== DELETE TEST USERS =====
    echo "\n👥 Deleting test users...\n";
    
    $totalUsers = \App\Models\User::count();
    $deletedUsers = \App\Models\User::where('email', '!=', $adminEmail)->delete();
    
    echo "  ✓ Deleted {$deletedUsers} user(s)\n";
    echo "  ✓ Kept admin: {$adminEmail}\n";
    
    // ===== FINAL VERIFICATION =====
    echo "\n📊 FINAL VERIFICATION:\n";
    echo "------------------------\n\n";
    
    echo "👤 USERS:\n";
    \App\Models\User::all()->each(function($user) use ($adminEmail) {
        $role = ($user->email === $adminEmail) ? '👑 ADMIN' : '👤 USER';
        echo "  {$role}: {$user->email}\n";
    });
    
    echo "\n📄 PUBLICATIONS:\n";
    echo "  - Resources: " . \DB::table('resources')->count() . "\n";
    echo "  - Authors: " . \DB::table('resource_authors')->count() . "\n";
    echo "  - Files: " . \DB::table('files')->count() . "\n";
    
    echo "\n✅ COMPLETE CLEANUP SUCCESSFUL!\n";
    
} catch (\Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

// Re-enable foreign key checks
\DB::statement('SET FOREIGN_KEY_CHECKS=1');