<?php

namespace App\Console\Commands;

use App\Modules\File\Models\File;
use App\Modules\Resource\Models\Resource;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ScanBrokenFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resources:scan-broken
                            {--output=console : Output mode: console, csv, json}
                            {--save= : Save results to file path}
                            {--dry-run : Show what would be checked without making changes}
                            {--batch=1000 : Number of resources to process per batch}
                            {--skip-s3 : Skip S3 content checks (faster but less accurate)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan all resources for broken/missing file links and generate report';

    /**
     * Storage disks to check
     */
    protected $disks = ['s3', 'local_rfiles', 'local'];

    /**
     * Results storage
     */
    protected $results = [
        'total_resources' => 0,
        'resources_with_files' => 0,
        'resources_without_files' => 0,
        'broken_files' => [],
        'missing_records' => [],
        'statistics' => []
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $output = $this->option('output');
        $savePath = $this->option('save');
        $dryRun = $this->option('dry-run');
        $batchSize = (int) $this->option('batch');

        $this->info('========================================');
        $this->info('Resource File Scanner');
        $this->info('========================================');
        $this->line('');
        $this->info("Scanning for broken/missing files...");
        $this->info("Output mode: {$output}");
        if ($savePath) {
            $this->info("Results will be saved to: {$savePath}");
        }
        if ($dryRun) {
            $this->warn('[DRY RUN] No changes will be made');
        }
        $this->line('');

        // Start scanning
        $this->scanResources($batchSize);

        // Display results
        $this->displayResults();

        // Save results if requested
        if ($savePath && !$dryRun) {
            $this->saveResults($savePath, $output);
        }

        // Generate fix queries
        $this->generateFixQueries();

        // Return success
        $this->line('');
        $this->info('Scan completed successfully!');
        return Command::SUCCESS;
    }

    /**
     * Scan all resources for broken files
     */
    protected function scanResources($batchSize)
    {
        $this->info('Step 1: Getting total resource count...');

        // Get total count of published resources
        $totalResources = Resource::where('is_published', 1)->count();
        $this->results['total_resources'] = $totalResources;
        $this->info("Total published resources: {$totalResources}");
        $this->line('');

        // Process resources in batches
        $this->info('Step 2: Scanning resources for broken files...');
        $this->line('');

        $progressBar = $this->output->createProgressBar($totalResources);
        $progressBar->start();

        $offset = 0;
        $processed = 0;

        while ($offset < $totalResources) {
            $resources = Resource::where('is_published', 1)
                ->select(['id', 'title', 'slug', 'user_id'])
                ->offset($offset)
                ->limit($batchSize)
                ->get();

            if ($resources->isEmpty()) {
                break;
            }

            foreach ($resources as $resource) {
                $this->checkResourceFile($resource);
                $processed++;
                $progressBar->advance();
            }

            $offset += $batchSize;
        }

        $progressBar->finish();
        $this->line('');
        $this->line('');

        // Calculate statistics
        $this->calculateStatistics();
    }

    /**
     * Check a single resource's file
     */
    protected function checkResourceFile($resource)
    {
        // Get the main_file via entity_files pivot table
        $mainFileRecord = DB::table('entity_files')
            ->where('entity_type', 'App\Modules\Resource\Models\Resource')
            ->where('entity_id', $resource->id)
            ->where('label', 'main_file')
            ->first();

        if (!$mainFileRecord) {
            // No file record found
            $this->results['resources_without_files']++;
            $this->results['missing_records'][] = [
                'resource_id' => $resource->id,
                'resource_title' => $resource->title,
                'resource_slug' => $resource->slug,
                'issue' => 'No file record found in entity_files table',
                'admin_url' => "/admin/resources/{$resource->slug}/edit"
            ];
            return;
        }

        // Get the actual file record
        $file = File::find($mainFileRecord->file_id);

        if (!$file) {
            // File record exists in entity_files but not in files table
            $this->results['broken_files'][] = [
                'resource_id' => $resource->id,
                'resource_title' => $resource->title,
                'resource_slug' => $resource->slug,
                'file_id' => $mainFileRecord->file_id,
                'issue' => 'File record missing from files table (orphan record)',
                'admin_url' => "/admin/resources/{$resource->slug}/edit",
                'fix_action' => 'Remove orphan record from entity_files and re-upload file'
            ];
            return;
        }

        // Check if file exists on disk/S3
        $fileExists = $this->checkFileExists($file);

        if (!$fileExists) {
            // File record exists but file is missing from storage
            $this->results['broken_files'][] = [
                'resource_id' => $resource->id,
                'resource_title' => $resource->title,
                'resource_slug' => $resource->slug,
                'file_id' => $file->id,
                'file_disk' => $file->disk,
                'file_path' => $file->path,
                'file_mime' => $file->mime,
                'file_size' => $file->size,
                'issue' => 'File missing from storage',
                'admin_url' => "/admin/resources/{$resource->slug}/edit",
                'fix_action' => 'Re-upload the file: ' . $file->path
            ];
            return;
        }

        // File is available
        $this->results['resources_with_files']++;
    }

    /**
     * Check if file exists on the specified disk
     */
    protected function checkFileExists($file)
    {
        try {
            $disk = Storage::disk($file->disk);

            // Check if file exists
            $exists = $disk->exists($file->path);

            if (!$exists) {
                return false;
            }

            // Additional check: try to get file contents (for S3, this verifies accessibility)
            if (in_array($file->disk, ['s3', 'local_rfiles'])) {
                $contents = $disk->get($file->path);
                return !empty($contents);
            }

            return $exists;

        } catch (\Exception $e) {
            Log::warning("Error checking file existence: " . $e->getMessage(), [
                'file_id' => $file->id,
                'disk' => $file->disk,
                'path' => $file->path
            ]);
            return false;
        }
    }

    /**
     * Calculate statistics
     */
    protected function calculateStatistics()
    {
        $total = $this->results['total_resources'];
        $withFiles = $this->results['resources_with_files'];
        $withoutFiles = $this->results['resources_without_files'];
        $brokenCount = count($this->results['broken_files']);
        $orphanCount = count($this->results['missing_records']);

        $this->results['statistics'] = [
            'total_resources' => $total,
            'resources_with_available_files' => $withFiles,
            'resources_with_missing_files' => $brokenCount,
            'resources_with_orphan_records' => $orphanCount,
            'resources_without_file_records' => $withoutFiles,
            'availability_percentage' => $total > 0 ? round(($withFiles / $total) * 100, 2) : 0,
            'broken_percentage' => $total > 0 ? round(($brokenCount / $total) * 100, 2) : 0,
        ];
    }

    /**
     * Display results in console
     */
    protected function displayResults()
    {
        $stats = $this->results['statistics'];

        $this->info('========================================');
        $this->info('SCAN RESULTS SUMMARY');
        $this->info('========================================');
        $this->line('');

        // Summary statistics
        $this->info('📊 Overall Statistics:');
        $this->line("   Total Resources Scanned: {$stats['total_resources']}");
        $this->line("   Files Available: {$stats['resources_with_available_files']}");
        $this->line("   Missing Files: {$stats['resources_with_missing_files']}");
        $this->line("   Orphan Records: {$stats['resources_with_orphan_records']}");
        $this->line("   No File Records: {$stats['resources_without_file_records']}");
        $this->line('');
        $this->info("   Availability Rate: {$stats['availability_percentage']}%");
        $this->line('');

        // Issues breakdown
        $this->info('📋 Issues Found:');
        $brokenCount = count($this->results['broken_files']);
        $orphanCount = count($this->results['missing_records']);

        if ($brokenCount > 0) {
            $this->warn("   ⚠️  {$brokenCount} resources have missing files on storage");
        } else {
            $this->info("   ✅ No missing files on storage");
        }

        if ($orphanCount > 0) {
            $this->warn("   ⚠️  {$orphanCount} resources have orphan file records");
        } else {
            $this->info("   ✅ No orphan file records");
        }

        $this->line('');

        // Show sample of broken files
        if ($brokenCount > 0) {
            $this->info('📝 Sample of Resources with Missing Files (first 10):');
            $this->line('');
            $this->table(
                ['ID', 'Title', 'Slug', 'File Path', 'Disk'],
                array_map(function($item) {
                    return [
                        $item['resource_id'],
                        substr($item['resource_title'], 0, 40) . (strlen($item['resource_title']) > 40 ? '...' : ''),
                        $item['resource_slug'],
                        substr($item['file_path'] ?? 'N/A', 0, 50) . (strlen($item['file_path'] ?? '') > 50 ? '...' : ''),
                        $item['file_disk'] ?? 'N/A'
                    ];
                }, array_slice($this->results['broken_files'], 0, 10))
            );
            $this->line('');
        }

        // Show sample of orphan records
        if ($orphanCount > 0) {
            $this->info('📝 Sample of Resources with Orphan Records (first 10):');
            $this->line('');
            $this->table(
                ['ID', 'Title', 'Slug', 'Issue'],
                array_map(function($item) {
                    return [
                        $item['resource_id'],
                        substr($item['resource_title'], 0, 40) . (strlen($item['resource_title']) > 40 ? '...' : ''),
                        $item['resource_slug'],
                        substr($item['issue'], 0, 50)
                    ];
                }, array_slice($this->results['missing_records'], 0, 10))
            );
            $this->line('');
        }
    }

    /**
     * Save results to file
     */
    protected function saveResults($savePath, $format)
    {
        try {
            $directory = dirname($savePath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            switch ($format) {
                case 'json':
                    file_put_contents($savePath, json_encode($this->results, JSON_PRETTY_PRINT));
                    break;

                case 'csv':
                    $this->saveCSV($savePath);
                    break;

                default:
                    // Console output only
                    return;
            }

            $this->info("✅ Results saved to: {$savePath}");

        } catch (\Exception $e) {
            $this->error("Failed to save results: " . $e->getMessage());
        }
    }

    /**
     * Save results as CSV
     */
    protected function saveCSV($savePath)
    {
        $file = fopen($savePath, 'w');

        // Write header
        fputcsv($file, [
            'Resource ID',
            'Resource Title',
            'Resource Slug',
            'File ID',
            'File Disk',
            'File Path',
            'File MIME',
            'File Size',
            'Issue Type',
            'Fix Action',
            'Admin URL'
        ]);

        // Write broken files
        foreach ($this->results['broken_files'] as $item) {
            fputcsv($file, [
                $item['resource_id'],
                $item['resource_title'],
                $item['resource_slug'],
                $item['file_id'] ?? '',
                $item['file_disk'] ?? '',
                $item['file_path'] ?? '',
                $item['file_mime'] ?? '',
                $item['file_size'] ?? '',
                'Missing File',
                $item['fix_action'] ?? '',
                $item['admin_url'] ?? ''
            ]);
        }

        // Write orphan records
        foreach ($this->results['missing_records'] as $item) {
            fputcsv($file, [
                $item['resource_id'],
                $item['resource_title'],
                $item['resource_slug'],
                $item['file_id'] ?? '',
                '',
                '',
                '',
                '',
                'Orphan Record',
                $item['fix_action'] ?? '',
                $item['admin_url'] ?? ''
            ]);
        }

        fclose($file);
    }

    /**
     * Generate SQL fix queries for the broken files
     */
    protected function generateFixQueries()
    {
        $this->line('');
        $this->info('========================================');
        $this->info('SQL FIX QUERIES');
        $this->info('========================================');
        $this->line('');

        $this->info('-- ===========================================');
        $this->info('-- OPTION 1: Remove orphan records from entity_files');
        $this->info('-- ===========================================');
        $this->line('');

        $orphanIds = [];
        foreach ($this->results['missing_records'] as $item) {
            if (isset($item['file_id'])) {
                $orphanIds[] = $item['file_id'];
            }
        }

        if (!empty($orphanIds)) {
            $ids = implode(', ', $orphanIds);
            $this->line("-- Run this SQL to remove orphan records:");
            $this->line("DELETE FROM entity_files WHERE file_id IN ({$ids});");
            $this->line('');
        } else {
            $this->line('-- No orphan records to remove');
            $this->line('');
        }

        $this->info('-- ===========================================');
        $this->info('-- OPTION 2: Find resources needing file re-upload');
        $this->info('-- ===========================================');
        $this->line('');

        $resourceIds = [];
        foreach ($this->results['broken_files'] as $item) {
            $resourceIds[] = $item['resource_id'];
        }

        if (!empty($resourceIds)) {
            $ids = implode(', ', $resourceIds);
            $this->line("-- Resources with missing files (need file re-upload):");
            $this->line("SELECT id, title, slug FROM resources WHERE id IN ({$ids});");
            $this->line('');
            $this->info('📝 Next steps to fix:');
            $this->line('   1. Go to each resource admin page');
            $this->line('   2. Delete the current file reference');
            $this->line('   3. Re-upload the file from your backup');
            $this->line('   4. Or restore files from S3 backup');
        } else {
            $this->line('-- No resources need file re-upload');
        }

        $this->line('');
        $this->info('-- ===========================================');
        $this->info('-- OUTPUT FORMATS');
        $this->info('-- ===========================================');
        $this->line('');
        $this->info('Run with different output formats:');
        $this->line('   php artisan resources:scan-broken --output=csv --save=/tmp/broken.csv');
        $this->line('   php artisan resources:scan-broken --output=json --save=/tmp/broken.json');
        $this->line('   php artisan resources:scan-broken --skip-s3  (faster, S3 content not checked)');
    }
}
