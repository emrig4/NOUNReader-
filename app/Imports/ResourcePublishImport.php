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
use App\Modules\Resource\Models\ResourceSubField;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Services\S3SpeedOptimizer;
use Illuminate\Support\Facades\DB;

class ResourcePublishImport implements ToModel, WithStartRow, WithEvents
{
	public $sheetName;
	public $validationErrors = [];
	public $processedCount = 0;
	public $errorCount = 0;
	public $duplicateCount = 0;
	public $updatedCount = 0;
	
	private $s3Optimizer;
	private $useSpeedOptimizations;

	private $processedFilenames = [];
	private $processedResourceIds = [];

	private $encodingIssuesFixed = 0;

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
		$this->useSpeedOptimizations = config('s3-speed.enabled', true);
		
		if ($this->useSpeedOptimizations && class_exists(S3SpeedOptimizer::class)) {
		try {
		$this->s3Optimizer = app(S3SpeedOptimizer::class);
		Log::info('S3 Speed Optimizer initialized for import');
		} catch (\Exception $e) {
		Log::warning('S3 Speed Optimizer unavailable: ' . $e->getMessage());
		$this->s3Optimizer = null;
		}
		}

		Log::info('DUPLICATE-PREVENTION ResourcePublishImport started by user: ' . (auth()->user()->name ?? 'Unknown'));

		$this->optimizePhpForImport();
		$this->preloadExistingResources();
	}

	private function cleanEncoding($text)
	{
		if ($text === null || $text === '') {
			return $text;
		}

		if (!is_string($text)) {
			$text = (string)$text;
		}

		$detectedEncoding = mb_detect_encoding($text, ['UTF-8', 'ISO-8859-1', 'Windows-1252', 'ASCII', 'CP1252'], true);

		if ($detectedEncoding && $detectedEncoding !== 'UTF-8') {
			$convertedText = mb_convert_encoding($text, 'UTF-8', $detectedEncoding);
			if ($convertedText !== false) {
				$text = $convertedText;
				$this->encodingIssuesFixed++;
				Log::debug("Fixed encoding: {$detectedEncoding} -> UTF-8");
			}
		}

		$originalText = $text;
		$text = $this->fixSmartQuotes($text);

		if ($text !== $originalText) {
			$this->encodingIssuesFixed++;
		}

		// FIXED: Removed overly aggressive regex that was stripping content
		// Only remove control characters and null bytes, keep all valid text
		$text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $text);

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
		"\xC3\xA9" => "é",
		"\xC3\xA0" => "à",
		"\xC3\xB4" => "ô",
		"\xC3\xAA" => "ê",
		"\xC3\xA7" => "ç",
		"\xC3\xB1" => "ñ",
		"\xC3\xBC" => "ü",
		"\xC3\xB6" => "ö",
		"\xC3\xA4" => "ä",
		"\xC3\xAD" => "í",
		"\xC3\xB3" => "ó",
		"\xC3\xBA" => "ú",
		];

		$cleanedText = str_replace(array_keys($replacements), array_values($replacements), $text);
		$cleanedText = preg_replace('/[Γ├╬║╗╗╟╜╫┬]/u', '', $cleanedText);

		return $cleanedText;
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

	private function preloadExistingResources()
	{
		try {
			$user_id = auth()->user()->id;

			$this->processedFilenames = DB::table('files')
			->where('user_id', $user_id)
			->where('disk', 's3')
			->pluck('filename')
			->unique()
			->toArray();

			$existingResources = Resource::where('user_id', $user_id)
			->select('id', 'title', 'slug', 'filename')
			->get();

			foreach ($existingResources as $resource) {
				$this->processedResourceIds[$resource->title] = $resource->id;
			}

			Log::info('Preloaded existing resources for duplicate prevention', [
				'filenames' => count($this->processedFilenames),
				'resources' => count($this->processedResourceIds)
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

		Log::info('PHP optimized for S3 import: timeout=30s, execution=600s, memory=1GB');
	}

	public function startRow(): int
	{
		return 2;
	}

	private function resourceExists($filename, $title, $user_id)
	{
		try {
			$existingFile = File::where('filename', $filename)
			->where('user_id', $user_id)
			->first();
			
			if ($existingFile) {
				$existingEntityFile = DB::table('entity_files')
				->where('file_id', $existingFile->id)
				->where('entity_type', 'App\Modules\Resource\Models\Resource')
				->first();

				if ($existingEntityFile) {
					return Resource::find($existingEntityFile->entity_id);
				}
			}

			if (isset($this->processedResourceIds[$title])) {
				return Resource::find($this->processedResourceIds[$title]);
			}

			return Resource::where('user_id', $user_id)
			->where('title', 'LIKE', '%' . trim($title) . '%')
			->first();

		} catch (\Exception $e) {
			Log::warning('Error checking resource existence: ' . $e->getMessage());
			return null;
		}
	}

	private function resourceMatchesByTitleAndOverview($existingResource, $title, $overview)
	{
		if (!$existingResource) {
			return false;
		}

		$cleanTitle = strtolower(trim($this->cleanEncoding($title)));
		$cleanOverview = strtolower(trim($this->cleanEncoding($overview)));

		$existingTitle = strtolower(trim($existingResource->title ?? ''));
		$existingOverview = strtolower(trim($existingResource->overview ?? ''));

		$titleMatches = !empty($cleanTitle) && !empty($existingTitle) && 
		($cleanTitle === $existingTitle || strpos($cleanTitle, $existingTitle) !== false || strpos($existingTitle, $cleanTitle) !== false);
		
		$overviewMatches = !empty($cleanOverview) && !empty($existingOverview) && 
		($cleanOverview === $existingOverview || strpos($cleanOverview, $existingOverview) !== false || strpos($existingOverview, $cleanOverview) !== false);

		return $titleMatches && $overviewMatches;
	}

	private function updateExistingResource($existingResource, $row, $file, $cleanData, $fieldSlug, $subFieldSlug)
	{
		try {
			$processedRow = $this->processRowData($row);
			$title = $this->cleanEncoding(trim($processedRow[1] ?? ''));

			$overview = '';
			if (isset($processedRow[2]) && $processedRow[2] !== null) {
				$rawOverview = $processedRow[2];
				$overview = $this->cleanEncoding(trim($rawOverview));
				
				// Debug logging for overview field in update
				Log::debug("Overview update processing - Raw length: " . strlen($rawOverview) . ", Cleaned length: " . strlen($overview));
				
				if (empty($overview) && !empty($rawOverview)) {
					Log::warning("Overview was empty after cleaning despite having raw data during update. Raw data: " . substr($rawOverview, 0, 100));
				}
			}

			$existingResource->update([
				'title' => $title,
				'overview' => $overview,
				'publication_year' => $cleanData['publication_year'],
				'coauthors' => $this->cleanEncoding($processedRow[5] ?? ''),
				'type' => $processedRow[6] ?? 'Project',
				'field' => $fieldSlug,
				'sub_fields' => $subFieldSlug,
				'currency' => $cleanData['currency'],
				'price' => $cleanData['price'],
				'preview_limit' => $cleanData['preview_limit'],
				'isbn' => $cleanData['isbn'],
				'is_featured' => $cleanData['is_featured'],
				'is_private' => $cleanData['is_private'],
				'is_active' => $cleanData['is_active'],
			]);

			if (!empty($processedRow[4])) {
				$leadAuthor = ResourceAuthor::where('resource_id', $existingResource->id)
				->where('is_lead', 1)
				->first();

				if ($leadAuthor && $leadAuthor->fullname !== $this->cleanEncoding(trim($processedRow[4]))) {
					$leadAuthor->update([
						'fullname' => $this->cleanEncoding(trim($processedRow[4])),
						'username' => \Str::slug($processedRow[4])
					]);
				}
			}

			$this->updateCoauthors($existingResource->id, $processedRow[5] ?? '');
			
			$this->updatedCount++;
			Log::info("Updated existing resource: {$title} (ID: {$existingResource->id})");
			
			return $existingResource;

		} catch (\Exception $e) {
			Log::error('Error updating existing resource: ' . $e->getMessage());
			return null;
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
				Log::warning("Field mapping applied: {$fieldSlug} -> {$this->fieldMapping[$fieldSlug]}");
				return $this->fieldMapping[$fieldSlug];
			}
		}

		$partialMatch = ResourceField::where('slug', 'like', '%' . str_replace('-', '', $fieldSlug) . '%')->first();
		if ($partialMatch) {
			Log::warning("Partial field match applied: {$fieldSlug} -> {$partialMatch->slug}");
			return $partialMatch->slug;
		}

		$wordMatches = ResourceField::where(function($query) use ($fieldSlug) {
			$words = explode('-', $fieldSlug);
			foreach ($words as $word) {
				if (strlen($word) > 2) {
					$query->orWhere('title', 'like', '%' . ucfirst($word) . '%');
				}
			}
		})->first();
		
		if ($wordMatches) {
			Log::warning("Word-based field match applied: {$fieldSlug} -> {$wordMatches->slug}");
			return $wordMatches->slug;
		}

		Log::error("Invalid field slug in import: {$fieldSlug}");
		$this->validationErrors[] = "Invalid field slug: '{$fieldSlug}' does not exist in resource_fields table";

		return null;
	}

	private function findOrCreateField($fieldName)
	{
		if (empty($fieldName) || $fieldName === '?' || $fieldName === 'null') {
			return null;
		}

		$fieldName = $this->cleanEncoding($fieldName);
		$normalizedName = trim($fieldName);
		
		if (empty($normalizedName)) {
			return null;
		}

		$baseSlug = Str::slug($normalizedName);
		
		if (empty($baseSlug)) {
			$baseSlug = 'field-' . time();
		}

		$existingField = ResourceField::where('slug', $baseSlug)->first();
		
		if ($existingField) {
			Log::debug("Using existing field: {$existingField->title} (slug: {$existingField->slug})");
			return $existingField->slug;
		}

		try {
			$newField = ResourceField::create([
				'title' => $normalizedName,
				'slug' => $baseSlug,
				'label' => 'General',
				'is_active' => true,
				'sort_order' => ResourceField::getNextSortOrder() ?? 1,
			]);

			Log::info("Created new field: {$normalizedName} (slug: {$newField->slug})");
			return $newField->slug;

		} catch (\Exception $e) {
			Log::error("Error creating field '{$normalizedName}': " . $e->getMessage());
			$this->validationErrors[] = "Error creating field: {$normalizedName}";
			return null;
		}
	}

	private function findOrCreateSubField($subFieldName, $parentFieldSlug)
	{
		if (empty($subFieldName) || $subFieldName === '?' || $subFieldName === 'null') {
			return null;
		}

		if (empty($parentFieldSlug)) {
			Log::warning("Cannot create sub-field '{$subFieldName}' - parent field slug is empty");
			return null;
		}

		$parentField = ResourceField::where('slug', $parentFieldSlug)->first();
		if (!$parentField) {
			Log::warning("Cannot create sub-field '{$subFieldName}' - parent field '{$parentFieldSlug}' does not exist");
			$this->validationErrors[] = "Parent field '{$parentFieldSlug}' does not exist for sub-field '{$subFieldName}'";
			return null;
		}

		$subFieldName = $this->cleanEncoding($subFieldName);
		$normalizedName = trim($subFieldName);
		
		if (empty($normalizedName)) {
			return null;
		}

		$baseSlug = Str::slug($normalizedName);
		
		if (empty($baseSlug)) {
			$baseSlug = 'subfield-' . time();
		}

		$existingSubField = ResourceSubField::where('slug', $baseSlug)
		->where('parent_field', $parentFieldSlug)
		->first();
		
		if ($existingSubField) {
			Log::debug("Using existing sub-field: {$existingSubField->title} (slug: {$existingSubField->slug}) under field: {$parentFieldSlug}");
			return $existingSubField->slug;
		}

		try {
			$newSubField = ResourceSubField::create([
				'title' => $normalizedName,
				'slug' => $baseSlug,
				'parent_field' => $parentFieldSlug,
				'is_active' => true,
				'sort_order' => ResourceSubField::getNextSortOrder($parentFieldSlug) ?? 1,
			]);

			Log::info("Created new sub-field: {$normalizedName} (slug: {$newSubField->slug}) under field: {$parentFieldSlug}");
			return $newSubField->slug;

		} catch (\Exception $e) {
			Log::error("Error creating sub-field '{$normalizedName}' under field '{$parentFieldSlug}': " . $e->getMessage());
			$this->validationErrors[] = "Error creating sub-field: {$normalizedName} under field {$parentFieldSlug}";
			return null;
		}
	}

	private function processFieldAndSubFields($fieldName, $subFieldName)
	{
		$fieldSlug = $this->findOrCreateField($fieldName);
		
		if (empty($fieldSlug)) {
			return [
				'field' => null,
				'sub_fields' => null,
			];
		}

		$subFieldSlug = null;
		if (!empty($subFieldName) && $subFieldName !== '?' && $subFieldName !== 'null') {
			$subFieldSlug = $this->findOrCreateSubField($subFieldName, $fieldSlug);
		}

		return [
			'field' => $fieldSlug,
			'sub_fields' => $subFieldSlug,
		];
	}

	private function validateAndCleanData($row)
	{
		$publicationYear = isset($row[3]) ? $row[3] : date('Y');
		if (empty($publicationYear) || $publicationYear === '?' || $publicationYear === 'null') {
			$publicationYear = date('Y');
		}

		$price = isset($row[10]) ? $row[10] : 0;
		if (empty($price) || $price === '?' || $price === 'null') {
			$price = 0;
		} else {
			$price = is_numeric($price) ? (int)$price : 0;
		}

		$currency = isset($row[9]) ? $row[9] : 'NGN';
		if (empty($currency) || $currency === '?' || $currency === 'null') {
			$currency = 'NGN';
		}

		$previewLimit = isset($row[11]) ? $row[11] : 5;
		if (empty($previewLimit) || $previewLimit === '?' || $previewLimit === 'null') {
			$previewLimit = 5;
		} else {
			$previewLimit = is_numeric($previewLimit) ? (int)$previewLimit : 5;
		}

		$isbn = isset($row[12]) ? $row[12] : null;
		if (empty($isbn) || $isbn === '?' || $isbn === 'null') {
			$isbn = null;
		}

		$isFeatured = isset($row[13]) ? (int)($row[13] === '1' || strtolower($row[13]) === 'true' || $row[13] === 1) : 0;
		$isPrivate = isset($row[14]) ? (int)($row[14] === '1' || strtolower($row[14]) === 'true' || $row[14] === 1) : 0;
		$isActive = isset($row[15]) ? (int)($row[15] === '1' || strtolower($row[15]) === 'true' || $row[15] === 1) : 1;

		return [
			'publication_year' => $publicationYear,
			'price' => $price,
			'currency' => strtoupper($currency),
			'preview_limit' => $previewLimit,
			'isbn' => $isbn,
			'is_featured' => $isFeatured,
			'is_private' => $isPrivate,
			'is_active' => $isActive
		];
	}

	private function ultraFastS3Validation($filename, $user_id)
	{
		try {
			$validationStart = microtime(true);

			if ($this->s3Optimizer) {
				try {
					$optimizedData = $this->s3Optimizer->getOptimizedUrl($filename, [
						'accelerated' => true,
						'cdn_enabled' => true,
						'expires' => 300,
						'signed' => false,
						'progressive' => false,
					]);
					
					if (!empty($optimizedData['metadata'])) {
						$metadata = $optimizedData['metadata'];

						$fileMeta = [
							'filename' => $filename,
							'path' => $filename,
							'extension' => pathinfo($filename, PATHINFO_EXTENSION),
							'mimetype' => $metadata['type'] ?? 'application/pdf',
							'size' => $metadata['size'] ?? 0,
							'optimization_strategy' => $optimizedData['optimization_type'],
							'fast_url' => $optimizedData['best_url'] ?? null,
						];
						
						$validationTime = round((microtime(true) - $validationStart) * 1000, 2);
						Log::info("Ultra-fast S3 validation: {$filename} in {$validationTime}ms using {$optimizedData['optimization_type']}");
						
						return $fileMeta;
					}
				} catch (\Exception $e) {
					Log::warning("S3 Speed Optimizer failed for {$filename}, falling back: " . $e->getMessage());
				}
			}

			if (!Storage::disk('s3')->exists($filename)) {
				Log::error("File not found in S3: {$filename}");
				$this->validationErrors[] = "File not found: {$filename}";
				return null;
			}

			try {
				$metadata = Storage::disk('s3')->getMetaData($filename);
				
				if ($metadata) {
					$validationTime = round((microtime(true) - $validationStart) * 1000, 2);
					Log::info("Fast S3 validation: {$filename} in {$validationTime}ms");
					
					return [
						'filename' => $metadata['filename'],
						'path' => $metadata['path'],
						'extension' => $metadata['extension'],
						'mimetype' => $metadata['mimetype'],
						'size' => $metadata['size'],
						'optimization_strategy' => 'regular_s3',
					];
				}
			} catch (\Exception $e) {
				Log::warning("Metadata retrieval failed for {$filename}, creating defaults", ['error' => $e->getMessage()]);
			}

			$validationTime = round((microtime(true) - $validationStart) * 1000, 2);
			Log::info("Emergency fallback for {$filename} in {$validationTime}ms");
			
			return [
				'filename' => $filename,
				'path' => $filename,
				'extension' => pathinfo($filename, PATHINFO_EXTENSION),
				'mimetype' => 'application/pdf',
				'size' => 0,
				'optimization_strategy' => 'emergency_fallback',
			];

		} catch (\Exception $e) {
			Log::error("S3 validation completely failed for file: {$filename}", ['error' => $e->getMessage()]);
			$this->validationErrors[] = "S3 connection failed for file: {$filename}";
			return null;
		}
	}

	private function reportProgress($current, $total)
	{
		$percentage = round(($current / $total) * 100, 1);
		$memoryUsage = round(memory_get_usage(true) / 1024 / 1024, 2);
		
		if ($current % 10 === 0 || $current === $total) {
			Log::info("Import Progress: {$current}/{$total} ({$percentage}%) | Memory: {$memoryUsage}MB | Processed: {$this->processedCount} | Duplicates: {$this->duplicateCount} | Updated: {$this->updatedCount} | Errors: {$this->errorCount} | Encoding Fixed: {$this->encodingIssuesFixed}");
		}
	}

	public function model(array $row)
	{
		try {
			if (empty($row[0]) || empty($row[1])) {
				Log::info('Skipping empty row');
				return null;
			}

			$processedRow = $this->processRowData($row);
			
			$filename = $processedRow[0] ?? '';
			$title = $this->cleanEncoding(trim($processedRow[1] ?? ''));
			
			$overview = '';
			if (isset($processedRow[2]) && $processedRow[2] !== null) {
				$rawOverview = $processedRow[2];
				$overview = $this->cleanEncoding(trim($rawOverview));
				
				// Debug logging for overview field
				Log::debug("Overview processing - Raw length: " . strlen($rawOverview) . ", Cleaned length: " . strlen($overview));
				
				if (empty($overview) && !empty($rawOverview)) {
					Log::warning("Overview was empty after cleaning despite having raw data. Raw data: " . substr($rawOverview, 0, 100));
				}
			}
			
			$user_id = auth()->user()->id;

			$existingResource = $this->resourceExists($filename, $title, $user_id);

			if ($existingResource) {
				if ($this->resourceMatchesByTitleAndOverview($existingResource, $title, $overview)) {
					Log::info("Title and Overview match - updating resource: {$title} (ID: {$existingResource->id})");

					$fileMeta = $this->ultraFastS3Validation($filename, $user_id);

					if ($fileMeta) {
						$fieldData = $this->processFieldAndSubFields($processedRow[7] ?? '', $processedRow[8] ?? '');
						$fieldSlug = $fieldData['field'];
						$subFieldSlug = $fieldData['sub_fields'];

						$cleanData = $this->validateAndCleanData($processedRow);

						$this->updateExistingResource($existingResource, $processedRow, $fileMeta, $cleanData, $fieldSlug, $subFieldSlug);

						$this->reportProgress($this->processedCount + $this->duplicateCount + $this->updatedCount + $this->errorCount + 1, 
							$this->processedCount + $this->duplicateCount + $this->updatedCount + $this->errorCount + 1);
						
						return null;
					} else {
						$this->errorCount++;
						return null;
					}
				}

				$this->duplicateCount++;
				Log::info("Duplicate detected - skipping: {$title} (Existing ID: {$existingResource->id})");

				$this->reportProgress($this->processedCount + $this->duplicateCount + $this->updatedCount + $this->errorCount + 1, $this->processedCount + $this->duplicateCount + $this->updatedCount + $this->errorCount + 1);

				return null;
			}

			$fileMeta = $this->ultraFastS3Validation($filename, $user_id);

			if (!$fileMeta) {
				$this->errorCount++;
				return null;
			}

			$fieldData = $this->processFieldAndSubFields($processedRow[7] ?? '', $processedRow[8] ?? '');
			$fieldSlug = $fieldData['field'];
			$subFieldSlug = $fieldData['sub_fields'];

			$cleanData = $this->validateAndCleanData($processedRow);

			$baseSlug = Str::slug($title);
			$uniqueSlug = $baseSlug;
			$counter = 1;

			while (Resource::where('slug', $uniqueSlug)->where('id', '!=', $existingResource->id ?? 0)->exists()) {
				$uniqueSlug = $baseSlug . '-' . $counter;
				$counter++;
			}

			return DB::transaction(function() use ($fileMeta, $processedRow, $cleanData, $fieldSlug, $subFieldSlug, $uniqueSlug, $user_id, $title, $overview) {

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

					Log::debug("Created file record: {$fileMeta['filename']}");
				}

				$resource = Resource::create([
					'title' => $title,
					'overview' => $overview,
					'publication_year' => $cleanData['publication_year'],
					'coauthors' => $this->cleanEncoding($processedRow[5] ?? ''),
					'type' => $processedRow[6] ?? 'Project',
					'field' => $fieldSlug,
					'sub_fields' => $subFieldSlug,
					'currency' => $cleanData['currency'],
					'price' => $cleanData['price'],
					'preview_limit' => $cleanData['preview_limit'],
					'isbn' => $cleanData['isbn'],
					'is_featured' => $cleanData['is_featured'],
					'is_private' => $cleanData['is_private'],
					'is_active' => $cleanData['is_active'],
					'slug' => $uniqueSlug,
					'user_id' => $user_id,
					'is_published' => true,
					'approval_status' => 'approved',
					'submitted_at' => now(),
					'approved_at' => now(),
					'approved_by' => auth()->id(),
				]);

				$this->processedCount++;

				if($file && $resource){
					DB::table('entity_files')->insert([
						'file_id' => $file->id,
						'entity_type' => 'App\Modules\Resource\Models\Resource',
						'entity_id' => $resource->id,
						'label' => 'main_file',
						'created_at' => $resource->created_at,
						'updated_at' => $resource->updated_at,
					]);

					if (!empty($processedRow[4])) {
						ResourceAuthor::create([
							'fullname' => $this->cleanEncoding(trim($processedRow[4])),
							'resource_id' => $resource->id,
							'is_lead' => 1,
							'username' => \Str::slug($processedRow[4])
						]);
					}

					if (!empty($processedRow[5])) {
						$cleanedCoauthors = $this->cleanEncoding($processedRow[5]);
						$authors = explode(',', $cleanedCoauthors);
						foreach($authors as $author) {
							$author = trim($author);
							if (!empty($author)) {
								$resourceAuthor = new ResourceAuthor;
								$resourceAuthor->resource_id = $resource->id;
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
				}

				Log::info("Successfully imported resource: {$title} (ID: {$resource->id}) using {$fileMeta['optimization_strategy']} | Encoding fixes applied: {$this->encodingIssuesFixed}");

				return $resource;
			});

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
				Log::info("Starting DUPLICATE-PREVENTION import for sheet: {$this->sheetName}");
			},
			AfterSheet::class => function (AfterSheet $event) {
				$totalTime = microtime(true) - LARAVEL_START;
				$avgPerFile = $this->processedCount > 0 ? round($totalTime / $this->processedCount, 3) : 0;
				$successRate = $this->processedCount > 0 ? round(($this->processedCount / ($this->processedCount + $this->errorCount)) * 100, 1) : 0;
				
				Log::info("DUPLICATE-PREVENTION Import completed!", [
					'processed' => $this->processedCount,
					'duplicates_prevented' => $this->duplicateCount,
					'updated' => $this->updatedCount,
					'errors' => $this->errorCount,
					'encoding_issues_fixed' => $this->encodingIssuesFixed,
					'success_rate' => $successRate . '%',
					'total_time' => round($totalTime, 2) . 's',
					'avg_per_file' => $avgPerFile . 's',
					'memory_peak' => round(memory_get_peak_usage(true) / 1024 / 1024, 2) . 'MB'
				]);

				if (!empty($this->validationErrors)) {
					Log::warning('Import validation errors:', $this->validationErrors);
				}

				if ($this->encodingIssuesFixed > 0) {
					Log::info("Total encoding issues fixed during import: {$this->encodingIssuesFixed}");
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
			'processed' => $this->processedCount,
			'duplicates_prevented' => $this->duplicateCount,
			'updated' => $this->updatedCount,
			'errors' => $this->errorCount,
			'encoding_issues_fixed' => $this->encodingIssuesFixed,
			'error_rate' => $this->processedCount > 0 ? round(($this->errorCount / ($this->processedCount + $this->errorCount)) * 100, 2) : 0,
			'duplication_rate' => ($this->duplicateCount + $this->updatedCount) > 0 ? round(($this->duplicateCount / ($this->duplicateCount + $this->processedCount + $this->updatedCount)) * 100, 2) : 0,
			's3_optimizations' => $this->useSpeedOptimizations && $this->s3Optimizer ? 'enabled' : 'disabled',
			'memory_peak' => round(memory_get_peak_usage(true) / 1024 / 1024, 2) . 'MB',
			'optimization_strategies' => ['duplicate-prevention', 'ultra-fast-validation', 'smart-caching', 'fallback-protection', 'transactional-integrity', 'encoding-cleanup', 'dynamic-field-creation']
		];
	}
}
