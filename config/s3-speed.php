<?php

return [

    /*
    |--------------------------------------------------------------------------
    | S3 Speed Optimization Configuration
    |--------------------------------------------------------------------------
    |
    | Configure S3 performance optimization settings without affecting
    | core functionality. These settings provide speed enhancements
    | while maintaining compatibility with existing code.
    |
    */

    // S3 Transfer Acceleration
    'use_acceleration' => env('S3_SPEED_ACCELERATION', false),

    // CloudFront CDN Integration
    'cloudfront_domain' => env('S3_SPEED_CLOUDFRONT_DOMAIN', ''),

    // Cache Settings
    'cache_ttl' => [
        'urls' => 300, // 5 minutes
        'metadata' => 600, // 10 minutes
        'progressive' => 180, // 3 minutes
    ],

    // Performance Thresholds
    'thresholds' => [
        'large_file' => 10 * 1024 * 1024, // 10MB
        'very_large_file' => 50 * 1024 * 1024, // 50MB
        'acceleration_recommended' => 10 * 1024 * 1024, // 10MB
    ],

    // Progressive Loading
    'progressive_loading' => [
        'enabled' => true,
        'chunk_size' => 1024 * 1024, // 1MB chunks
        'preview_size' => 64 * 1024, // 64KB for preview
    ],

    // URL Expiration (in seconds)
    'url_expiration' => [
        'default' => 3600, // 1 hour
        'images' => 86400, // 24 hours
        'documents' => 3600, // 1 hour
        'large_files' => 1800, // 30 minutes
    ],

    // Compression Settings
    'compression' => [
        'enabled' => true,
        'gzip' => true,
        'brotli' => env('S3_SPEED_BROTLI', true),
        'min_size' => 1024, // 1KB minimum
    ],

    // Browser Caching Headers
    'browser_cache' => [
        'enabled' => true,
        'max_age' => [
            'images' => 2592000, // 30 days
            'css_js' => 2592000, // 30 days
            'documents' => 86400, // 1 day
            'default' => 3600, // 1 hour
        ],
    ],

    // CDN Settings
    'cdn' => [
        'enabled' => !empty(env('S3_SPEED_CLOUDFRONT_DOMAIN')),
        'invalidate_on_update' => true,
        'cache_behavior' => 'cache-optimized',
    ],

    // Smart Caching
    'smart_cache' => [
        'enabled' => true,
        'predictive_prefetch' => true,
        'bandwidth_optimization' => true,
    ],

    // Performance Monitoring
    'monitoring' => [
        'enabled' => env('S3_SPEED_MONITORING', false),
        'track_downloads' => true,
        'track_performance' => true,
    ],

    // Optimization Strategies
    'strategies' => [
        'auto_select' => true, // Automatically choose best strategy
        'fallback_enabled' => true, // Fallback to regular S3 if optimization fails
        'progressive_priority' => true, // Prioritize progressive loading for large files
    ],

    // File Type Optimization
    'file_optimization' => [
        'images' => [
            'use_cloudfront' => true,
            'generate_thumbnails' => true,
            'progressive_jpeg' => true,
        ],
        'pdfs' => [
            'use_cloudfront' => true,
            'cache_pages' => true,
            'progressive_loading' => true,
        ],
        'videos' => [
            'use_acceleration' => true,
            'segmented_loading' => true,
            'adaptive_bitrate' => true,
        ],
        'documents' => [
            'use_cloudfront' => true,
            'smart_caching' => true,
        ],
    ],

    // Security Settings
    'security' => [
        'signed_urls' => true,
        'secure_urls' => true,
        'validate_signature' => true,
    ],

    // Debug and Development
    'debug' => [
        'enabled' => env('APP_DEBUG', false),
        'log_performance' => env('S3_SPEED_LOG_PERFORMANCE', false),
        'show_strategy_used' => env('S3_SPEED_SHOW_STRATEGY', false),
    ],

];