<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use App\Modules\File\Models\File;
use App\Modules\Resource\Models\Resource;
use App\Modules\Resource\Models\ResourceAuthor;
use App\Modules\Resource\Models\ResourceField;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ResourceBulkUpdateImport implements ToModel, WithStartRow, WithEvents
{
    public $sheetName;
    public $validationErrors = [];
    public $processedCount = 0;
    public $errorCount = 0;
    public $updateCount = 0;
    public $notFoundCount = 0;
    
    // Encoding fix tracking
    private $encodingIssuesFixed = 0;
    
    // Field mapping for deprecated/incorrect field slugs
    private $fieldMapping = [
        'media-and-communication-studies' => 'social-and-management-sciences',
        'communication-studies' => 'social-and-management-sciences', 
        'media-studies' => 'social-and-management-sciences',
        'journalism-media-studies' => 'social-and-management-sciences',
        'humanities' => 'art-and-humanities',
        'social-sciences' => 'social-and-management-sciences',
        'natural-sciences' => 'natural-and-applied-sciences',
        'formal-sciences' => 'natural-and-applied-sciences',
        'applied-sciences' => 'natural-and-applied-sciences',
        'health-sciences' => 'medical-and-health-sciences',
        'physical-sciences' => 'environmental-and-physical-sciences',
        'computer-sciences' => 'technology',
        'business' => 'social-and-management-sciences',
        'education-studies' => 'education',
        'engineering-studies' => 'engineering',
        'law-studies' => 'law',
        'medical-studies' => 'medical-and-health-sciences',
        'agriculture-studies' => 'agriculture',
        'environmental-studies' => 'environmental-and-physical-sciences',
        'accounting' => 'social-and-management-sciences',
    ];

    public function __construct(){
        $this->sheetName = '';
        
        Log::info('BULK-UPDATE ResourceBulkUpdateImport started by user: ' . (auth()->user()->name ?? 'Unknown'));
        
        $this->optimizePhpForImport();
        $this->preloadExistingResources();
    }

    private function cleanEncoding($text)
    {
        if (empty($text)) return $text;
        
        if (!is_string($text)) {
            $text = (string)$text;
        }
        
        $detectedEncoding = mb_detect_encoding($text, ['UTF-8', 'ISO-8859-1', 'Windows-1252', 'ASCII', 'CP1252'], true);
        
        if ($detectedEncoding && $detectedEncoding !== 'UTF-8') {
            $convertedText = mb_convert_encoding($text, 'UTF-8', $detectedEncoding);
            if ($convertedText !== false) {
                $text = $convertedText;
                $this->encodingIssuesFixed++;
            }
        }
        
        $originalText = $text;
        $text = $this->fixSmartQuotes($text);
        
        if ($text !== $originalText) {
            $this->encodingIssuesFixed++;
        }
        
        $text = preg_replace('/[^\x20-\x7E\x{80}-\x{10FFFF}]/u', '', $text);
        
        return trim($text);
    }

    private function fixSmartQuotes($text)
    {
        $replacements = [
            "\xE2\x80\x98" => "'",
            "\xE2\x80\x99" => "'",
            "\xE2\x80\x9C" => '"',
            "\xE2\x80\x9D" => '"',
            "\xE2\x80\xA6" => "...",
            "\xE2\x80\xA2" => "-",
            "\xE2\x80\x93" => "-",
            "\xE2\x80\x94" => "-",
            "\xC2\xA9" => "(c)",
            "\xC2\xAE" => "(r)",
            "\xE2\x84\xA2" => "(tm)",
            "\xCE\x93" => "",
            "\xE2\x94\x9C" => "",
            "\xE2\x94\xA4" => "",
            "\xE2\x9C\xA3" => "",
            "\xE2\x9C\xBA" => "",
            "\xE2\x9C\x8A" => "",
            "\xC3\x85" => "A",
        ];
        
        $cleanedText = str_replace(array_keys($replacements), array_values($replacements), $text);
        $cleanedText = preg_replace('/[Γ├╬║╗╗╟╜╫┬]/u', '', $cleanedText);
        
        return $cleanedText;
    }

    private function preloadExistingResources()
    {
        try {
            $user_id = auth()->user()->id;
            
            $existingResources = Resource::where('user_id', $user_id)
                ->select('id', 'title', 'slug', 'filename')
                ->get();
                
            $this->existingResourcesByTitle = [];
            foreach ($existingResources as $resource) {
                $cleanTitle = strtolower(trim($resource->title));
                $this->existingResourcesByTitle[$cleanTitle] = $resource;
            }
            
            Log::info('🔍 Preloaded resources for bulk update matching', [
                'resources' => count($this->existingResourcesByTitle)
            ]);
            
        } catch (\Exception $e) {
            Log::warning('Failed to preload existing resources: ' . $e->getMessage());
        }
    }

    private function optimizePhpForImport()
    {
        ini_set('default_socket_timeout', 30);
        ini_set('max_execution_time', 600);
        ini_set('memory_limit', '1024M');
        
        Log::info('🚀 PHP optimized for bulk update: timeout=30s, execution=600s, memory=1GB');
    }

    public function startRow(): int
    {
        return 2;
    }

    private function findResourceByTitle($title)
    {
        $cleanTitle = strtolower(trim($title));
        
        if (isset($this->existingResourcesByTitle[$cleanTitle])) {
            return $this->existingResourcesByTitle[$cleanTitle];
        }
        
        foreach ($this->existingResourcesByTitle as $existingTitle => $resource) {
            if (strpos(strtolower($existingTitle), $cleanTitle) !== false || 
                strpos($cleanTitle, strtolower($existingTitle)) !== false) {
                return $resource;
            }
        }
        
        return Resource::where('user_id', auth()->user()->id)
            ->where('title', 'LIKE', '%' . trim($title) . '%')
            ->first();
    }

    private function updateResource($existingResource, $row, $fileMeta = null)
    {
        try {
            $processedRow = $this->processRowData($row);
            $title = $this->cleanEncoding(trim($processedRow[1]));
            
            $updateData = [
                'title' => $title,
                'overview' => $this->cleanEncoding(trim($processedRow[2])),
                'publication_year' => $processedRow[3] ?? date('Y'),
                'coauthors' => $this->cleanEncoding($processedRow[5] ?? ''),
                'type' => $processedRow[6] ?? 'project',
                'sub_fields' => $this->cleanEncoding($processedRow[8] ?? ''),
            ];
            
            if (!empty($processedRow[7])) {
                $validatedField = $this->validateAndMapField($processedRow[7]);
                if ($validatedField) {
                    $updateData['field'] = $validatedField;
                }
            }
            
            if (isset($processedRow[9]) && !empty($processedRow[9])) {
                $updateData['currency'] = strtoupper($processedRow[9]);
            }
            if (isset($processedRow[10]) && is_numeric($processedRow[10])) {
                $updateData['price'] = (int)$processedRow[10];
            }
            
            if ($fileMeta && !empty($fileMeta['filename'])) {
                $updateData['filename'] = $fileMeta['filename'];
            }
            
            $existingResource->update($updateData);
            
            if (!empty($processedRow[4])) {
                $leadAuthor = ResourceAuthor::where('resource_id', $existingResource->id)
                    ->where('is_lead', 1)
                    ->first();
                    
                if ($leadAuthor) {
                    $leadAuthor->update([
                        'fullname' => $this->cleanEncoding(trim($processedRow[4])),
                        'username' => \Str::slug($processedRow[4])
                    ]);
                }
            }
            
            if (!empty($processedRow[5])) {
                $this->updateCoauthors($existingResource->id, $processedRow[5]);
            }
            
            if ($fileMeta && !empty($fileMeta['filename'])) {
                $this->updateFileRecord($existingResource->id, $fileMeta);
            }
            
            $this->updateCount++;
            Log::info("🔄 Updated resource: {$title} (ID: {$existingResource->id})");
            
            return $existingResource;
            
        } catch (\Exception $e) {
            Log::error('Error updating resource: ' . $e->getMessage());
            $this->validationErrors[] = "Error updating '{$row[1]}': " . $e->getMessage();
            return null;
        }
    }

    private function updateFileRecord($resourceId, $fileMeta)
    {
        try {
            $entityFile = DB::table('entity_files')
                ->where('entity_id', $resourceId)
                ->where('entity_type', 'App\Modules\Resource\Models\Resource')
                ->where('label', 'main_file')
                ->first();
                
            if ($entityFile) {
                File::where('id', $entityFile->file_id)->update([
                    'filename' => $fileMeta['filename'],
                    'path' => $fileMeta['path'] ?? $fileMeta['filename'],
                    'extension' => $fileMeta['extension'],
                    'mime' => $fileMeta['mime'] ?? 'application/pdf',
                    'size' => $fileMeta['size'] ?? 0,
                ]);
                
                Log::info("📁 Updated file record for resource {$resourceId}");
            } else {
                $user_id = auth()->user()->id;
                $file = File::create([
                    'user_id' => $user_id,
                    'disk' => 's3',
                    'filename' => $fileMeta['filename'],
                    'path' => $fileMeta['path'] ?? $fileMeta['filename'],
                    'extension' => $fileMeta['extension'],
                    'mime' => $fileMeta['mime'] ?? 'application/pdf',
                    'size' => $fileMeta['size'] ?? 0,
                    'location' => 'upload',
                ]);
                
                DB::table('entity_files')->insert([
                    'file_id' => $file->id,
                    'entity_type' => 'App\Modules\Resource\Models\Resource',
                    'entity_id' => $resourceId,
                    'label' => 'main_file',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                Log::info("📁 Created new file record for resource {$resourceId}");
            }
        } catch (\Exception $e) {
            Log::warning('Error updating file record: ' . $e->getMessage());
        }
    }

    private function updateCoauthors($resourceId, $coauthorsString)
    {
        try {
            ResourceAuthor::where('resource_id', $resourceId)
                ->where('is_lead', 0)
                ->delete();
                
            if (!empty($coauthorsString)) {
                $cleanedCoauthors = $this->cleanEncoding($coauthorsString);
                $authors = explode(',', $cleanedCoauthors);
                foreach($authors as $author) {
                    $author = trim($author);
                    if (!empty($author)) {
                        $resourceAuthor = new ResourceAuthor;
                        $resourceAuthor->resource_id = $resourceId;
                        $resourceAuthor->fullname = $author;
                        $resourceAuthor->is_lead = 0;

                        $user = User::whereRaw("CONCAT(`first_name`, ' ', `last_name`) LIKE ?", ['%'.$author.'%'])
                                ->first();
                        if($user){
                            $resourceAuthor->username = $user->username;
                        } else {
                            $resourceAuthor->username = \Str::slug($author);
                        }

                        $resourceAuthor->save();
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning('Error updating coauthors: ' . $e->getMessage());
        }
    }

    private function validateAndMapField($fieldSlug)
    {
        if (empty($fieldSlug) || $fieldSlug === '?' || $fieldSlug === 'null') {
            return null;
        }

        $fieldSlug = $this->cleanEncoding($fieldSlug);
        $fieldSlug = trim(strtolower($fieldSlug));
        
        $existingField = ResourceField::where('slug', $fieldSlug)->first();
        if ($existingField) {
            return $existingField->slug;
        }

        if (isset($this->fieldMapping[$fieldSlug])) {
            $mappedField = ResourceField::where('slug', $this->fieldMapping[$fieldSlug])->first();
            if ($mappedField) {
                return $this->fieldMapping[$fieldSlug];
            }
        }

        $partialMatch = ResourceField::where('slug', 'like', '%' . str_replace('-', '', $fieldSlug) . '%')->first();
        if ($partialMatch) {
            return $partialMatch->slug;
        }

        Log::error("Invalid field slug in bulk update: {$fieldSlug}");
        return null;
    }

    private function processRowData($row)
    {
        $processedRow = [];
        
        foreach ($row as $index => $cell) {
            if (is_string($cell)) {
                $processedRow[$index] = $this->cleanEncoding($cell);
            } else {
                $processedRow[$index] = $cell;
            }
        }
        
        return $processedRow;
    }

    private function validateFile($filename)
    {
        try {
            if (empty($filename)) {
                return null;
            }
            
            if (!Storage::disk('s3')->exists($filename)) {
                Log::warning("File not found in S3: {$filename}");
                return null;
            }
            
            $metadata = Storage::disk('s3')->getMetaData($filename);
            
            return [
                'filename' => $filename,
                'path' => $filename,
                'extension' => pathinfo($filename, PATHINFO_EXTENSION),
                'mime' => $metadata['mimetype'] ?? 'application/pdf',
                'size' => $metadata['size'] ?? 0,
            ];
            
        } catch (\Exception $e) {
            Log::warning('Error validating file: ' . $e->getMessage());
            return null;
        }
    }

    private function reportProgress($current, $total)
    {
        $percentage = round(($current / $total) * 100, 1);
        $memoryUsage = round(memory_get_usage(true) / 1024 / 1024, 2);
        
        if ($current % 10 === 0 || $current === $total) {
            Log::info("📊 Bulk Update Progress: {$current}/{$total} ({$percentage}%) | Memory: {$memoryUsage}MB | Updated: {$this->updateCount} | Not Found: {$this->notFoundCount} | Errors: {$this->errorCount}");
        }
    }

    public function model(array $row)
    {
        try {
            if (empty($row[1])) {
                Log::info('Skipping empty row (no title)');
                return null;
            }

            $processedRow = $this->processRowData($row);
            $title = $this->cleanEncoding(trim($processedRow[1]));
            
            $existingResource = $this->findResourceByTitle($title);
            
            if (!$existingResource) {
                $this->notFoundCount++;
                Log::info("🔍 Resource not found for title: {$title}");
                
                $this->reportProgress($this->updateCount + $this->notFoundCount + $this->errorCount + 1, 
                    $this->updateCount + $this->notFoundCount + $this->errorCount + 1);
                
                return null;
            }

            $fileMeta = null;
            if (!empty($processedRow[0])) {
                $fileMeta = $this->validateFile($processedRow[0]);
                if (!$fileMeta) {
                    $this->errorCount++;
                    $this->validationErrors[] = "File not found for '{$title}': {$processedRow[0]}";
                    return null;
                }
            }

            $updatedResource = $this->updateResource($existingResource, $row, $fileMeta);
            
            if ($updatedResource) {
                $this->processedCount++;
            } else {
                $this->errorCount++;
            }
            
            $this->reportProgress($this->updateCount + $this->notFoundCount + $this->errorCount, 
                $this->updateCount + $this->notFoundCount + $this->errorCount);

            return null;
            
        } catch (\Exception $e) {
            Log::error('Error processing bulk update row: ' . $e->getMessage(), [
                'row_data' => $row,
                'trace' => $e->getTraceAsString()
            ]);
            $this->validationErrors[] = "Error processing row: " . $e->getMessage();
            $this->errorCount++;
            return null;
        }
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $this->sheetName = $event->getSheet()->getDelegate()->getTitle();
                Log::info("🚀 Starting BULK UPDATE import for sheet: {$this->sheetName}");
            },
            AfterSheet::class => function (AfterSheet $event) {
                $totalTime = microtime(true) - LARAVEL_START;
                $avgPerFile = $this->updateCount > 0 ? round($totalTime / $this->updateCount, 3) : 0;
                $successRate = ($this->updateCount + $this->notFoundCount) > 0 ? 
                    round(($this->updateCount / ($this->updateCount + $this->notFoundCount + $this->errorCount)) * 100, 1) : 0;
                
                Log::info("🏁 BULK UPDATE Import completed!", [
                    'updated' => $this->updateCount,
                    'not_found' => $this->notFoundCount,
                    'errors' => $this->errorCount,
                    'encoding_issues_fixed' => $this->encodingIssuesFixed,
                    'success_rate' => $successRate . '%',
                    'total_time' => round($totalTime, 2) . 's',
                    'avg_per_file' => $avgPerFile . 's',
                    'memory_peak' => round(memory_get_peak_usage(true) / 1024 / 1024, 2) . 'MB'
                ]);
                
                if (!empty($this->validationErrors)) {
                    Log::warning('⚠️ Bulk update validation errors:', $this->validationErrors);
                }
            }
        ];
    }

    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    public function getImportStats()
    {
        return [
            'updated' => $this->updateCount,
            'not_found' => $this->notFoundCount,
            'errors' => $this->errorCount,
            'encoding_issues_fixed' => $this->encodingIssuesFixed,
            'success_rate' => ($this->updateCount + $this->notFoundCount) > 0 ? 
                round(($this->updateCount / ($this->updateCount + $this->notFoundCount + $this->errorCount)) * 100, 2) : 0,
            'memory_peak' => round(memory_get_peak_usage(true) / 1024 / 1024, 2) . 'MB',
        ];
    }
}