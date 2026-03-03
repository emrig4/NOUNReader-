<?php

/**
 * Change Resource Pricing Script
 * 
 * Purpose: Update all resource download prices in the database
 * Usage: php change_pricing.php
 * 
 * Features:
 * - Batch processing for large datasets (50,000+ resources)
 * - Dry run mode to preview changes before applying
 * - Progress logging
 * - Rollback capability with backup
 * 
 * @author ReadProjectTopics
 * @version 1.0.0
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Modules\Resource\Models\Resource;

class ChangePricing
{
    /**
     * Configuration
     */
    private $oldPrice = 4000;
    private $newPrice = 2000;
    private $batchSize = 1000; // Process 1000 records at a time
    private $dryRun = false; // Set to true to preview without changes
    
    /**
     * Colors for console output
     */
    private $colors = [
        'green' => "\033[32m",
        'yellow' => "\033[33m",
        'red' => "\033[31m",
        'blue' => "\033[34m",
        'reset' => "\033[0m",
    ];
    
    /**
     * Initialize and run the pricing change
     */
    public function run($args = [])
    {
        $this->printHeader();
        
        // Check for dry run flag
        if (in_array('--dry-run', $args) || in_array('-d', $args)) {
            $this->dryRun = true;
            $this->printMessage("DRY RUN MODE - No changes will be made", 'yellow');
        }
        
        // Check for custom prices
        if (isset($args[0]) && is_numeric($args[0])) {
            $this->newPrice = (int)$args[0];
        }
        if (isset($args[1]) && is_numeric($args[1])) {
            $this->oldPrice = (int)$args[1];
        }
        
        $this->printInfo("Configuration:");
        $this->printInfo("  Old Price: {$this->oldPrice} credits");
        $this->printInfo("  New Price: {$this->newPrice} credits");
        $this->printInfo("  Batch Size: {$this->batchSize}");
        $this->printInfo("  Mode: " . ($this->dryRun ? 'DRY RUN (preview only)' : 'LIVE UPDATE'));
        echo "\n";
        
        // Step 1: Count resources to update
        $count = $this->countResources();
        if ($count === 0) {
            $this->printMessage("No resources found with price = {$this->oldPrice}", 'yellow');
            return;
        }
        
        $this->printMessage("Found {$count} resources to update", 'green');
        echo "\n";
        
        // Step 2: Confirm before proceeding
        if (!$this->dryRun) {
            if (!$this->confirmAction()) {
                $this->printMessage("Operation cancelled", 'yellow');
                return;
            }
        }
        
        // Step 3: Create backup before changes
        if (!$this->dryRun) {
            $backupFile = $this->createBackup();
            $this->printInfo("Backup created: {$backupFile}");
        }
        
        // Step 4: Update prices
        $this->updatePrices($count);
        
        // Step 5: Summary
        $this->printSummary();
    }
    
    /**
     * Print script header
     */
    private function printHeader()
    {
        echo "\n";
        echo $this->colors['blue'];
        echo "╔════════════════════════════════════════════════════════════════════╗\n";
        echo "║           CHANGE RESOURCE PRICING SCRIPT v1.0.0                    ║\n";
        echo "║                                                                    ║\n";
        echo "║  Change download prices from old value to new value               ║\n";
        echo "║                                                                    ║\n";
        echo "║  Usage: php change_pricing.php [new_price] [old_price]            ║\n";
        echo "║  Example: php change_pricing.php 2000 4000                        ║\n";
        echo "║  Dry Run: php change_pricing.php --dry-run                        ║\n";
        echo "╚════════════════════════════════════════════════════════════════════╝\n";
        echo $this->colors['reset'];
        echo "\n";
    }
    
    /**
     * Count resources with the old price
     */
    private function countResources()
    {
        return Resource::where('price', $this->oldPrice)->count();
    }
    
    /**
     * Confirm action with user
     */
    private function confirmAction()
    {
        echo $this->colors['yellow'];
        echo "⚠️  WARNING: This will update {$this->countResources()} resources in the database.\n";
        echo "   Are you sure you want to continue? [y/N]: ";
        echo $this->colors['reset'];
        
        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        fclose($handle);
        
        return strtolower(trim($line)) === 'y';
    }
    
    /**
     * Create a backup of the resources table
     */
    private function createBackup()
    {
        $timestamp = date('Y_m_d_H_i_s');
        $filename = storage_path("backups/prices_backup_{$timestamp}.sql");
        
        // Ensure backup directory exists
        if (!is_dir(storage_path('backups'))) {
            mkdir(storage_path('backups'), 0755, true);
        }
        
        // Export only price columns for resources with old price
        $prices = Resource::where('price', $this->oldPrice)
            ->select('id', 'price')
            ->get();
        
        $sql = "-- Price Backup created at {$timestamp}\n";
        $sql .= "-- Resources with old price = {$this->oldPrice}\n\n";
        
        foreach ($prices as $resource) {
            $sql .= "UPDATE resources SET price = {$resource->price} WHERE id = {$resource->id};\n";
        }
        
        file_put_contents($filename, $sql);
        
        return $filename;
    }
    
    /**
     * Update prices in batches
     */
    private function updatePrices($total)
    {
        $this->printMessage("Starting price update...", 'blue');
        echo "\n";
        
        $updated = 0;
        $errors = 0;
        $startTime = microtime(true);
        
        $progressBar = $this->createProgressBar($total);
        
        while (true) {
            // Get batch of resources
            $resources = Resource::where('price', $this->oldPrice)
                ->select('id', 'title', 'price')
                ->limit($this->batchSize)
                ->offset($updated)
                ->get();
            
            if ($resources->isEmpty()) {
                break;
            }
            
            foreach ($resources as $resource) {
                if (!$this->dryRun) {
                    try {
                        $resource->price = $this->newPrice;
                        $resource->save();
                        $updated++;
                    } catch (\Exception $e) {
                        $errors++;
                        $this->printMessage("Error updating resource ID {$resource->id}: " . $e->getMessage(), 'red');
                    }
                } else {
                    $updated++;
                }
                
                // Update progress bar
                $progressBar['current'] = $updated;
                $this->updateProgressBar($progressBar);
            }
            
            // Clear memory
            if (!$this->dryRun) {
                DB::purge('mysql');
            }
        }
        
        // Complete progress bar
        $progressBar['current'] = $total;
        $this->updateProgressBar($progressBar, true);
        
        $endTime = microtime(true);
        $duration = round($endTime - $startTime, 2);
        
        echo "\n\n";
        $this->printMessage("Update completed in {$duration} seconds", 'green');
        
        $this->updatedCount = $updated;
        $this->errorCount = $errors;
        $this->duration = $duration;
    }
    
    /**
     * Create progress bar structure
     */
    private function createProgressBar($total)
    {
        return [
            'total' => $total,
            'current' => 0,
            'width' => 50,
            'start' => 0,
        ];
    }
    
    /**
     * Update and display progress bar
     */
    private function updateProgressBar(&$progressBar, $complete = false)
    {
        $percent = $progressBar['total'] > 0 
            ? ($progressBar['current'] / $progressBar['total']) * 100 
            : 100;
        
        $filled = (int)($progressBar['width'] * ($percent / 100));
        $empty = $progressBar['width'] - $filled;
        
        $bar = str_repeat('█', $filled) . str_repeat('░', $empty);
        
        echo "\r" . str_repeat(' ', 80) . "\r"; // Clear line
        echo "\r[{$bar}] {$progressBar['current']}/{$progressBar['total']} (" . round($percent, 1) . "%)";
        
        if ($complete) {
            echo "\n";
        }
        
        flush();
    }
    
    /**
     * Print final summary
     */
    private function printSummary()
    {
        echo "\n";
        echo $this->colors['blue'];
        echo "╔════════════════════════════════════════════════════════════════════╗\n";
        echo "║                        SUMMARY                                     ║\n";
        echo "╠════════════════════════════════════════════════════════════════════╣\n";
        echo $this->colors['reset'];
        
        $mode = $this->dryRun ? 'DRY RUN' : 'LIVE UPDATE';
        echo $this->colors['green'];
        echo "║  Mode: {$mode}\n";
        echo "║  Old Price: {$this->oldPrice} credits\n";
        echo "║  New Price: {$this->newPrice} credits\n";
        echo "║  Resources Updated: " . ($this->updatedCount ?? 0) . "\n";
        echo "║  Errors: " . ($this->errorCount ?? 0) . "\n";
        echo "║  Duration: " . ($this->duration ?? 0) . " seconds\n";
        
        echo $this->colors['blue'];
        echo "╚════════════════════════════════════════════════════════════════════╝\n";
        echo $this->colors['reset'];
        echo "\n";
    }
    
    /**
     * Print info message
     */
    private function printInfo($message)
    {
        echo $this->colors['blue'];
        echo "ℹ️  {$message}\n";
        echo $this->colors['reset'];
    }
    
    /**
     * Print colored message
     */
    private function printMessage($message, $color = 'reset')
    {
        echo $this->colors[$color] ?? $this->colors['reset'];
        echo "{$message}\n";
        echo $this->colors['reset'];
    }
}

// Run the script
$pricing = new ChangePricing();
$pricing->run($argv);
