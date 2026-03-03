<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\S3SpeedOptimizer;

/**
 * S3 File Controller - Production Optimized
 * Handles S3 file operations with speed optimization
 */
class S3FileController extends Controller
{
    protected $s3Optimizer;

    public function __construct(S3SpeedOptimizer $s3Optimizer)
    {
        $this->s3Optimizer = $s3Optimizer;
    }

    /**
     * Get optimized S3 URL for fast loading
     */
    public function getOptimizedUrl(Request $request): JsonResponse
    {
        $request->validate([
            'file_path' => 'required|string',
            'options' => 'array'
        ]);

        try {
            $filePath = $request->input('file_path');
            $options = $request->input('options', []);
            
            $optimizedData = $this->s3Optimizer->getOptimizedUrl($filePath, $options);
            
            return response()->json([
                'success' => true,
                'data' => $optimizedData,
                'timestamp' => now()->toISOString()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('S3 URL optimization failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to optimize URL',
                'fallback_url' => $request->input('file_path')
            ], 500);
        }
    }

    /**
     * Get file metadata with S3 optimization
     */
    public function getFileMetadata(Request $request): JsonResponse
    {
        $request->validate([
            'file_path' => 'required|string'
        ]);

        try {
            $filePath = $request->input('file_path');
            $metadata = $this->s3Optimizer->getS3ObjectMetadata($filePath);
            
            return response()->json([
                'success' => true,
                'data' => $metadata,
                'timestamp' => now()->toISOString()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('S3 metadata retrieval failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to retrieve metadata',
                'data' => [
                    'size' => 0,
                    'type' => 'application/octet-stream',
                    'cacheable' => false
                ]
            ], 500);
        }
    }

    /**
     * Preload file with progressive loading
     */
    public function preloadFile(Request $request): JsonResponse
    {
        $request->validate([
            'file_path' => 'required|string',
            'page_range' => 'array',
            'priority' => 'integer|min:1|max:5'
        ]);

        try {
            $filePath = $request->input('file_path');
            $pageRange = $request->input('page_range', [1, 2]);
            $priority = $request->input('priority', 3);
            
            // Use optimized URL
            $optimizedData = $this->s3Optimizer->getOptimizedUrl($filePath);
            $fileUrl = $optimizedData['best_url'];
            
            // Return progressive loading configuration
            return response()->json([
                'success' => true,
                'data' => [
                    'url' => $fileUrl,
                    'page_range' => $pageRange,
                    'priority' => $priority,
                    'preload_config' => [
                        'chunk_size' => config('s3-speed.progressive_loading.chunk_size', 1048576),
                        'preview_size' => config('s3-speed.progressive_loading.preview_size', 65536),
                        'timeout' => 30000
                    ]
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('S3 file preload failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to preload file'
            ], 500);
        }
    }

    /**
     * Clear S3 optimization cache
     */
    public function clearCache(Request $request): JsonResponse
    {
        try {
            $filePath = $request->input('file_path');
            
            if ($filePath) {
                $this->s3Optimizer->clearCache($filePath);
                $message = "Cache cleared for: {$filePath}";
            } else {
                $this->s3Optimizer->clearCache();
                $message = "All S3 cache cleared";
            }
            
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
            
        } catch (\Exception $e) {
            \Log::error('S3 cache clear failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to clear cache'
            ], 500);
        }
    }

    /**
     * Get S3 performance statistics
     */
    public function getPerformanceStats(): JsonResponse
    {
        try {
            $stats = $this->s3Optimizer->getPerformanceStats();
            
            return response()->json([
                'success' => true,
                'data' => $stats,
                'timestamp' => now()->toISOString()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('S3 stats retrieval failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to retrieve performance stats'
            ], 500);
        }
    }

    /**
     * Generate presigned URL for direct access
     */
    public function generatePresignedUrl(Request $request): JsonResponse
    {
        $request->validate([
            'file_path' => 'required|string',
            'expires' => 'integer|min:60|max:86400'
        ]);

        try {
            $filePath = $request->input('file_path');
            $expires = $request->input('expires', 3600);
            
            $optimizedData = $this->s3Optimizer->getOptimizedUrl($filePath, [
                'expires' => $expires,
                'signed' => true
            ]);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'url' => $optimizedData['best_url'],
                    'expires' => now()->addSeconds($expires)->toISOString()
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('S3 presigned URL generation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to generate presigned URL'
            ], 500);
        }
    }
}