<?php

namespace App\Services;

use AWS\S3\S3Client;
use Illuminate\Support\Facades\Log;
use Exception;

class S3FileService
{
    private $s3Client;
    private $bucket;

    public function __construct()
    {
        $this->s3Client = new S3Client([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ]
        ]);
        
        $this->bucket = env('AWS_BUCKET', 'emri-solution');
    }

    /**
     * Get file contents from S3 with proper error handling
     * 
     * @param string $filePath
     * @param string $bucket
     * @return string|null Base64 encoded content or null on failure
     */
    public function getFileContents($filePath, $bucket = null)
    {
        $bucket = $bucket ?: $this->bucket;
        
        try {
            // Clean the file path
            $cleanPath = $this->cleanFilePath($filePath);
            
            $result = $this->s3Client->getObject([
                'Bucket' => $bucket,
                'Key' => $cleanPath
            ]);
            
            if ($result['Body']) {
                $fileContents = $result['Body']->getContents();
                return base64_encode($fileContents);
            }
            
        } catch (Exception $e) {
            Log::error('S3 File Access Error', [
                'error' => $e->getMessage(),
                'file_path' => $filePath,
                'bucket' => $bucket
            ]);
            
            // Return null on error - this will be handled gracefully in the view
            return null;
        }
        
        return null;
    }

    /**
     * Check if file exists in S3
     * 
     * @param string $filePath
     * @param string $bucket
     * @return bool
     */
    public function fileExists($filePath, $bucket = null)
    {
        $bucket = $bucket ?: $this->bucket;
        
        try {
            $cleanPath = $this->cleanFilePath($filePath);
            
            $this->s3Client->headObject([
                'Bucket' => $bucket,
                'Key' => $cleanPath
            ]);
            
            return true;
            
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Clean and normalize file path
     * 
     * @param string $filePath
     * @return string
     */
    private function cleanFilePath($filePath)
    {
        // Remove URL encoding and normalize path
        $decodedPath = urldecode($filePath);
        
        // Remove bucket prefix if present
        $cleanPath = preg_replace('/^.*\/([^\/]+)$/', '$1', $decodedPath);
        
        // Remove any query parameters
        $cleanPath = preg_replace('/\?.*$/', '', $cleanPath);
        
        return $cleanPath;
    }

    /**
     * Generate presigned URL for direct download
     * 
     * @param string $filePath
     * @param int $expiration Minutes
     * @param string $bucket
     * @return string|null
     */
    public function generatePresignedUrl($filePath, $expiration = 60, $bucket = null)
    {
        $bucket = $bucket ?: $this->bucket;
        
        try {
            $cleanPath = $this->cleanFilePath($filePath);
            
            $cmd = $this->s3Client->getCommand('GetObject', [
                'Bucket' => $bucket,
                'Key' => $cleanPath
            ]);
            
            $request = $this->s3Client->createPresignedRequest($cmd, "+{$expiration} minutes");
            
            return (string) $request->getUri();
            
        } catch (Exception $e) {
            Log::error('S3 Presigned URL Error', [
                'error' => $e->getMessage(),
                'file_path' => $filePath
            ]);
            
            return null;
        }
    }
}