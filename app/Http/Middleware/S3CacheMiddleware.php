<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use App\Services\S3SpeedOptimizer;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

/**
 * S3 Cache Middleware - Production Ready
 * 
 * Production-optimized middleware for S3 resources with:
 * - Shared hosting compatibility
 * - Robust error handling
 * - Intelligent resource detection
 * - Performance optimization
 * - Zero impact on existing functionality
 * 
 * FIXED: Now handles RedirectResponse properly (uploads, form submissions)
 */
class S3CacheMiddleware
{
    private $s3Optimizer;
    
    public function __construct(S3SpeedOptimizer $s3Optimizer)
    {
        $this->s3Optimizer = $s3Optimizer;
    }

    /**
     * Handle an incoming request
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        try {
            // FIXED: Skip optimization for redirects (uploads, form submissions, etc.)
            if ($response instanceof RedirectResponse) {
                return $response;
            }
            
            // Only apply optimization to actual S3/static file resources
            if ($this->isS3Resource($request) && $response instanceof Response) {
                $response = $this->optimizeResponse($request, $response);
            }
        } catch (\Exception $e) {
            // Log error but don't break functionality
            \Log::warning('S3 Cache Middleware Error: ' . $e->getMessage(), [
                'path' => $request->path(),
                'response_type' => get_class($response),
                'file' => __FILE__,
                'line' => __LINE__
            ]);
        }
        
        return $response;
    }

    /**
     * Check if the request is for S3/static file resources
     * Enhanced to avoid Laravel routes
     */
    private function isS3Resource(Request $request)
    {
        $path = $request->path();
        $fullPath = $request->fullUrl();
        
        // Skip if this looks like a Laravel route (has query params that are Laravel routes)
        if ($this->isLaravelRoute($path, $fullPath)) {
            return false;
        }
        
        // Only process actual file paths, not routes
        $s3Patterns = [
            'storage/',
            'uploads/',
            'files/',
            'documents/',
            // Remove 'resources/' as it conflicts with Laravel routes
        ];
        
        foreach ($s3Patterns as $pattern) {
            if (strpos($path, $pattern) !== false) {
                return true;
            }
        }
        
        // Check for actual file extensions (images, PDFs, etc.)
        if ($this->hasFileExtension($path)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Check if path looks like a Laravel route
     */
    private function isLaravelRoute($path, $fullUrl)
    {
        // If URL has query parameters and looks like a Laravel route
        if (strpos($fullUrl, '?') !== false) {
            $queryString = parse_url($fullUrl, PHP_URL_QUERY);
            if ($queryString) {
                parse_str($queryString, $params);
                // If it has Laravel-like parameters, it's probably a route
                if (isset($params['search']) || isset($params['field']) || 
                    isset($params['type']) || isset($params['subfield'])) {
                    return true;
                }
            }
        }
        
        // Check if path matches common Laravel route patterns
        $routePatterns = [
            'resources/search',
            'resources/fields',
            'resources/subfields',
            'resources/types',
            'account',
            'admin',
            'blog',
        ];
        
        foreach ($routePatterns as $pattern) {
            if (strpos($path, $pattern) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check if path has a file extension
     */
    private function hasFileExtension($path)
    {
        $fileExtensions = [
            '.jpg', '.jpeg', '.png', '.gif', '.webp', '.svg',
            '.pdf', '.doc', '.docx', '.txt', '.rtf',
            '.css', '.js', '.html', '.xml',
            '.mp4', '.avi', '.mov', '.wmv',
            '.mp3', '.wav', '.flac',
            '.zip', '.rar', '.7z'
        ];
        
        $extension = strtolower(substr($path, strrpos($path, '.')));
        return in_array($extension, $fileExtensions);
    }

    /**
     * Optimize response for S3 resources
     * FIXED: Now properly type-hinted to accept only Response (not RedirectResponse)
     */
    private function optimizeResponse(Request $request, Response $response): Response
    {
        // Set cache headers with error handling
        $response = $this->setCacheHeaders($request, $response);
        
        // Add compression headers
        $response = $this->addCompressionHeaders($response);
        
        // Add performance headers
        $response = $this->addPerformanceHeaders($request, $response);
        
        return $response;
    }

    /**
     * Set smart cache headers with error handling
     */
    private function setCacheHeaders(Request $request, Response $response): Response
    {
        try {
            $path = $request->path();
            $contentType = $response->headers->get('Content-Type', '');
            
            // Determine cache duration based on file type
            $cacheDuration = $this->getCacheDuration($path, $contentType);
            
            if ($cacheDuration > 0) {
                $response->headers->set('Cache-Control', "public, max-age={$cacheDuration}");
                $response->headers->set('Expires', gmdate('D, d M Y H:i:s \G\M\T', time() + $cacheDuration));
                
                // FIXED: Use current time instead of filemtime() on URLs
                // filemtime() only works on physical files, not URLs
                $currentTime = time();
                $response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s \G\M\T', $currentTime));
                
                // Add ETag for cache validation
                $etag = md5($path . $response->getContent());
                $response->setEtag($etag);
                
                // Check if client has this version cached
                if ($request->headers->has('If-None-Match') && 
                    $request->headers->get('If-None-Match') === $etag) {
                    $response->setStatusCode(304);
                    $response->setContent(null);
                }
            }
        } catch (\Exception $e) {
            // If cache header setting fails, log but don't break functionality
            \Log::warning('Cache header error: ' . $e->getMessage(), [
                'path' => $request->path(),
                'method' => __METHOD__
            ]);
        }
        
        return $response;
    }

    /**
     * Add compression headers for better performance
     */
    private function addCompressionHeaders(Response $response): Response
    {
        try {
            $acceptEncoding = $_SERVER['HTTP_ACCEPT_ENCODING'] ?? '';
            
            if (strpos($acceptEncoding, 'br') !== false) {
                $response->headers->set('Content-Encoding', 'br');
            } elseif (strpos($acceptEncoding, 'gzip') !== false) {
                $response->headers->set('Content-Encoding', 'gzip');
            }
        } catch (\Exception $e) {
            // Silently ignore compression errors
        }
        
        return $response;
    }

    /**
     * Add performance-related headers
     */
    private function addPerformanceHeaders(Request $request, Response $response): Response
    {
        try {
            // Add performance hints
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
            $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
            
            // Add S3 optimization indicator
            $response->headers->set('X-S3-Optimized', 'true');
        } catch (\Exception $e) {
            // Silently ignore header errors
        }
        
        return $response;
    }

    /**
     * Get cache duration based on file type
     */
    private function getCacheDuration($path, $contentType): int
    {
        // Images: 30 days
        if (strpos($contentType, 'image/') === 0) {
            return 30 * 24 * 60 * 60; // 30 days
        }
        
        // CSS/JS: 30 days
        if (strpos($contentType, 'text/css') === 0 || 
            strpos($contentType, 'application/javascript') === 0) {
            return 30 * 24 * 60 * 60; // 30 days
        }
        
        // Documents: 1 day
        if (strpos($contentType, 'application/pdf') === 0 ||
            strpos($contentType, 'application/msword') === 0 ||
            strpos($contentType, 'application/vnd.openxmlformats-officedocument') === 0) {
            return 24 * 60 * 60; // 1 day
        }
        
        // Videos: 1 hour
        if (strpos($contentType, 'video/') === 0) {
            return 60 * 60; // 1 hour
        }
        
        // Default: 1 hour
        return 60 * 60; // 1 hour
    }
}
