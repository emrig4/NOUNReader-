<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AI Detector Settings
    |--------------------------------------------------------------------------
    */

    // Free tier limits
    'free_tier' => [
        'daily_checks' => env('AI_DETECTOR_FREE_DAILY_CHECKS', 10),
        'words_per_check' => env('AI_DETECTOR_FREE_WORDS_PER_CHECK', 1500),
    ],

    // Registered user tier limits
    'registered_tier' => [
        'daily_checks' => env('AI_DETECTOR_REGISTERED_DAILY_CHECKS', 30),
        'words_per_check' => env('AI_DETECTOR_REGISTERED_WORDS_PER_CHECK', 1500),
    ],

    // Premium tier limits (for future use)
    'premium_tier' => [
        'daily_checks' => env('AI_DETECTOR_PREMIUM_DAILY_CHECKS', 999),
        'words_per_check' => env('AI_DETECTOR_PREMIUM_WORDS_PER_CHECK', 10000),
    ],

    // File upload settings
    'file_upload' => [
        'max_size' => env('AI_DETECTOR_MAX_FILE_SIZE', 10240), // 10MB
        'allowed_types' => env('AI_DETECTOR_ALLOWED_TYPES', 'txt,pdf,doc,docx'),
    ],

    // AI detection settings
    'detection' => [
        'confidence_threshold' => env('AI_DETECTOR_CONFIDENCE_THRESHOLD', 70), // 70% threshold for AI detection
        'min_words' => env('AI_DETECTOR_MIN_WORDS', 50),
        'max_words' => env('AI_DETECTOR_MAX_WORDS', 10000),
    ],

    // API integration settings (for future premium features)
    'api' => [
        'gptzero' => [
            'api_key' => env('GPTZERO_API_KEY'),
            'enabled' => env('GPTZERO_ENABLED', false),
        ],
        'originality_ai' => [
            'api_key' => env('ORIGINALITY_AI_API_KEY'),
            'enabled' => env('ORIGINALITY_AI_ENABLED', false),
        ],
        'turnitin' => [
            'api_key' => env('TURNITIN_API_KEY'),
            'enabled' => env('TURNITIN_ENABLED', false),
        ],
    ],

    // Security settings
    'security' => [
        'rate_limit' => env('AI_DETECTOR_RATE_LIMIT', 15), // requests per minute
        'session_timeout' => env('AI_DETECTOR_SESSION_TIMEOUT', 3600), // seconds
    ],

    // UI settings
    'ui' => [
        'primary_color' => env('AI_DETECTOR_PRIMARY_COLOR', '#23A455'),
        'theme' => env('AI_DETECTOR_THEME', 'green'),
    ],
];