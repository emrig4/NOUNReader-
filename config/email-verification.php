<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Email Verification Configuration
    |--------------------------------------------------------------------------
    */
    
    // Enable/disable email verification system
    'enabled' => env('EMAIL_VERIFICATION_ENABLED', true),
    
    // Require verification for all users
    'require_verification' => env('EMAIL_VERIFICATION_REQUIRE_VERIFICATION', false),
    
    // Token expiration time in hours
    'token_expiry_hours' => env('EMAIL_VERIFICATION_TOKEN_EXPIRY_HOURS', 24),
    
    // Enable/disable login verification codes
    'login_verification_enabled' => env('LOGIN_VERIFICATION_ENABLED', false),
    
    // Login token expiration in minutes
    'login_token_expiry_minutes' => env('LOGIN_VERIFICATION_EXPIRY_MINUTES', 15),
    
    // Bypass roles (comma separated)
    'bypass_roles' => explode(',', env('EMAIL_VERIFICATION_BYPASS_ROLES', 'sudo,admin')),
    
    // Default from address for verification emails
    'from_email' => env('MAIL_FROM_ADDRESS', 'noreply@projectandmaterials.com'),
    'from_name' => env('MAIL_FROM_NAME', 'Read Project Topics'),
    
    // Email template settings
    'email_templates' => [
        'verify_email' => 'emails.verify-email',
        'verify_login' => 'emails.verify-login',
    ],
    
    // Notification settings
    'notifications' => [
        'enabled' => env('EMAIL_VERIFICATION_NOTIFICATIONS', true),
        'admins' => explode(',', env('EMAIL_VERIFICATION_ADMIN_EMAILS', '')),
    ],
    
    // Rate limiting for resend requests
    'rate_limit' => [
        'max_attempts' => env('EMAIL_VERIFICATION_MAX_ATTEMPTS', 5),
        'decay_minutes' => env('EMAIL_VERIFICATION_DECAY_MINUTES', 60),
    ],

    // Security settings
    'security' => [
        'token_length' => env('EMAIL_VERIFICATION_TOKEN_LENGTH', 64),
        'cleanup_days' => env('EMAIL_VERIFICATION_CLEANUP_DAYS', 7),
    ],
];