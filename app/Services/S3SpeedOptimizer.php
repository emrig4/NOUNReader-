<?php

namespace App\Services;

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

/**
 * S3 Ultra-Speed Optimizer - Production Ready
 * Non-intrusive S3 performance optimization service optimized for shared hosting
 */
class S3SpeedOptimizer
{
    private $s3Client;
    private $bucket;
    private $region;
    private $useAcceleration;
    private $cloudFrontDomain;

    public function __construct()
    {
        $this->bucket = config('filesystems.disks.s3.bucket');
        $this->region = config('filesystems.disks.s3.region');
        $this->useAcceleration = config('s3-speed.use_acceleration', false);
        $this->cloudFrontDomain = config('s3-speed.cloudfront_domain');
        
        try {
            // Create optimized S3 client with shared hosting compatibility
            $this->s3Client = new S3Client([
                'version' => 'latest',
                'region' => $this->region,
                'credentials' => [
                    'key' => config('filesystems.disks.s3.key'),
                    'secret' => config('filesystems.disks.s3.secret'),
                ],
                'http' => [
                    'timeout' => 30, // Reduced for shared hosting
                    'connect_timeout' => 3, // Reduced for faster failure
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('S3 Client initialization failed: ' . $e->getMessage());
            $this->s3Client = null;
        }
    }

    /**
     * Generate optimized S3 URL for fast loading
     */
    public function getOptimizedUrl($path, $options = [])
    {
        // Return simple path if S3 client is not available
        if (!$this->s3Client) {
            return [
                'original_path' => $path,
                'best_url' => $path,
                'optimization_type' => 'fallback'
            ];
        }

        // Default optimization options
        $defaults = [
            'accelerated' => $this->useAcceleration,
            'cdn_enabled' => !empty($this->cloudFrontDomain),
            'expires' => 3600,
            'signed' => true,
            'progressive' => false,
        ];
        
        $options = array_merge($defaults, $options);
        $cacheKey = "s3_optimized_url_" . md5($path . serialize($options));
        
        try {
            return Cache::remember($cacheKey, 300, function() use ($path, $options) {
                $result = [
                    'original_path' => $path,
                    'optimization_type' => $this->determineOptimalStrategy($path, $options),
                    'urls' => [],
                    'metadata' => $this->getS3ObjectMetadata($path),
                ];

                // Strategy 1: CloudFront CDN (fastest)
                if ($options['cdn_enabled'] && $this->cloudFrontDomain) {
                    $result['urls']['cloudfront'] = $this->generateCloudFrontUrl($path, $options['expires']);
                }

                // Strategy 2: Regular S3 with smart caching (fallback)
                $result['urls']['s3_regular'] = $this->generateRegularS3Url($path, $options['expires']);

                // Select best strategy
                $result['best_url'] = $this->selectBestUrl($result['urls']);
                
                return $result;
            });
        } catch (\Exception $e) {
            \Log::warning('S3 optimization error: ' . $e->getMessage());
            
            // Return fallback
            return [
                'original_path' => $path,
                'best_url' => $path,
                'optimization_type' => 'fallback',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Smart S3 object metadata retrieval with caching
     */
    public function getS3ObjectMetadata($path)
    {
        if (!$this->s3Client) {
            return [
                'size' => 0,
                'type' => 'application/octet-stream',
                'etag' => '',
                'last_modified' => '',
                'cacheable' => false,
            ];
        }

        $cacheKey = "s3_metadata_" . md5($path);
        
        try {
            return Cache::remember($cacheKey, 600, function() use ($path) {
                $result = $this->s3Client->headObject([
                    'Bucket' => $this->bucket,
                    'Key' => $path,
                ]);
                
                return [
                    'size' => $result['ContentLength'],
                    'type' => $result['ContentType'] ?? 'application/octet-stream',
                    'etag' => $result['ETag'],
                    'last_modified' => $result['LastModified']->format('Y-m-d H:i:s'),
                    'cacheable' => $this->isCacheable($result),
                ];
            });
        } catch (S3Exception $e) {
            return [
                'size' => 0,
                'type' => 'application/octet-stream',
                'etag' => '',
                'last_modified' => '',
                'cacheable' => false,
                'error' => $e->getMessage(),
            ];
        } catch (\Exception $e) {
            return [
                'size' => 0,
                'type' => 'application/octet-stream',
                'etag' => '',
                'last_modified' => '',
                'cacheable' => false,
                'error' => 'Metadata retrieval failed',
            ];
        }
    }

    /**
     * Generate CloudFront URL with smart caching
     */
    private function generateCloudFrontUrl($path, $expires)
    {
        // If using CloudFront, use the CloudFront domain
        $cloudFrontUrl = "https://{$this->cloudFrontDomain}/{$path}";
        
        // Add cache-busting query parameter for updates
        $metadata = $this->getS3ObjectMetadata($path);
        if ($metadata['etag']) {
            $cloudFrontUrl .= "?v=" . substr(md5($metadata['etag']), 0, 8);
        }
        
        return $cloudFrontUrl;
    }

    /**
     * Generate regular S3 URL with smart caching
     */
    private function generateRegularS3Url($path, $expires)
    {
        try {
            $command = $this->s3Client->getCommand('GetObject', [
                'Bucket' => $this->bucket,
                'Key' => $path,
            ]);
            
            $signedUrl = $this->s3Client->createPresignedRequest($command, '+' . $expires . ' seconds')->getUri();
            
            return $signedUrl;
        } catch (\Exception $e) {
            \Log::warning('Regular S3 URL generation failed: ' . $e->getMessage());
            return $path; // Return original path as fallback
        }
    }

    /**
     * Determine optimal loading strategy for a file
     */
    private function determineOptimalStrategy($path, $options)
    {
        // CDN is best for all file types
        if ($options['cdn_enabled'] && $this->cloudFrontDomain) {
            return 'cloudfront_optimal';
        }
        
        // Regular S3 for everything else
        return 'regular_s3_sufficient';
    }

    /**
     * Select the best URL from available options
     */
    private function selectBestUrl($urls)
    {
        // Priority: CloudFront > Regular
        if (isset($urls['cloudfront'])) {
            return $urls['cloudfront'];
        }
        
        return $urls['s3_regular'] ?? '';
    }

    /**
     * Check if object is cacheable
     */
    private function isCacheable($metadata)
    {
        $contentType = $metadata['ContentType'] ?? '';
        $cacheableTypes = [
            'image/',
            'video/',
            'application/pdf',
            'text/',
            'application/javascript',
            'application/css',
        ];
        
        foreach ($cacheableTypes as $type) {
            if (strpos($contentType, $type) === 0) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Clear S3 optimization cache
     */
    public function clearCache($path = null)
    {
        try {
            if ($path) {
                $cacheKey = "s3_optimized_url_" . md5($path);
                Cache::forget($cacheKey);
            } else {
                // Clear all S3 optimization cache
                Cache::tags(['s3_optimization'])->flush();
            }
        } catch (\Exception $e) {
            \Log::warning('S3 cache clear error: ' . $e->getMessage());
        }
        
        return true;
    }

    /**
     * Get performance statistics
     */
    public function getPerformanceStats()
    {
        return [
            'acceleration_enabled' => $this->useAcceleration,
            'cloudfront_enabled' => !empty($this->cloudFrontDomain),
            'cloudfront_domain' => $this->cloudFrontDomain,
            'region' => $this->region,
            'bucket' => $this->bucket,
            's3_client_available' => $this->s3Client !== null,
        ];
    }
}