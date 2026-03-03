<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class SpamProtectionService
{
    /**
     * Blocked email domains (known spam/disposable)
     */
    protected $blockedDomains = [
        'tempmail.com', 'throwaway.email', 'guerrillamail.com', 'mailinator.com',
        '10minutemail.com', 'fakeinbox.com', 'sharklasers.com', 'spam4.me',
        'grr.la', 'maildrop.cc', 'getnada.com', 'yopmail.com', 'trashmail.com',
        'dispostable.com', 'emailondeck.com', 'fake-email.com', 'spamgourmet.com',
        'mailnesia.com', 'tempr.email', 'mintemail.com', 'mailcatch.com',
        'spamcowboy.com', 'spamcowboy.net', 'spamcowboy.org', 'spamhead.com',
        'spamify.com', 'tempail.com', 'emailisvalid.com', 'tempinbox.com',
        'mohmal.com', 'tempail.org', 'tempmailaddress.com', 'burnermail.io',
        'getairmail.com', 'meltmail.com', 'spambox.us', 'mailtemp.net',
        'discard.email', 'discardmail.com', 'discardmail.de', 'spamdrom.com',
        'crazymailing.com', 'mailzilla.com', 'mailzilla.net', 'tempemail.net',
        'spamfree24.org', 'jetable.org', 'spamavert.com', 'tempomail.com',
    ];

    /**
     * Spam patterns in names
     */
    protected $spamNamePatterns = [
        '/cash/i', '/money/i', '/gift/i', '/bonus/i', '/free/i', '/win/i',
        '/winner/i', '/prize/i', '/lottery/i', '/bitcoin/i', '/crypto/i',
        '/investment/i', '/income/i', '/earn/i', '/work from home/i',
    ];

    /**
     * Check if registration is spam
     * @return array ['blocked' => true/false, 'reason' => message]
     */
    public function isSpam($request): array
    {
        $ip = $request->ip();
        $email = strtolower($request->email ?? '');
        $name = trim(($request->first_name ?? '') . ' ' . ($request->last_name ?? ''));

        // 1. Rate limiting - max 3 registrations per IP per hour
        $rateKey = "reg_{$ip}";
        if (RateLimiter::tooManyAttempts($rateKey, 3)) {
            Log::warning('Spam blocked: Rate limit', ['ip' => $ip]);
            return ['blocked' => true, 'reason' => 'Too many registrations. Please try again later.'];
        }
        RateLimiter::hit($rateKey, 3600);

        // 2. Honeypot check - if hidden fields are filled, it's a bot
        if (!empty($request->middle_name) || !empty($request->website)) {
            Log::warning('Spam blocked: Honeypot', ['ip' => $ip]);
            return ['blocked' => true, 'reason' => 'Registration blocked.'];
        }

        // 3. Block disposable email domains
        if (!empty($email)) {
            $domain = explode('@', $email)[1] ?? '';
            if (in_array(strtolower($domain), $this->blockedDomains)) {
                Log::warning('Spam blocked: Disposable email', ['email' => $email]);
                return ['blocked' => true, 'reason' => 'Please use a permanent email address.'];
            }
        }

        // 4. Check name for spam patterns
        if (!empty($name)) {
            foreach ($this->spamNamePatterns as $pattern) {
                if (preg_match($pattern, $name)) {
                    Log::warning('Spam blocked: Spam name', ['name' => $name, 'ip' => $ip]);
                    return ['blocked' => true, 'reason' => 'Please enter a valid name.'];
                }
            }
        }

        // 5. Name too short
        if (strlen($name) < 3) {
            return ['blocked' => true, 'reason' => 'Name is too short.'];
        }

        return ['blocked' => false, 'reason' => ''];
    }
}