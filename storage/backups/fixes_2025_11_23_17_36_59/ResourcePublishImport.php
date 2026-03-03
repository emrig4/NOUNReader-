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

class ResourcePublishImport implements ToModel, WithStartRow, WithEvents
{
    public $sheetName;
    public $validationErrors = [];
    public $processedCount = 0;
    public $errorCount = 0;
    
    /**
     * Field mapping for deprecated/incorrect field slugs
     * Maps old field names to correct field slugs
     */
    private $fieldMapping = [
        // Old/incorrect field slugs -> Correct field slug
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
        // Log the start of import process
        Log::info('ResourcePublishImport started by user: ' . (auth()->user()->name ?? 'Unknown'));
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * Validate and map field slug to existing resource field
     */
    private function validateAndMapField($fieldSlug)
    {
        // If field is empty or null, return null
        if (empty($fieldSlug) || $fieldSlug === '?' || $fieldSlug === 'null') {
            return null;
        }

        // Trim whitespace and convert to lowercase for comparison
        $fieldSlug = trim(strtolower($fieldSlug));
        
        // Check if field exists exactly as provided
        $existingField = ResourceField::where('slug', $fieldSlug)->first();
        if ($existingField) {
            return $existingField->slug;
        }

        // Check if field exists in our mapping
        if (isset($this->fieldMapping[$fieldSlug])) {
            $mappedField = ResourceField::where('slug', $this->fieldMapping[$fieldSlug])->first();
            if ($mappedField) {
                Log::warning("Field mapping applied: {$fieldSlug} -> {$this->fieldMapping[$fieldSlug]}");
                return $this->fieldMapping[$fieldSlug];
            }
        }

        // Try partial matching (contains)
        $partialMatch = ResourceField::where('slug', 'like', '%' . str_replace('-', '', $fieldSlug) . '%')->first();
        if ($partialMatch) {
            Log::warning("Partial field match applied: {$fieldSlug} -> {$partialMatch->slug}");
            return $partialMatch->slug;
        }

        // Try matching by title (contains words)
        $wordMatches = ResourceField::where(function($query) use ($fieldSlug) {
            $words = explode('-', $fieldSlug);
            foreach ($words as $word) {
                if (strlen($word) > 2) { // Only search for words longer than 2 characters
                    $query->orWhere('title', 'like', '%' . ucfirst($word) . '%');
                }
            }
        })->first();

        if ($wordMatches) {
            Log::warning("Word-based field match applied: {$fieldSlug} -> {$wordMatches->slug}");
            return $wordMatches->slug;
        }

        // If no match found, log error and return null (will be handled by onError)
        Log::error("Invalid field slug in import: {$fieldSlug}");
        $this->validationErrors[] = "Invalid field slug: '{$fieldSlug}' does not exist in resource_fields table";
        
        return null;
    }

    /**
     * Validate and clean other fields
     */
    private function validateAndCleanData($row)
    {
        // Clean and validate publication year
        $publicationYear = $row[3];
        if (empty($publicationYear) || $publicationYear === '?' || $publicationYear === 'null') {
            $publicationYear = date('Y'); // Default to current year
        }

        // Clean and validate price
        $price = $row[10];
        if (empty($price) || $price === '?' || $price === 'null') {
            $price = 0;
        } else {
            $price = is_numeric($price) ? (int)$price : 0;
        }

        // Clean and validate currency
        $currency = $row[9];
        if (empty($currency) || $currency === '?' || $currency === 'null') {
            $currency = 'NGN'; // Default currency
        }

        // Clean and validate preview limit
        $previewLimit = $row[11];
        if (empty($previewLimit) || $previewLimit === '?' || $previewLimit === 'null') {
            $previewLimit = 5; // Default preview limit
        } else {
            $previewLimit = is_numeric($previewLimit) ? (int)$previewLimit : 5;
        }

        // Clean and validate ISBN
        $isbn = $row[12];
        if (empty($isbn) || $isbn === '?' || $isbn === 'null') {
            $isbn = null;
        }

        // Clean and validate boolean flags
        $isFeatured = (int)($row[13] === '1' || strtolower($row[13]) === 'true' || $row[13] === 1);
        $isPrivate = (int)($row[14] === '1' || strtolower($row[14]) === 'true' || $row[14] === 1);
        $isPublished = isset($row[15]) ? (int)($row[15] === '1' || strtolower($row[15]) === 'true' || $row[15] === 1) : 1;

        return [
            'publication_year' => $publicationYear,
            'price' => $price,
            'currency' => strtoupper($currency),
            'preview_limit' => $previewLimit,
            'isbn' => $isbn,
            'is_featured' => $isFeatured, // ✅ FIXED: Use correct variable
            'is_private' => $isPrivate,   // ✅ FIXED: Use correct variable
            'is_published' => $isPublished, // ✅ FIXED: Use correct variable and proper naming
        ];
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    *    
    */
    public function model(array $row)
    {
        try {
            // Skip empty rows
            if (empty($row[0]) || empty($row[1])) {
                Log::info('Skipping empty row');
                return null;
            }

            $filename = $row[0];
            $user_id = auth()->user()->id;

            // Validate filename exists in S3
            if (!Storage::disk('s3')->exists($filename)) {
                Log::error("File not found in S3: {$filename}");
                $this->validationErrors[] = "File not found: {$filename}";
                return null;
            }

            $fileMeta = Storage::disk('s3')->getMetaData($filename);
            if (!$fileMeta) {
                Log::error("Could not get metadata for file: {$filename}");
                $this->validationErrors[] = "Could not get metadata for file: {$filename}";
                return null;
            }

            // Validate and map field
            $validatedField = $this->validateAndMapField($row[7]);
            
            // If field validation failed, log but continue with null
            if ($validatedField === null && !empty($row[7]) && $row[7] !== '?' && $row[7] !== 'null') {
                Log::warning("Invalid field '{$row[7]}' found in row, setting field to null");
                $this->errorCount++;
            }

            // Clean and validate other data
            $cleanData = $this->validateAndCleanData($row);

            // Generate unique slug from title
            $title = trim($row[1]);
            $baseSlug = Str::slug($title);
            $uniqueSlug = $baseSlug;
            $counter = 1;
            
            while (Resource::where('slug', $uniqueSlug)->exists()) {
                $uniqueSlug = $baseSlug . '-' . $counter;
                $counter++;
            }

            // Create or find file
            $file = File::where('filename', $fileMeta['filename'])->first();
            if (!$file) {
                $file = File::create([
                    'user_id' => $user_id,
                    'disk' => 's3',
                    'filename' => $fileMeta['filename'],
                    'path' => $fileMeta['path'],
                    'extension' => $fileMeta['extension'],
                    'mime' => $fileMeta['mimetype'],
                    'size' => $fileMeta['size'],
                    'location' => 'upload',
                ]);
            }

            // Create resource with validated data
            $resource = Resource::create([
                'title' => $title,
                'overview' => trim($row[2]),
                'publication_year' => $cleanData['publication_year'],
                'coauthors' => $row[5], // comma delimited strings
                'type' => $row[6], // resource type slug
                'field' => $validatedField, // validated field slug (may be null)
                'sub_fields' => $row[8], // comma delimited strings
                'currency' => $cleanData['currency'],
                'price' => $cleanData['price'],
                'preview_limit' => $cleanData['preview_limit'],
                'isbn' => $cleanData['isbn'],
                'is_featured' => $cleanData['is_featured'],
                'is_private' => $cleanData['is_private'],
                'is_published' => $cleanData['is_published'], // ✅ FIXED: Changed from 'is_active' to 'is_published'
                'slug' => $uniqueSlug,
                'user_id' => $user_id,
            ]);

            $this->processedCount++;

            if($file && $resource){
                // Create entity file relationship
                \DB::table('entity_files')->insert([
                    'file_id' => $file->id,
                    'entity_type' => 'App\Modules\Resource\Models\Resource',
                    'entity_id' => $resource->id,
                    'label' => 'main_file',
                    'created_at' => $resource->created_at,
                    'updated_at' => $resource->updated_at,
                ]);

                // Create lead author
                if (!empty($row[4])) {
                    ResourceAuthor::create([
                        'fullname' => trim($row[4]),
                        'resource_id' => $resource->id,
                        'is_lead' => 1,
                        'username' => \Str::slug($row[4])
                    ]);
                }

                // Save coauthors
                if (!empty($row[5])) {
                    $authors = explode(',', $row[5]);
                    foreach($authors as $author) {
                        $author = trim($author);
                        if (!empty($author)) {
                            $resourceAuthor = new ResourceAuthor;
                            $resourceAuthor->resource_id = $resource->id;
                            $resourceAuthor->fullname = $author;

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
                
                Log::info("Successfully imported resource: {$title} (ID: {$resource->id})");
            }
            
            return $resource;

        } catch (\Exception $e) {
            Log::error('Error importing resource row: ' . $e->getMessage(), [
                'row_data' => $row,
                'trace' => $e->getTraceAsString()
            ]);
            $this->validationErrors[] = "Error importing row: " . $e->getMessage();
            $this->errorCount++;
            return null;
        }
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $this->sheetName = $event->getSheet()->getDelegate()->getTitle();
                Log::info("Starting import for sheet: {$this->sheetName}");
            },
            AfterSheet::class => function (AfterSheet $event) {
                Log::info("Import completed. Processed: {$this->processedCount}, Errors: {$this->errorCount}");
                
                if (!empty($this->validationErrors)) {
                    Log::warning('Import validation errors:', $this->validationErrors);
                }
            }
        ];
    }

    /**
     * Get validation errors for display
     */
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    /**
     * Get import statistics
     */
    public function getImportStats()
    {
        return [
            'processed' => $this->processedCount,
            'errors' => $this->errorCount,
            'error_rate' => $this->processedCount > 0 ? round(($this->errorCount / ($this->processedCount + $this->errorCount)) * 100, 2) : 0
        ];
    }
}
