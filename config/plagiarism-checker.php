<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Plagiarism Checker Settings
    |--------------------------------------------------------------------------
    */

    // Free tier limits
    'free_tier' => [
        'daily_checks' => env('PLAGIARISM_FREE_DAILY_CHECKS', 5),
        'words_per_check' => env('PLAGIARISM_FREE_WORDS_PER_CHECK', 1000),
    ],

    // Registered user tier limits
    'registered_tier' => [
        'daily_checks' => env('PLAGIARISM_REGISTERED_DAILY_CHECKS', 20),
        'words_per_check' => env('PLAGIARISM_REGISTERED_WORDS_PER_CHECK', 1000),
    ],

    // Premium tier limits (for future use)
    'premium_tier' => [
        'daily_checks' => env('PLAGIARISM_PREMIUM_DAILY_CHECKS', 999),
        'words_per_check' => env('PLAGIARISM_PREMIUM_WORDS_PER_CHECK', 10000),
    ],

    // File upload settings
    'file_upload' => [
        'max_size' => env('PLAGIARISM_MAX_FILE_SIZE', 10240), // 10MB
        'allowed_types' => env('PLAGIARISM_ALLOWED_TYPES', 'txt,pdf,doc,docx'),
    ],

    // API integration settings (for future premium features)
    'api' => [
        'copyscape' => [
            'api_key' => env('COPYSCAPE_API_KEY'),
            'enabled' => env('COPYSCAPE_ENABLED', false),
        ],
        'turnitin' => [
            'api_key' => env('TURNITIN_API_KEY'),
            'enabled' => env('TURNITIN_ENABLED', false),
        ],
    ],

    // Security settings
    'security' => [
        'rate_limit' => env('PLAGIARISM_RATE_LIMIT', 10), // requests per minute
        'session_timeout' => env('PLAGIARISM_SESSION_TIMEOUT', 3600), // seconds
    ],

    // UI settings
    'ui' => [
        'primary_color' => env('PLAGIARISM_PRIMARY_COLOR', '#23A455'),
        'theme' => env('PLAGIARISM_THEME', 'green'),
    ],
];
