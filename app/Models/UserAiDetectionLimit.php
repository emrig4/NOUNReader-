<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAiDetectionLimit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'check_date',
        'checks_used',
        'words_used',
    ];

    protected $casts = [
        'check_date' => 'date',
        'checks_used' => 'integer',
        'words_used' => 'integer',
    ];

    /**
     * Get the user that owns this limit.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if user can perform a check.
     */
    public function canPerformCheck(int $wordCount = 0): bool
    {
        $config = $this->getLimitConfig();

        $remainingChecks = $config['daily_checks'] - $this->checks_used;
        $remainingWords = $config['daily_words'] - $this->words_used;

        return $remainingChecks > 0 && $remainingWords >= $wordCount;
    }

    /**
     * Get remaining checks.
     */
    public function getRemainingChecksAttribute(): int
    {
        $config = $this->getLimitConfig();
        return max(0, $config['daily_checks'] - $this->checks_used);
    }

    /**
     * Get remaining words.
     */
    public function getRemainingWordsAttribute(): int
    {
        $config = $this->getLimitConfig();
        return max(0, $config['daily_words'] - $this->words_used);
    }

    /**
     * Increment usage.
     */
    public function incrementUsage(int $checks = 1, int $words = 0): void
    {
        $this->increment('checks_used', $checks);
        $this->increment('words_used', $words);
    }

    /**
     * Decrement usage.
     */
    public function decrementUsage(int $checks = 1, int $words = 0): void
    {
        $this->decrement('checks_used', $checks);
        $this->decrement('words_used', $words);
    }

    /**
     * Get usage percentage.
     */
    public function getUsagePercentageAttribute(): array
    {
        $config = $this->getLimitConfig();

        return [
            'checks' => round(($this->checks_used / $config['daily_checks']) * 100, 1),
            'words' => round(($this->words_used / $config['daily_words']) * 100, 1),
        ];
    }

    /**
     * Check if limits are exhausted.
     */
    public function isExhausted(): bool
    {
        return $this->remaining_checks <= 0 || $this->remaining_words <= 0;
    }

    /**
     * Check if user is approaching limits.
     */
    public function isApproachingLimit(): bool
    {
        $usage = $this->usage_percentage;
        return $usage['checks'] >= 80 || $usage['words'] >= 80;
    }

    /**
     * Get the limit configuration for this user.
     */
    private function getLimitConfig(): array
    {
        // Simple approach like plagiarism checker - no complex user method calls
        $user = $this->user;
        
        return [
            'daily_checks' => $user ? 30 : 10,  // Same as plagiarism checker
            'daily_words' => 1500,
        ];
    }

    /**
     * Scope for today's limits.
     */
    public function scopeToday($query)
    {
        return $query->where('check_date', today());
    }

    /**
     * Scope for specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->where('check_date', $date);
    }

    /**
     * Scope for user.
     */
    public function scopeForUser($query, $userId = null)
    {
        if ($userId) {
            return $query->where('user_id', $userId);
        }
        
        return $query->whereNull('user_id');
    }

    /**
     * Boot method for model events.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Set default values
            if (is_null($model->checks_used)) {
                $model->checks_used = 0;
            }
            if (is_null($model->words_used)) {
                $model->words_used = 0;
            }
        });
    }

    /**
     * Get usage status for display.
     */
    public function getStatusAttribute(): array
    {
        $config = $this->getLimitConfig();

        return [
            'checks_used' => $this->checks_used,
            'checks_total' => $config['daily_checks'],
            'checks_remaining' => $this->remaining_checks,
            'words_used' => $this->words_used,
            'words_total' => $config['daily_words'],
            'words_remaining' => $this->remaining_words,
            'usage_percentage' => $this->usage_percentage,
            'is_exhausted' => $this->isExhausted(),
            'is_approaching' => $this->isApproachingLimit(),
        ];
    }
}