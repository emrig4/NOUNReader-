<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class VerificationService
{
    /**
     * Generate a 6-digit verification code
     */
    public function generateVerificationCode(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Generate and send verification code for registration
     * Now allows email reuse for failed verification attempts
     */
    public function generateAndSendCode(string $email, ?string $firstName = null, ?string $lastName = null): string
    {
        // Generate verification code
        $code = $this->generateVerificationCode();

        // Check if email already has a permanent user (completed registration)
        $existingPermanentUser = User::where('email', $email)
            ->where('has_set_permanent_password', true)
            ->first();

        if ($existingPermanentUser) {
            throw new \Exception('This email has already been used for registration.');
        }

        // Clean up any existing temporary verification records for this email
        // This allows the same email to be used again for fresh registration
        User::where('email', $email)
            ->where('has_set_permanent_password', false)
            ->where(function ($query) {
                $query->where('first_name', '')
                      ->orWhereNull('first_name');
            })
            ->delete();

        Log::info('Cleaned up previous verification attempts for email', ['email' => $email]);

        // Create new temporary user record for verification
        $user = User::create([
            'first_name' => $firstName ?: '',
            'last_name' => $lastName ?: '',
            'email' => $email,
            'password' => Hash::make($code), // Store hashed verification code as password
            'has_set_permanent_password' => false,
            'email_verified_at' => null,
        ]);

        // Send verification code email
        try {
            Mail::to($user->email)->send(new \App\Mail\VerificationCodeMail($user, $code));
            Log::info('Verification code sent', ['user_id' => $user->id, 'email' => $user->email]);
        } catch (\Exception $e) {
            Log::error('Failed to send verification code', [
                'user_id' => $user->id, 
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);
            // Don't throw exception - still allow user to proceed with verification
        }

        return $code;
    }

    /**
     * Verify the verification code and return user
     */
    public function verifyCodeAndGetUser(string $email, string $code): ?User
    {
        $user = User::where('email', $email)
            ->where('has_set_permanent_password', false)
            ->first();
        
        if (!$user) {
            Log::warning('User not found during verification', ['email' => $email]);
            return null;
        }

        // Verify the code against the stored hash
        if (Hash::check($code, $user->password)) {
            Log::info('Verification successful', ['user_id' => $user->id, 'email' => $email]);
            return $user;
        }

        Log::warning('Invalid verification code', ['email' => $email, 'code' => $code]);
        return null;
    }

    /**
     * Clean up failed verification attempts for an email
     * This is automatically called during new registration attempts
     */
    public function cleanupFailedVerification(string $email): void
    {
        $deleted = User::where('email', $email)
            ->where('has_set_permanent_password', false)
            ->where(function ($query) {
                $query->where('first_name', '')
                      ->orWhereNull('first_name');
            })
            ->delete();

        if ($deleted > 0) {
            Log::info('Cleaned up failed verification attempts', ['email' => $email, 'deleted' => $deleted]);
        }
    }

    /**
     * Check if email is permanently registered (cannot be reused)
     */
    public function isEmailPermanentlyRegistered(string $email): bool
    {
        return User::where('email', $email)
            ->where('has_set_permanent_password', true)
            ->exists();
    }

    /**
     * Get all expired verification attempts and clean them up
     * Call this periodically to keep database clean
     */
    public function cleanupExpiredVerificationAttempts(): void
    {
        // Clean up verification attempts older than 2 hours
        $expiredTime = Carbon::now()->subHours(2);
        
        $deleted = User::where('has_set_permanent_password', false)
            ->where(function ($query) {
                $query->where('first_name', '')
                      ->orWhereNull('first_name');
            })
            ->where('created_at', '<', $expiredTime)
            ->delete();

        if ($deleted > 0) {
            Log::info('Cleaned up expired verification attempts', ['count' => $deleted]);
        }
    }

    /**
     * Validate verification code format
     */
    public function isValidCodeFormat(string $code): bool
    {
        return preg_match('/^\d{6}$/', $code);
    }
}