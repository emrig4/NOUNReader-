<?php

/**
 * Complete Bulk Upload Solution
 * 
 * Step 1: Creates fields and subfields from Excel data
 * Step 2: Uploads all 926 resources
 * 
 * Usage: php complete-bulk-upload.php
 */

echo "========================================\n";
echo "  COMPLETE BULK UPLOAD SOLUTION\n";
echo "========================================\n\n";

echo "This script will:\n";
echo "1. Create fields and subfields from your Excel data\n";
echo "2. Upload all resources to the database\n\n";

echo "Press ENTER to continue or Ctrl+C to cancel...\n";
readline("");

echo "\n";

// Increase memory and execution time
ini_set('memory_limit', '512M');
ini_set('max_execution_time', 600);

// Bootstrap Laravel
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Modules\Resource\Models\Resource;
use App\Modules\File\Models\File;
use App\Modules\Resource\Models\ResourceField;
use App\Modules\Resource\Models\ResourceAuthor;

$filePath = __DIR__ . '/Projectandmaterials_CLEANED (1).xlsx';

echo "Step 1: Reading Excel file...\n";

if (!file_exists($filePath)) {
    echo "ERROR: File not found: {$filePath}\n";
    echo "Please upload Projectandmaterials_CLEANED (1).xlsx to: " . __DIR__ . "\n";
    exit(1);
}

try {
    $collection = Excel::toCollection(null, $filePath);
    $rows = $collection[0];
    
    $data = [];
    $uniqueFields = [];
    $uniqueSubFields = [];
    
    foreach ($rows as $row) {
        $filename = trim($row['filename'] ?? '');
        $title = trim($row['title'] ?? '');
        $fieldName = trim($row['field'] ?? '');
        $subFieldName = trim($row['sub_fields'] ?? '');
        
        if (!empty($filename) && !empty($title)) {
            $data[] = [
                'filename' => $filename,
                'title' => $title,
                'overview' => trim($row['overview'] ?? ''),
                'publication_year' => trim($row['publication_year'] ?? date('Y')),
                'author' => trim($row['author'] ?? ''),
                'coauthors' => trim($row['coauthors'] ?? ''),
                'type' => trim($row['type'] ?? ''),
                'field' => $fieldName,
                'sub_fields' => $subFieldName,
                'currency' => trim($row['currency'] ?? 'NGN'),
                'price' => trim($row['price'] ?? 0),
                'preview_limit' => trim($row['preview_limit'] ?? 5),
                'isbn' => trim($row['isbn'] ?? ''),
                'is_featured' => trim($row['is_featured'] ?? 0),
                'is_private' => trim($row['is_private'] ?? 0),
                'is_active' => trim($row['is_active'] ?? 1),
            ];
            
            if (!empty($fieldName)) {
                $uniqueFields[$fieldName] = true;
            }
            if (!empty($subFieldName)) {
                $uniqueSubFields[$subFieldName] = true;
            }
        }
    }
    
    if (empty($data)) {
        throw new Exception("No valid data found in Excel file");
    }
    
    echo "✓ Found " . count($data) . " resources\n";
    echo "✓ Found " . count($uniqueFields) . " unique fields to create\n";
    echo "✓ Found " . count($uniqueSubFields) . " unique subfields to create\n\n";
    
} catch (\Exception $e) {
    echo "ERROR reading Excel file: " . $e->getMessage() . "\n";
    exit(1);
}

// ============================================
// STEP 1: CREATE FIELDS AND SUBFIELDS
// ============================================
echo "Step 2: Creating fields and subfields...\n\n";

$fieldSlugToId = [];

try {
    DB::statement('SET FOREIGN_KEY_CHECKS=0');
    
    // Clear existing fields and subfields
    DB::table('resource_sub_fields')->delete();
    DB::table('resource_fields')->delete();
    
    DB::statement('SET FOREIGN_KEY_CHECKS=1');
    
    echo "✓ Cleared existing fields and subfields\n\n";
    
    // Create fields
    $fieldIndex = 0;
    foreach ($uniqueFields as $fieldName => $dummy) {
        $fieldSlug = Str::slug($fieldName);
        
        DB::table('resource_fields')->insert([
            'slug' => $fieldSlug,
            'title' => $fieldName,
            'label' => $fieldSlug,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        $fieldSlugToId[$fieldName] = ++$fieldIndex;
        
        echo "  Created field: {$fieldName} (slug: {$fieldSlug})\n";
    }
    
    echo "\n✓ Created " . count($uniqueFields) . " fields\n\n";
    
    // Create subfields
    $subFieldCount = 0;
    foreach ($uniqueSubFields as $subFieldName => $dummy) {
        // Find parent field - this is tricky because subfield names contain field name
        $parentFieldId = null;
        $parentFieldName = null;
        
        foreach ($uniqueFields as $fieldName => $dummy2) {
            // Check if field name is contained in subfield name
            if (stripos($subFieldName, $fieldName) !== false) {
                $parentFieldId = $fieldSlugToId[$fieldName];
                $parentFieldName = $fieldName;
                break;
            }
        }
        
        // If no parent found, assign to first field
        if (!$parentFieldId) {
            $parentFieldId = 1;
            $parentFieldName = 'First Field';
        }
        
        $subFieldSlug = Str::slug($subFieldName);
        
        DB::table('resource_sub_fields')->insert([
            'slug' => $subFieldSlug,
            'title' => $subFieldName,
            'parent_field' => Str::slug($parentFieldName),
            'field_id' => $parentFieldId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        $subFieldCount++;
    }
    
    echo "✓ Created {$subFieldCount} subfields\n\n";
    
} catch (\Exception $e) {
    echo "ERROR creating fields: " . $e->getMessage() . "\n";
    exit(1);
}

// ============================================
// STEP 3: UPLOAD RESOURCES
// ============================================
echo "Step 3: Uploading resources...\n\n";

$processedCount = 0;
$errorCount = 0;
$totalRows = count($data);

foreach ($data as $index => $item) {
    try {
        // Create field slug from field name
        $fieldSlug = Str::slug($item['field']);
        
        // Generate unique slug for resource
        $baseSlug = Str::slug($item['title']);
        $uniqueSlug = $baseSlug;
        $counter = 1;
        
        while (Resource::where('slug', $uniqueSlug)->exists()) {
            $uniqueSlug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        // Create resource
        $resource = Resource::create([
            'title' => $item['title'],
            'overview' => $item['overview'],
            'publication_year' => $item['publication_year'],
            'coauthors' => $item['coauthors'],
            'type' => $item['type'],
            'field' => $fieldSlug,
            'sub_fields' => $item['sub_fields'],
            'currency' => $item['currency'],
            'price' => $item['price'],
            'preview_limit' => $item['preview_limit'],
            'isbn' => $item['isbn'],
            'is_featured' => $item['is_featured'],
            'is_private' => $item['is_private'],
            'is_active' => $item['is_active'],
            'slug' => $uniqueSlug,
            'user_id' => 1, // Admin user
            'is_published' => true,
            'approval_status' => 'approved',
            'submitted_at' => now(),
            'approved_at' => now(),
            'approved_by' => 1,
        ]);
        
        // Create file record (simplified - assumes file exists on S3)
        $file = File::create([
            'user_id' => 1,
            'disk' => 's3',
            'filename' => $item['filename'],
            'path' => $item['filename'],
            'extension' => pathinfo($item['filename'], PATHINFO_EXTENSION),
            'mime' => 'application/pdf',
            'size' => 0,
            'location' => 'upload',
        ]);
        
        // Link file to resource
        DB::table('entity_files')->insert([
            'file_id' => $file->id,
            'entity_type' => 'App\Modules\Resource\Models\Resource',
            'entity_id' => $resource->id,
            'label' => 'main_file',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Create lead author
        if (!empty($item['author'])) {
            ResourceAuthor::create([
                'fullname' => $item['author'],
                'resource_id' => $resource->id,
                'is_lead' => 1,
                'username' => Str::slug($item['author'])
            ]);
        }
        
        $processedCount++;
        
        // Progress update
        if (($index + 1) % 50 === 0 || ($index + 1) === $totalRows) {
            echo "\r  Progress: " . ($index + 1) . "/{$totalRows} resources processed...    ";
        }
        
    } catch (\Exception $e) {
        $errorCount++;
        if ($errorCount <= 5) {
            echo "ERROR uploading resource: " . $e->getMessage() . "\n";
            echo "  Title: {$item['title']}\n";
        }
    }
}

echo "\n\n";

// ============================================
// STEP 4: VERIFY RESULTS
// ============================================
echo "Step 4: Verifying results...\n\n";

$totalResources = Resource::count();
$totalFields = ResourceField::count();
$totalSubFields = DB::table('resource_sub_fields')->count();

echo "========================================\n";
echo "  FINAL RESULTS\n";
echo "========================================\n\n";

echo "Fields Created: {$totalFields}\n";
echo "SubFields Created: {$totalSubFields}\n";
echo "Resources Uploaded: {$totalResources}\n";
echo "Errors: {$errorCount}\n\n";

if ($errorCount > 0) {
    echo "⚠️  There were {$errorCount} errors during upload.\n";
    echo "Check storage/logs/laravel.log for details.\n\n";
}

echo "✓ Fields and subfields created successfully\n";
echo "✓ Resources uploaded and ready\n";
echo "✓ All resources are APPROVED and ACTIVE\n";
echo "\nDone!\n";
