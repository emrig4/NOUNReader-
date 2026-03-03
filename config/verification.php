<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Verification Code Configuration
    |--------------------------------------------------------------------------
    */

    'code' => [
        'length' => env('VERIFICATION_CODE_LENGTH', 6),
        'expiry_minutes' => env('VERIFICATION_CODE_EXPIRY_MINUTES', 15),
    ],

    'attempts' => [
        'max_verification_attempts' => env('MAX_VERIFICATION_ATTEMPTS', 5),
        'max_resend_attempts_per_hour' => env('MAX_RESEND_ATTEMPTS_PER_HOUR', 3),
        'resend_cooldown_minutes' => env('RESEND_COOLDOWN_MINUTES', 1),
    ],

    'security' => [
        'require_https' => env('VERIFICATION_REQUIRE_HTTPS', true),
        'rate_limit_verification' => env('RATE_LIMIT_VERIFICATION', true),
        'log_verification_attempts' => env('LOG_VERIFICATION_ATTEMPTS', true),
    ],

    'email' => [
        'subject' => env('VERIFICATION_EMAIL_SUBJECT', 'Your Verification Code'),
        'from_address' => env('MAIL_FROM_ADDRESS', 'noreply@projectandmaterials.com'),
        'from_name' => env('MAIL_FROM_NAME', 'Read Project Topics'),
    ],

    'features' => [
        'auto_redirect_after_verification' => env('AUTO_REDIRECT_AFTER_VERIFICATION', true),
        'allow_resend_code' => env('ALLOW_RESEND_CODE', true),
        'cleanup_expired_codes' => env('CLEANUP_EXPIRED_CODES', true),
        'cleanup_interval_hours' => env('CLEANUP_INTERVAL_HOURS', 24),
    ],

    'redirects' => [
        'after_successful_verification' => env('VERIFICATION_SUCCESS_REDIRECT', '/dashboard'),
        'after_password_set' => env('PASSWORD_SET_SUCCESS_REDIRECT', '/dashboard'),
        'on_error' => env('VERIFICATION_ERROR_REDIRECT', '/verify-code'),
    ],

    'messages' => [
        'registration_success' => 'Registration successful! Please check your email for the verification code.',
        'verification_sent' => 'Verification code sent to your email.',
        'verification_success' => 'Email verified successfully! Please set your permanent password.',
        'password_set_success' => 'Password set successfully! You can now log in normally.',
        'code_expired' => 'Verification code has expired. Please request a new one.',
        'too_many_attempts' => 'Too many failed attempts. Please request a new verification code.',
        'invalid_code' => 'Invalid verification code. Please check and try again.',
    ],
];