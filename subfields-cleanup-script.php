<?php
/**
 * SUBFIELDS DATABASE CLEANUP - "THINKER" APPROACH
 * 
 * This script performs a complete database-level cleanup of subfields.
 * Use this if the admin interface approach fails or for emergency cleanup.
 * 
 * WARNING: This will permanently delete ALL subfields and their references!
 */

// Set up the application environment
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Database facade
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

echo "🧹 SUBFIELDS DATABASE CLEANUP SCRIPT\n";
echo "=====================================\n\n";

// Start transaction for safety
DB::beginTransaction();

try {
    echo "📊 CURRENT STATUS:\n";
    
    // Check current subfields count
    $subfieldCount = DB::table('resource_sub_fields')->count();
    echo "- Current subfields: {$subfieldCount}\n";
    
    // Check resources with subfield references
    $resourcesWithSubfields = DB::table('resources')
        ->whereNotNull('sub_fields')
        ->where('sub_fields', '!=', '')
        ->where('sub_fields', '!=', '[]')
        ->count();
    echo "- Resources with subfield references: {$resourcesWithSubfields}\n";
    
    // Check unique subfield slugs in use
    $resources = DB::table('resources')
        ->whereNotNull('sub_fields')
        ->where('sub_fields', '!=', '')
        ->where('sub_fields', '!=', '[]')
        ->get(['sub_fields']);
    
    $usedSubfields = [];
    foreach ($resources as $resource) {
        if ($resource->sub_fields) {
            $slugs = explode(',', $resource->sub_fields);
            foreach ($slugs as $slug) {
                $slug = trim($slug);
                if (!empty($slug)) {
                    $usedSubfields[] = $slug;
                }
            }
        }
    }
    
    $uniqueUsedSubfields = array_unique($usedSubfields);
    echo "- Unique subfield slugs still in use: " . count($uniqueUsedSubfields) . "\n";
    
    echo "\n🔄 STARTING CLEANUP...\n\n";
    
    // Step 1: Clear subfield references from resources
    echo "Step 1: Clearing subfield references from resources...\n";
    
    $affectedResources = DB::table('resources')
        ->whereNotNull('sub_fields')
        ->where('sub_fields', '!=', '')
        ->update(['sub_fields' => null]);
    
    echo "✓ Updated {$affectedResources} resources - cleared subfield references\n";
    
    // Step 2: Delete all subfields
    echo "\nStep 2: Deleting all subfields...\n";
    
    // Temporarily disable foreign key checks for this table
    DB::statement('SET FOREIGN_KEY_CHECKS=0');
    
    // Delete all subfields
    $deletedSubfields = DB::table('resource_sub_fields')->delete();
    
    // Re-enable foreign key checks
    DB::statement('SET FOREIGN_KEY_CHECKS=1');
    
    echo "✓ Deleted {$deletedSubfields} subfields\n";
    
    // Step 3: Verify cleanup
    echo "\nStep 3: Verifying cleanup...\n";
    
    $remainingSubfields = DB::table('resource_sub_fields')->count();
    $remainingResourceSubfields = DB::table('resources')
        ->whereNotNull('sub_fields')
        ->where('sub_fields', '!=', '')
        ->count();
    
    echo "✓ Remaining subfields: {$remainingSubfields}\n";
    echo "✓ Resources with subfield data: {$remainingResourceSubfields}\n";
    
    // Commit the transaction
    DB::commit();
    
    echo "\n🎉 CLEANUP COMPLETED SUCCESSFULLY!\n";
    echo "====================================\n";
    echo "✅ All subfields have been permanently deleted\n";
    echo "✅ All subfield references have been cleared from resources\n";
    echo "✅ Database is now clean of subfield data\n";
    
    // Log the operation
    Log::info("Database cleanup: All subfields deleted", [
        'deleted_subfields' => $deletedSubfields,
        'affected_resources' => $affectedResources,
        'cleaned_by' => 'cleanup_script',
        'timestamp' => now()
    ]);
    
    echo "\n💡 NOTE: You may want to run 'php artisan cache:clear' to clear any cached data.\n";
    
} catch (Exception $e) {
    // Rollback on error
    DB::rollback();
    
    echo "\n❌ ERROR OCCURRED!\n";
    echo "==================\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " (Line: " . $e->getLine() . ")\n";
    echo "\n🔄 Changes have been rolled back.\n";
    echo "No data was deleted.\n";
    
    // Log the error
    Log::error("Subfield cleanup failed", [
        'error' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'timestamp' => now()
    ]);
    
    exit(1);
}

echo "\n✨ Script completed successfully!\n";