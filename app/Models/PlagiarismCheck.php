<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlagiarismCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'original_text',
        'word_count',
        'plagiarism_score',
        'sources',
        'status',
        'check_time',
        'session_id',
    ];

    protected $casts = [
        'sources' => 'array',
        'plagiarism_score' => 'float',
        'check_time' => 'float',
        'word_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns this plagiarism check.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include checks for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        if ($userId) {
            return $query->where('user_id', $userId);
        }
        
        // For guest users, return checks with null user_id
        return $query->whereNull('user_id');
    }

    /**
     * Get the plagiarism level based on score.
     */
    public function getPlagiarismLevelAttribute()
    {
        $score = $this->plagiarism_score;
        
        if ($score <= 10) {
            return 'Low';
        } elseif ($score <= 25) {
            return 'Medium';
        } else {
            return 'High';
        }
    }

    /**
     * Get formatted sources for display.
     */
    public function getFormattedSourcesAttribute()
    {
        if (!$this->sources || !is_array($this->sources)) {
            return [];
        }

        return collect($this->sources)->map(function ($source) {
            return [
                'title' => $source['title'] ?? 'Unknown Source',
                'url' => $source['url'] ?? '',
                'match_percentage' => $source['match'] ?? 0,
            ];
        });
    }

    /**
     * Get the file name if any (for future file upload feature).
     */
    public function getFileNameAttribute()
    {
        // Placeholder for future file upload feature
        return null;
    }
}
