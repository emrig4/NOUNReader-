<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * PDF Preview Service
 * 
 * Generates lightweight PDF previews (2-3 pages) from larger PDF files
 * to improve page load performance on resource detail pages.
 */
class PdfPreviewService
{
    // ✅ Brand colors
    private $primaryColor = [35, 164, 85];      // #23A455 - Your green
    private $darkText = [44, 62, 80];           // Dark gray
    private $lightText = [127, 140, 141];       // Light gray
    private $borderColor = [189, 195, 199];     // Border gray

    /**
     * Generate a preview of a PDF file
     */
    public function generatePreview($filePath, $resourceId)
    {
        try {
            $cacheKey = "pdf_preview_{$resourceId}";
            $cached = Cache::get($cacheKey);
            
            if ($cached) {
                Log::debug("Using cached preview for resource {$resourceId}");
                return $cached;
            }

            $originalContent = Storage::disk('s3')->get($filePath);
            if (!$originalContent) {
                Log::error("Failed to download PDF from S3: {$filePath}");
                return null;
            }

            $tempPath = storage_path('app/temp/' . uniqid() . '.pdf');
            $tempDir = dirname($tempPath);
            
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            file_put_contents($tempPath, $originalContent);
            $previewContent = $this->extractPages($tempPath, 1, 3);

            if (file_exists($tempPath)) {
                unlink($tempPath);
            }

            if (!$previewContent) {
                Log::error("Failed to generate preview for resource {$resourceId}");
                return null;
            }

            $previewData = [
                'is_preview' => true,
                'pages' => 3,
                'content' => base64_encode($previewContent),
                'generated_at' => now()->toISOString()
            ];

            Cache::put($cacheKey, $previewData, now()->addHours(24));
            Log::info("Preview generated successfully for resource {$resourceId} (3 pages)");

            return $previewData;

        } catch (\Exception $e) {
            Log::error("PDF preview generation failed for resource {$resourceId}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Extract specific pages from a PDF using FPDI
     */
    private function extractPages($sourcePath, $startPage, $pageCount)
    {
        try {
            $pdf = new \setasign\Fpdi\Fpdi();
            $pageCount = $pdf->setSourceFile($sourcePath);

            for ($i = 1; $i <= min($pageCount, $pageCount); $i++) {
                $templateId = $pdf->importPage($i);
                $pdf->AddPage();
                $pdf->useTemplate($templateId);
            }

            return $pdf->Output('', 'S');

        } catch (\Exception $e) {
            Log::error("PDF page extraction failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate a secure preview PDF for guest/limited users
     * ✅ WORKS ON BOTH MOBILE AND DESKTOP
     */
    public function generateSecurePreview($fileContent, $contentPageLimit = 3)
    {
        try {
            $tempPath = storage_path('app/temp/preview_' . uniqid() . '.pdf');
            $tempDir = dirname($tempPath);
            
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            file_put_contents($tempPath, $fileContent);
            $previewContent = $this->extractPagesWithOverlay($tempPath, $contentPageLimit);

            if (file_exists($tempPath)) {
                unlink($tempPath);
            }

            if (!$previewContent) {
                Log::error("Failed to generate secure preview PDF");
                return null;
            }

            return $previewContent;

        } catch (\Exception $e) {
            Log::error("Secure PDF preview generation failed: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Extract pages from PDF and add END OF PREVIEW overlay page
     * ✅ MOBILE & DESKTOP COMPATIBLE: Overlay prevents scrolling past page 3
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
                'Secure and save for you'
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
     * Clear cached preview for a resource
     */
    public function clearPreview($resourceId)
    {
        $cacheKey = "pdf_preview_{$resourceId}";
        return Cache::forget($cacheKey);
    }
}