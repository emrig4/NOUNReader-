<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * PDF Guest Preview Service
 * 
 * Extracts limited pages from PDFs for guest users to reduce S3 bandwidth costs.
 * Caches extracted previews to avoid repeated S3 downloads.
 */
class PdfGuestPreviewService
{
    // ✅ Brand colors
    private $primaryColor = [35, 164, 85];      // #23A455 - Your green
    private $darkText = [44, 62, 80];           // Dark gray
    private $lightText = [127, 140, 141];       // Light gray
    private $borderColor = [189, 195, 199];     // Border gray

    /**
     * Generate a limited page preview for guest users
     *
     * @param string $filePath - S3 path to the original PDF
     * @param int $resourceId - Resource ID for caching
     * @param int $pageLimit - Number of pages to extract (default 3)
     * @param bool $addOverlay - Whether to add "END OF PREVIEW" page (default true)
     * @return array|null - Preview data or null if failed
     */
    public function generateGuestPreview($filePath, $resourceId, $pageLimit = 3, $addOverlay = true)
    {
        try {
            // Create a cache key for this guest preview
            $cacheKey = "guest_pdf_preview_{$resourceId}_{$pageLimit}_" . ($addOverlay ? 'overlay' : 'plain');

            // Check if preview is already cached (avoid repeated S3 calls)
            $cached = Cache::get($cacheKey);
            if ($cached) {
                Log::debug("Using cached guest preview for resource {$resourceId}");
                return $cached;
            }

            // Verify file exists on S3 first (HEAD request - minimal cost)
            if (!Storage::disk('s3')->exists($filePath)) {
                Log::warning("PDF file not found on S3: {$filePath}");
                return null;
            }

            // Download the original PDF from S3
            $originalContent = Storage::disk('s3')->get($filePath);
            if (!$originalContent) {
                Log::error("Failed to download PDF from S3: {$filePath}");
                return null;
            }

            // Create a temporary file to work with
            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            
            $tempPath = $tempDir . '/' . uniqid('guest_preview_') . '.pdf';
            file_put_contents($tempPath, $originalContent);

            // Extract limited pages (with or without overlay)
            if ($addOverlay) {
                $previewContent = $this->extractPagesWithOverlay($tempPath, $pageLimit);
            } else {
                $previewContent = $this->extractLimitedPages($tempPath, $pageLimit);
            }

            // Clean up temp file
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }

            if (!$previewContent) {
                Log::error("Failed to extract pages for resource {$resourceId}");
                return null;
            }

            // Prepare preview data
            $previewData = [
                'is_guest_preview' => true,
                'pages_extracted' => $pageLimit,
                'has_overlay' => $addOverlay,
                'content' => base64_encode($previewContent),
                'generated_at' => now()->toISOString()
            ];

            // Cache the preview for 7 days (reduce S3 costs significantly)
            Cache::put($cacheKey, $previewData, now()->addDays(7));

            Log::info("Guest preview generated for resource {$resourceId} ({$pageLimit} pages, overlay: " . ($addOverlay ? 'yes' : 'no') . ")");

            return $previewData;

        } catch (\Exception $e) {
            Log::error("Guest PDF preview generation failed for resource {$resourceId}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Extract limited pages from a PDF using FPDI
     *
     * @param string $sourcePath - Path to source PDF
     * @param int $pageLimit - Number of pages to extract
     * @return string|false - PDF content or false on failure
     */
    private function extractLimitedPages($sourcePath, $pageLimit)
    {
        try {
            // Use FPDI to extract pages
            $pdf = new \setasign\Fpdi\Fpdi();
            
            // Get total pages in source PDF
            $totalPages = $pdf->setSourceFile($sourcePath);
            
            // Extract only the limited pages
            $pagesToExtract = min($pageLimit, $totalPages);
            
            for ($i = 1; $i <= $pagesToExtract; $i++) {
                try {
                    $templateId = $pdf->importPage($i);
                    $size = $pdf->getTemplateSize($templateId);
                    $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                    $pdf->useTemplate($templateId);
                } catch (\Exception $pageError) {
                    Log::warning("Could not extract page {$i}: " . $pageError->getMessage());
                    continue;
                }
            }

            // Return PDF as string
            return $pdf->Output('', 'S');

        } catch (\Exception $e) {
            Log::error("PDF page extraction failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Extract pages from PDF and add END OF PREVIEW overlay page
     * ✅ MOBILE & DESKTOP COMPATIBLE: Overlay prevents scrolling past page limit
     *
     * @param string $sourcePath - Path to source PDF
     * @param int $contentPageCount - Number of content pages to extract (default 3)
     * @return string|false - PDF content or false on failure
     */
    private function extractPagesWithOverlay($sourcePath, $contentPageCount = 3)
    {
        try {
            $pdf = new \setasign\Fpdi\Fpdi();
            $sourcePageCount = $pdf->setSourceFile($sourcePath);

            // ✅ CRITICAL: Only extract specified pages (MOBILE FIX)
            $pagesToExtract = min($contentPageCount, $sourcePageCount);
            for ($i = 1; $i <= $pagesToExtract; $i++) {
                $templateId = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($templateId);
                $pdf->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId);
            }

            // ✅ OVERLAY PAGE - Green branded with credit messaging
            $pdf->AddPage('P', 'A4');
            
            $pageWidth = $pdf->GetPageWidth();
            $pageHeight = $pdf->GetPageHeight();
            $margin = 20;
            $contentWidth = $pageWidth - (2 * $margin);

            // White background
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Rect(0, 0, $pageWidth, $pageHeight, 'F');

            // ✅ Green top bar
            $pdf->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $pdf->Rect(0, 0, $pageWidth, 40, 'F');

            // ✅ Main heading - "END OF PREVIEW"
            $pdf->SetFont('Helvetica', 'B', 48);
            $pdf->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $pdf->SetXY($margin, $pageHeight * 0.25);
            $pdf->MultiCell($contentWidth, 40, 'END OF PREVIEW', 0, 'C', false);

            // ✅ Green decorative line
            $pdf->SetDrawColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $pdf->SetLineWidth(3);
            $pdf->Line($margin + 40, $pageHeight * 0.40, $pageWidth - $margin - 40, $pageHeight * 0.40);

            // Main message
            $pdf->SetFont('Helvetica', '', 16);
            $pdf->SetTextColor($this->darkText[0], $this->darkText[1], $this->darkText[2]);
            $pdf->SetXY($margin, $pageHeight * 0.43);
            $pdf->MultiCell($contentWidth, 8, 'You have reached the end of the preview.', 0, 'C', false);

            // ✅ Secondary message - Updated for credits
            $pdf->SetFont('Helvetica', '', 14);
            $pdf->SetTextColor($this->lightText[0], $this->lightText[1], $this->lightText[2]);
            $pdf->SetXY($margin, $pageHeight * 0.50);
            $pdf->MultiCell($contentWidth, 7, 'Buy credits to read the complete document.', 0, 'C', false);

            // ✅ CTA Button - BUY CREDIT (green)
            $pdf->SetFont('Helvetica', 'B', 16);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);

            $btnWidth = 180;
            $btnHeight = 16;
            $btnX = ($pageWidth - $btnWidth) / 2;
            $btnY = $pageHeight * 0.60;

            // Draw button background
            $pdf->Rect($btnX, $btnY, $btnWidth, $btnHeight, 'F');
            
            // ✅ Button text - BUY CREDIT
            $pdf->SetXY($btnX, $btnY + 2);
            $pdf->Cell($btnWidth, $btnHeight - 4, 'BUY CREDIT NOW', 0, 0, 'C');
            
            // ✅ Add clickable link - Updated to credits route
            $pdf->Link($btnX, $btnY, $btnWidth, $btnHeight, url('/pricings'));

            // ✅ Login link for existing members
            $pdf->SetFont('Helvetica', '', 13);
            $pdf->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $pdf->SetXY($margin, $pageHeight * 0.70);
            $pdf->MultiCell($contentWidth, 6, 'Already have credits? Login here', 0, 'C', false);
            $pdf->Link($margin, $pageHeight * 0.70 - 2, $contentWidth, 8, url('/login'));

            // ✅ Benefits list - PLAIN TEXT ONLY (no special chars)
            $pdf->SetFont('Helvetica', '', 11);
            $pdf->SetTextColor($this->darkText[0], $this->darkText[1], $this->darkText[2]);
            $pdf->SetXY($margin, $pageHeight * 0.78);
            
            $benefits = [
                'Instant access to complete projects',
                'High-quality project downloads',
                'Secure and safe for you'
            ];
            
            foreach ($benefits as $benefit) {
                $pdf->MultiCell($contentWidth, 5, $benefit, 0, 'C', false);
            }

            // ✅ Footer info - Updated messaging
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->SetTextColor($this->borderColor[0], $this->borderColor[1], $this->borderColor[2]);
            $pdf->SetXY($margin, $pageHeight - $margin - 10);
            $pdf->MultiCell($contentWidth, 4, 'Preview limited to ' . $contentPageCount . ' pages. Buy credits to access the full document and unlock all features.', 0, 'C', false);

            return $pdf->Output('', 'S');

        } catch (\Exception $e) {
            Log::error("PDF page extraction with overlay failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Clear cached guest preview for a resource
     *
     * @param int $resourceId
     * @param int $pageLimit
     * @param bool $addOverlay
     * @return bool
     */
    public function clearGuestPreview($resourceId, $pageLimit = 3, $addOverlay = true)
    {
        $cacheKey = "guest_pdf_preview_{$resourceId}_{$pageLimit}_" . ($addOverlay ? 'overlay' : 'plain');
        return Cache::forget($cacheKey);
    }
    
    /**
     * Clear all cached previews for a resource
     *
     * @param int $resourceId
     * @return void
     */
    public function clearAllPreviews($resourceId)
    {
        // Clear common page limits with both overlay variants
        foreach ([3, 5, 10] as $limit) {
            Cache::forget("guest_pdf_preview_{$resourceId}_{$limit}_overlay");
            Cache::forget("guest_pdf_preview_{$resourceId}_{$limit}_plain");
        }
    }
}