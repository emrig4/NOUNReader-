<?php
/**
 * cPanel-Friendly Field & Subfield Replacement Script
 * 
 * Usage: Upload to your server and access via browser
 * URL: https://pamdev.online/replace-fields-web.php
 * 
 * This script will DELETE all existing fields and subfields
 * and replace them with data from Projectandmaterials.xlsx
 */

// Increase memory limit and execution time for large imports
ini_set('memory_limit', '256M');
ini_set('max_execution_time', 300);

// Bootstrap Laravel application
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ResourceField;
use App\Models\ResourceSubField;

?>
<!DOCTYPE html>
<html>
<head>
    <title>Field & Subfield Replacement Tool</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .info {
            background-color: #cce5ff;
            color: #004085;
            padding: 15px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .warning {
            background-color: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 4px;
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px 5px 10px 0;
            cursor: pointer;
            border: none;
            font-size: 16px;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .btn-primary {
            background-color: #007bff;
        }
        pre {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
        }
        .progress {
            width: 100%;
            background-color: #e0e0e0;
            border-radius: 4px;
            margin: 20px 0;
        }
        .progress-bar {
            width: 0%;
            height: 30px;
            background-color: #4CAF50;
            border-radius: 4px;
            text-align: center;
            line-height: 30px;
            color: white;
            transition: width 0.3s;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Field & Subfield Replacement Tool</h1>
        
        <?php
        $excelFile = __DIR__ . '/Projectandmaterials.xlsx';
        $action = $_GET['action'] ?? '';
        
        if (!file_exists($excelFile)) {
            echo '<div class="error">ERROR: Excel file not found!<br>';
            echo 'Expected location: ' . $excelFile . '</div>';
            echo '<div class="info">Please upload "Projectandmaterials.xlsx" to your project root directory.</div>';
            exit;
        }
        
        // Import handler
        if ($action === 'import') {
            echo '<div class="info">Starting import process...</div>';
            
            try {
                // Disable foreign key checks for clean deletion
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
                
                // Clear existing data
                echo '<p>Deleting existing subfields...</p>';
                DB::table('resource_sub_fields')->delete();
                
                echo '<p>Deleting existing fields...</p>';
                DB::table('resource_fields')->delete();
                
                // Re-enable foreign key checks
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
                
                echo '<p>Reading Excel file...</p>';
                
                // Read Excel data
                $data = Excel::toArray(new class {}, $excelFile)[0];
                
                $fieldCount = 0;
                $subFieldCount = 0;
                $currentField = null;
                $fields = [];
                $subFields = [];
                
                // Process Excel data (skip header row if exists)
                foreach ($data as $index => $row) {
                    // Skip empty rows
                    if (empty($row[0]) && empty($row[1])) {
                        continue;
                    }
                    
                    // Check if this is a header row (first column contains "Field" or similar)
                    if ($index === 0 && stripos($row[0], 'field') !== false) {
                        continue; // Skip header row
                    }
                    
                    // Row format: Column A = Field Name, Column B = SubField Name
                    $fieldName = trim($row[0] ?? '');
                    $subFieldName = trim($row[1] ?? '');
                    
                    // If we have a field name and no subfield, it's a main field
                    if (!empty($fieldName) && empty($subFieldName)) {
                        $currentField = [
                            'name' => $fieldName,
                            'slug' => Str::slug($fieldName),
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                        
                        // Store field temporarily to get ID later
                        $fields[] = $fieldName;
                    }
                    // If we have both field name and subfield, add subfield to current field
                    elseif (!empty($fieldName) && !empty($subFieldName) && $currentField) {
                        $subFields[] = [
                            'field_id' => count($fields), // Will be corrected after fields are created
                            'name' => $subFieldName,
                            'slug' => Str::slug($subFieldName),
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                        $subFieldCount++;
                    }
                }
                
                echo '<p>Inserting ' . count($fields) . ' fields...</p>';
                
                // Insert fields and get their IDs
                $fieldIdMap = [];
                foreach ($fields as $index => $fieldName) {
                    $id = DB::table('resource_fields')->insertGetId([
                        'name' => $fieldName,
                        'slug' => Str::slug($fieldName),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    $fieldIdMap[$index] = $id;
                    $fieldCount++;
                }
                
                echo '<p>Inserting ' . count($subFields) . ' subfields...</p>';
                
                // Update subfields with correct field IDs and insert
                foreach ($subFields as &$subField) {
                    $subField['field_id'] = $fieldIdMap[$subField['field_id']];
                }
                
                DB::table('resource_sub_fields')->insert($subFields);
                
                echo '<div class="success">SUCCESS! Import completed!</div>';
                echo '<div class="info">Summary:</div>';
                echo '<table>';
                echo '<tr><th>Type</th><th>Count</th></tr>';
                echo '<tr><td>Fields</td><td>' . $fieldCount . '</td></tr>';
                echo '<tr><td>SubFields</td><td>' . $subFieldCount . '</td></tr>';
                echo '</table>';
                
            } catch (Exception $e) {
                echo '<div class="error">ERROR: ' . htmlspecialchars($e->getMessage()) . '</div>';
                echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
            }
        }
        
        // Preview data
        if (empty($action)) {
            echo '<div class="warning">WARNING: This will DELETE all existing fields and subfields!</div>';
            
            echo '<div class="info">Preview of data to be imported:</div>';
            
            try {
                $data = Excel::toArray(new class {}, $excelFile)[0];
                
                echo '<table>';
                echo '<tr><th>#</th><th>Field Name</th><th>SubField Name</th></tr>';
                
                $previewCount = 0;
                $currentField = null;
                
                foreach ($data as $index => $row) {
                    if ($previewCount >= 20) {
                        echo '<tr><td colspan="3" style="text-align:center;color:#666;">... and more rows ...</td></tr>';
                        break;
                    }
                    
                    // Skip empty rows and header
                    if (empty($row[0]) && empty($row[1])) continue;
                    if ($index === 0 && stripos($row[0], 'field') !== false) continue;
                    
                    $fieldName = trim($row[0] ?? '');
                    $subFieldName = trim($row[1] ?? '');
                    
                    if (!empty($fieldName)) {
                        echo '<tr>';
                        echo '<td>' . ($index + 1) . '</td>';
                        echo '<td><strong>' . htmlspecialchars($fieldName) . '</strong></td>';
                        echo '<td>' . htmlspecialchars($subFieldName) . '</td>';
                        echo '</tr>';
                        $previewCount++;
                    }
                }
                
                echo '</table>';
                
                $totalRows = count(array_filter($data, function($row) {
                    return !empty($row[0]) && !(stripos($row[0], 'field') !== false && $row[1] === null);
                }));
                
                echo '<p>Total rows in Excel: ' . $totalRows . '</p>';
                
            } catch (Exception $e) {
                echo '<div class="error">Error reading Excel file: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
            
            echo '<div class="progress">';
            echo '<div class="progress-bar" id="progressBar">0%</div>';
            echo '</div>';
            
            echo '<form method="post" action="?action=import" onsubmit="startProgress()">';
            echo '<button type="submit" class="btn btn-danger">DELETE ALL & REPLACE WITH NEW DATA</button>';
            echo '</form>';
            
            echo '<script>';
            echo 'function startProgress() {';
            echo '  document.getElementById("progressBar").style.width = "50%";';
            echo '  document.getElementById("progressBar").textContent = "Processing...";';
            echo '}';
            echo '</script>';
        }
        
        // Show back link
        if ($action === 'import') {
            echo '<a href="replace-fields-web.php" class="btn btn-primary">Back to Preview</a>';
        }
        ?>
        
        <hr style="margin: 30px 0;">
        
        <h3>File Status</h3>
        <?php
        $excelExists = file_exists($excelFile);
        echo '<p>' . ($excelExists ? 'Found' : 'Missing') . ' Excel file: ' . ($excelExists ? 'Yes' : 'No') . '</p>';
        echo '<p>Path: ' . $excelFile . '</p>';
        ?>
        
        <h3>Instructions</h3>
        <ol>
            <li>Ensure <code>Projectandmaterials.xlsx</code> is in your project root directory</li>
            <li>Click the button above to delete existing data and import new data</li>
            <li>Review the preview before clicking</li>
            <li>After successful import, delete this file from your server for security</li>
        </ol>
        
        <div class="warning">
            <strong>Security Note:</strong> Delete this file (<code>replace-fields-web.php</code>) from your server after use to prevent unauthorized access.
        </div>
    </div>
</body>
</html>
