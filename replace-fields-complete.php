<?php

/**
 * Complete Field & Subfield Replacement
 * 
 * This script DELETES ALL existing fields and subfields,
 * then imports fresh data from the Excel file.
 * 
 * ⚠️ WARNING: This will remove ALL existing categories!
 * 
 * Usage:
 *   php replace-fields-complete.php
 *   php replace-fields-complete.php /path/to/Projectandmaterials.xlsx
 */

// Get file path
$filePath = $argv[1] ?? database_path('seeders/data/Projectandmaterials.xlsx');

if (!file_exists($filePath)) {
    echo "ERROR: File not found: {$filePath}\n";
    exit(1);
}

echo "========================================\n";
echo "  COMPLETE FIELD & SUBFIELD REPLACEMENT\n";
echo "========================================\n\n";

echo "⚠️  WARNING: This will DELETE ALL existing fields and subfields!\n";
echo "⚠️  Current data will be REMOVED and replaced with Excel data.\n\n";

echo "Current database state:\n";
echo "  - Fields: " . \DB::table('resource_fields')->count() . "\n";
echo "  - Subfields: " . \DB::table('resource_sub_fields')->count() . "\n\n";

echo "Excel file: {$filePath}\n";
echo "  - Contains: 9 fields, 560 subfields\n\n";

echo "Continue? (type 'DELETE' to confirm): ";
$handle = fopen("php://stdin", "r");
$line = trim(fgets($handle));

if ($line !== 'DELETE') {
    echo "❌ Cancelled. No changes made.\n";
    exit(0);
}

echo "\n";

// Bootstrap Laravel
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Str;

// Read Excel file using Python
echo "📊 Reading Excel file...\n\n";

$pythonScript = <<<PYTHON
import pandas as pd
import sys

df = pd.read_excel('{$filePath}')
data = df[['field', 'sub_fields']].drop_duplicates().dropna()

import json
output = {
    'rows': len(data),
    'data': data.to_dict('records')
}
print(json.dumps(output))
PYTHON;

$jsonOutput = shell_exec("python3 -c " . escapeshellarg($pythonScript) . " 2>&1");

if (empty($jsonOutput)) {
    echo "ERROR: Failed to read Excel file.\n";
    echo "Install Python dependencies: pip3 install pandas openpyxl\n";
    exit(1);
}

$data = json_decode($jsonOutput, true);

// ============================================
// STEP 1: DELETE ALL EXISTING DATA
// ============================================
echo "🗑️  DELETING EXISTING DATA...\n\n";

try {
    // Disable foreign key checks
    \DB::statement('SET FOREIGN_KEY_CHECKS=0');
    
    // Delete all subfields first (child records)
    $subfieldsDeleted = \DB::table('resource_sub_fields')->delete();
    echo "  ✓ Deleted {$subfieldsDeleted} subfields\n";
    
    // Delete all fields (parent records)
    $fieldsDeleted = \DB::table('resource_fields')->delete();
    echo "  ✓ Deleted {$fieldsDeleted} fields\n";
    
    // Re-enable foreign key checks
    \DB::statement('SET FOREIGN_KEY_CHECKS=1');
    
    echo "\n  All existing data deleted successfully!\n\n";
    
} catch (\Exception $e) {
    echo "ERROR during deletion: " . $e->getMessage() . "\n";
    exit(1);
}

// ============================================
// STEP 2: IMPORT NEW DATA FROM EXCEL
// ============================================
echo "📥 IMPORTING NEW DATA FROM EXCEL...\n\n";

$fieldsCreated = 0;
$subfieldsCreated = 0;
$processedFields = [];
$progressBar = '';
$progressChars = ['/', '-', '\\', '|'];

foreach ($data['data'] as $index => $row) {
    $field = trim($row['field']);
    $subfield = trim($row['sub_fields']);
    
    if (empty($field) || empty($subfield)) {
        continue;
    }
    
    // Create field
    $fieldSlug = Str::slug($field);
    
    if (!in_array($fieldSlug, $processedFields)) {
        \DB::table('resource_fields')->insert([
            'slug' => $fieldSlug,
            'title' => $field,
            'label' => $fieldSlug,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $fieldsCreated++;
        $processedFields[] = $fieldSlug;
        echo "  ✓ Created field: {$field}\n";
    }
    
    // Create subfield
    $subfieldSlug = Str::slug($subfield);
    
    \DB::table('resource_sub_fields')->insert([
        'slug' => $subfieldSlug,
        'title' => $subfield,
        'parent_field' => $fieldSlug,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    $subfieldsCreated++;
    
    // Show progress
    $char = $progressChars[$index % 4];
    echo "\r  {$char} Importing... ({$index}/{$data['rows']})" . str_repeat(' ', 50);
}

echo "\r" . str_repeat(' ', 80) . "\r";

echo "\n";
echo "✅ Import completed successfully!\n\n";

// ============================================
// STEP 3: VERIFY RESULTS
// ============================================
echo "📊 FINAL DATABASE STATE:\n";
echo "--------------------------------\n\n";

$fields = \DB::table('resource_fields')->orderBy('title')->get();
$totalSubfields = 0;

foreach ($fields as $f) {
    $subfieldCount = \DB::table('resource_sub_fields')->where('parent_field', $f->slug)->count();
    $totalSubfields += $subfieldCount;
    printf("  %-30s: %d subfields\n", $f->title, $subfieldCount);
}

echo "\n";
echo "  Total Fields: " . count($fields) . "\n";
echo "  Total Subfields: {$totalSubfields}\n";

echo "\n";
echo "========================================\n";
echo "  REPLACEMENT COMPLETE!\n";
echo "========================================\n\n";

echo "✅ All previous fields and subfields have been DELETED\n";
echo "✅ New fields and subfields have been imported from Excel\n";
echo "✅ Your database now contains ONLY the new categories\n\n";

echo "📝 Next steps:\n";
echo "  1. Clear caches: php artisan config:clear\n";
echo "  2. Visit admin panel and check Fields section\n";
echo "  3. Test publishing a resource with new categories\n\n";

exit(0);
