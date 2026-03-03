<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class UserPlagiarismLimit extends Model
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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns this limit.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if user can perform a plagiarism check.
     */
    public function canPerformCheck($wordCount = 0)
    {
        $config = $this->getLimitConfig();
        
        // Check remaining checks
        if ($this->remaining_checks <= 0) {
            return false;
        }
        
        // Check remaining words
        if ($this->remaining_words < $wordCount) {
            return false;
        }
        
        return true;
    }

    /**
     * Increment usage for this user.
     */
    public function incrementUsage($checks = 1, $words = 0)
    {
        $this->increment('checks_used', $checks);
        $this->increment('words_used', $words);
        $this->save();
        
        return $this;
    }

    /**
     * Get remaining checks for today.
     */
    public function getRemainingChecksAttribute()
    {
        $config = $this->getLimitConfig();
        return max(0, $config['daily_checks'] - $this->checks_used);
    }

    /**
     * Get remaining words for today.
     */
    public function getRemainingWordsAttribute()
    {
        $config = $this->getLimitConfig();
        return max(0, $config['daily_words'] - $this->words_used);
    }

    /**
     * Get the limit configuration for this user.
     */
    private function getLimitConfig()
    {
        // For now, return free tier limits
        // In future versions, this could check subscription status
        $user = $this->user;
        
        return [
            'daily_checks' => $user ? 20 : 5,
            'daily_words' => 1000,
        ];
    }

    /**
     * Reset daily usage (called by scheduled task).
     */
    public static function resetDaily()
    {
        DB::table('user_plagiarism_limits')
            ->where('check_date', '<', now()->toDateString())
            ->delete();
    }
}
