<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Resource\Models\Resource;
use App\Modules\File\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use DB;

class GeneratePageCount extends Command
{
    protected $signature = 'pages:generate {--limit=500 : Process only X resources at a time} {--force : Regenerate even if pages exist} {--retry : Retry failed page counts}';
    protected $description = 'Generate page counts for PDF resources from S3 and store in database';

    public function handle()
    {
        $this->info('Starting page count generation for 15000+ publications...');
        $this->newLine();

        $limit = (int) $this->option('limit');
        $force = $this->option('force');
        $retry = $this->option('retry');

        // Query: Get resources that need page count
        $query = Resource::withoutGlobalScopes();

        if ($retry) {
            // Retry mode: get resources with 0, 1, or 2 pages (likely incorrect)
            $this->info('Retry mode: Finding resources with incorrect page counts...');
            $query->where(function($q) {
                $q->whereNull('pages')
                  ->orWhere('pages', 0)
                  ->orWhere('pages', 1)
                  ->orWhere('pages', 2)
                  ->orWhere('pages', '');
            });
        } elseif (!$force) {
            // Normal mode: Only get resources where pages is null or 0
            $query->where(function($q) {
                $q->whereNull('pages')
                  ->orWhere('pages', 0)
                  ->orWhere('pages', '');
            });
        }

        $totalResources = $query->count();

        if ($totalResources === 0) {
            $this->warn('All resources already have correct page counts!');
            Log::info('Page generation skipped - all resources have correct pages');
            return 0;
        }

        $this->info("Found {$totalResources} resources to process (processing {$limit} at a time)");
        $this->newLine();

        // Process in batches to avoid memory issues
        $processedTotal = 0;
        $successTotal = 0;
        $skippedTotal = 0;
        $errorTotal = 0;

        $batchSize = $limit;
        $batches = ceil($totalResources / $batchSize);

        for ($batch = 0; $batch < $batches; $batch++) {
            $this->info("Processing batch " . ($batch + 1) . " of {$batches}...");

            // Get resources for this batch - process in order to ensure we get all
            $resources = $query->skip($batch * $batchSize)
                               ->take($batchSize)
                               ->orderBy('id', 'ASC')  // Changed to ASC to process in order
                               ->get();

            $progressBar = $this->output->createProgressBar(count($resources));
            $progressBar->setFormat('%current%/%max% [%bar%] %percent:3s%%');

            foreach ($resources as $resource) {
                try {
                    // Double-check: Skip if already has valid pages (unless force or retry)
                    if (!$force && !$retry && $resource->pages && $resource->pages > 0) {
                        $skippedTotal++;
                        $progressBar->advance();
                        continue;
                    }

                    // Get main file linked to this resource
                    $file = DB::table('entity_files')
                        ->where('entity_id', $resource->id)
                        ->where('entity_type', 'App\Modules\Resource\Models\Resource')
                        ->where('label', 'main_file')
                        ->first();

                    if (!$file) {
                        Log::info("No file attached: ID {$resource->id} - {$resource->title}");
                        $skippedTotal++;
                        $progressBar->advance();
                        continue;
                    }

                    // Get file details from files table
                    $fileRecord = File::find($file->file_id);

                    if (!$fileRecord) {
                        Log::warning("File record not found: ID {$resource->id}");
                        $skippedTotal++;
                        $progressBar->advance();
                        continue;
                    }

                    // Only process PDFs
                    if (strtolower($fileRecord->extension) !== 'pdf') {
                        Log::info("Non-PDF file ({$fileRecord->extension}): ID {$resource->id}");
                        $skippedTotal++;
                        $progressBar->advance();
                        continue;
                    }

                    // Check if file exists in S3
                    if (!$this->isFileAccessible($fileRecord->path)) {
                        Log::warning("S3 file broken/missing: {$fileRecord->path}");
                        $errorTotal++;
                        $progressBar->advance();
                        continue;
                    }

                    // Get page count from PDF - use improved method
                    $pageCount = $this->getAccuratePageCount($fileRecord->path);

                    if ($pageCount === null || $pageCount === 0) {
                        Log::warning("Could not determine pages: ID {$resource->id} - {$resource->title}");
                        $errorTotal++;
                        $progressBar->advance();
                        continue;
                    }

                    // Update resource with page count
                    $resource->update(['pages' => $pageCount]);

                    Log::info("Updated: ID {$resource->id} - {$resource->title} ({$pageCount} pages)");
                    $successTotal++;

                } catch (\Exception $e) {
                    Log::error("Error on ID {$resource->id}: " . $e->getMessage());
                    $errorTotal++;
                }

                $progressBar->advance();
            }

            $progressBar->finish();
            $processedTotal += count($resources);
        }

        $this->newLine(2);
        $this->info('=====================================================');
        $this->info("   Total Processed: {$processedTotal}");
        $this->info("   Success: {$successTotal}");
        $this->info("   Skipped: {$skippedTotal}");
        $this->info("   Errors: {$errorTotal}");
        $this->info('=====================================================');

        Log::info("Page count generation completed", [
            'processed' => $processedTotal,
            'success' => $successTotal,
            'skipped' => $skippedTotal,
            'errors' => $errorTotal
        ]);

        return 0;
    }

    /**
     * Check if file exists and is accessible in S3
     */
    private function isFileAccessible($filePath)
    {
        try {
            // Check if file exists
            if (!Storage::disk('s3')->exists($filePath)) {
                return false;
            }

            // Quick check: try to get file size
            $size = Storage::disk('s3')->size($filePath);
            return $size > 0;

        } catch (\Exception $e) {
            Log::warning("S3 accessibility check failed for {$filePath}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get accurate page count from S3 PDF file using multiple methods
     */
    private function getAccuratePageCount($filePath)
    {
        try {
            // Get PDF content from S3
            $pdfContent = Storage::disk('s3')->get($filePath);

            if (empty($pdfContent)) {
                return null;
            }

            // Try multiple methods to get accurate page count
            
            // Method 1: Look for the page count in the trailer (most reliable)
            $pageCount = $this->getPageCountFromTrailer($pdfContent);
            if ($pageCount !== null && $pageCount > 0) {
                return $pageCount;
            }

            // Method 2: Count /Type /Page objects more accurately
            $pageCount = $this->getPageCountFromObjects($pdfContent);
            if ($pageCount !== null && $pageCount > 0) {
                return $pageCount;
            }

            // Method 3: Look for /Count in page tree nodes
            $pageCount = $this->getPageCountFromPageTree($pdfContent);
            if ($pageCount !== null && $pageCount > 0) {
                return $pageCount;
            }

            // Method 4: Download and use file analysis (last resort)
            $pageCount = $this->getPageCountFromDownload($filePath);
            if ($pageCount !== null && $pageCount > 0) {
                return $pageCount;
            }

            return null;

        } catch (\Exception $e) {
            Log::error("Error reading PDF from S3 {$filePath}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Method 1: Get page count from PDF trailer (most reliable)
     */
    private function getPageCountFromTrailer($content)
    {
        // Look for /Size and /Count in trailer dictionary
        if (preg_match_all('/\/Count\s+(\d+)/', $content, $matches)) {
            // Get the last /Count value (usually in the page tree root)
            $counts = array_map('intval', $matches[1]);
            $maxCount = max($counts);
            
            // Filter out unreasonable counts (too high or too low)
            if ($maxCount > 0 && $maxCount < 5000) {
                return $maxCount;
            }
        }
        
        return null;
    }

    /**
     * Method 2: Count /Type /Page objects more accurately
     */
    private function getPageCountFromObjects($content)
    {
        // More accurate pattern to match /Type /Page but not /Type /Pages
        // This pattern looks for standalone Page objects
        $pattern = '/^\s*\/Type\s*\/Page\s*(?:\s*\/[A-Za-z0-9_]+)*\s*$/m';
        
        // Count matches
        $count = preg_match_all($pattern, $content, $matches);
        
        if ($count > 0 && $count < 5000) {
            return $count;
        }
        
        // Alternative pattern - less strict
        $pattern2 = '/\/Type\s*\/Page(?!s)/';
        $count2 = preg_match_all($pattern2, $content, $matches2);
        
        // If we get an unreasonably high count, it's likely false positives
        // Real PDFs rarely have more than a few thousand pages
        if ($count2 > 0 && $count2 < 5000) {
            // Verify by also checking /Count - if significantly different, use the lower one
            if (preg_match_all('/\/Count\s+(\d+)/', $content, $countMatches)) {
                $treeCount = max(array_map('intval', $countMatches[1]));
                if ($treeCount > 0 && $treeCount < 5000) {
                    // Use the more reasonable value
                    return min($count2, $treeCount);
                }
            }
            return $count2;
        }
        
        return null;
    }

    /**
     * Method 3: Get page count from page tree structure
     */
    private function getPageCountFromPageTree($content)
    {
        // Look for page tree root with /Count
        // Pattern: << /Type /Catalog /Pages ... /Count N >>
        if (preg_match('/\/Pages\s*\d+\s*\d+\s*R[^\]]*\/Count\s+(\d+)/', $content, $matches)) {
            $count = intval($matches[1]);
            if ($count > 0 && $count < 5000) {
                return $count;
            }
        }
        
        // Alternative: look for /Pages object with /Count
        if (preg_match('/\/Type\s*\/Pages.*?\/Count\s+(\d+)/s', $content, $matches)) {
            $count = intval($matches[1]);
            if ($count > 0 && $count < 5000) {
                return $count;
            }
        }
        
        return null;
    }

    /**
     * Method 4: Download and analyze the file (last resort)
     */
    private function getPageCountFromDownload($filePath)
    {
        try {
            // Create temp file
            $tempFile = tempnam(sys_get_temp_dir(), 'pdf_');
            
            // Download to temp file
            Storage::disk('s3')->get($filePath);
            $content = Storage::disk('s3')->get($filePath);
            file_put_contents($tempFile, $content);
            
            // Try to get page count from file
            $pageCount = null;
            
            // Method: Read raw bytes and look for page markers
            if (filesize($tempFile) > 0) {
                // Read file in chunks and look for page objects
                $handle = fopen($tempFile, 'rb');
                $pageObjects = 0;
                
                while (!feof($handle)) {
                    $chunk = fread($handle, 8192);
                    // Count /Type /Page occurrences in this chunk
                    $pageObjects += preg_match_all('/\/Type\s*\/Page(?!s)/', $chunk, $m);
                }
                fclose($handle);
                
                if ($pageObjects > 0 && $pageObjects < 5000) {
                    $pageCount = $pageObjects;
                }
            }
            
            // Clean up
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
            
            return $pageCount;
            
        } catch (\Exception $e) {
            Log::error("Error in download method for {$filePath}: " . $e->getMessage());
            return null;
        }
    }
}
