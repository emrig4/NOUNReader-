<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class AiDetection extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'original_text',
        'word_count',
        'ai_score',
        'confidence_level',
        'indicators',
        'writing_style',
        'status',
        'detection_time',
        'session_id',
    ];

    protected $casts = [
        'indicators' => 'array',
        'writing_style' => 'array',
        'ai_score' => 'decimal:2',
        'detection_time' => 'decimal:3',
    ];

    /**
     * Get the user that owns this detection.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for filtering by user.
     */
    public function scopeForUser($query, $userId = null)
    {
        if ($userId) {
            return $query->where('user_id', $userId);
        }
        
        return $query->whereNull('user_id');
    }

    /**
     * Scope for filtering by confidence level.
     */
    public function scopeByConfidenceLevel($query, $level)
    {
        return $query->where('confidence_level', $level);
    }

    /**
     * Scope for filtering by date range.
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Get AI score as percentage.
     */
    public function getAiScoreAttribute($value): float
    {
        return round($value, 2);
    }

    /**
     * Get confidence level display text.
     */
    public function getConfidenceTextAttribute(): string
    {
        return match($this->confidence_level) {
            'high' => 'High Confidence',
            'medium' => 'Medium Confidence',
            'low' => 'Low Confidence',
            default => 'Unknown',
        };
    }

    /**
     * Get AI detection result text.
     */
    public function getDetectionResultAttribute(): string
    {
        if ($this->ai_score >= 70) {
            return 'Likely AI Generated';
        } elseif ($this->ai_score >= 40) {
            return 'Uncertain';
        } else {
            return 'Likely Human Written';
        }
    }

    /**
     * Get confidence level color class.
     */
    public function getConfidenceClassAttribute(): string
    {
        return match($this->confidence_level) {
            'high' => 'text-red-600',
            'medium' => 'text-yellow-600',
            'low' => 'text-green-600',
            default => 'text-gray-600',
        };
    }

    /**
     * Get AI score color class.
     */
    public function getScoreClassAttribute(): string
    {
        if ($this->ai_score >= 70) {
            return 'text-red-600';
        } elseif ($this->ai_score >= 40) {
            return 'text-yellow-600';
        } else {
            return 'text-green-600';
        }
    }

    /**
     * Check if content is likely AI generated.
     */
    public function isLikelyAi(): bool
    {
        return $this->ai_score >= 70;
    }

    /**
     * Check if content is likely human written.
     */
    public function isLikelyHuman(): bool
    {
        return $this->ai_score < 40;
    }

    /**
     * Get detection indicators summary.
     */
    public function getIndicatorsSummaryAttribute(): string
    {
        if (!$this->indicators) {
            return 'No specific indicators found';
        }

        $indicators = $this->indicators;
        $summary = [];

        if (isset($indicators['complexity']['score']) && $indicators['complexity']['score'] > 0.7) {
            $summary[] = 'High vocabulary complexity';
        }

        if (isset($indicators['repetition']['score']) && $indicators['repetition']['score'] > 0.6) {
            $summary[] = 'Repetitive patterns';
        }

        if (isset($indicators['style']['score']) && $indicators['style']['score'] > 0.8) {
            $summary[] = 'Consistent writing style';
        }

        if (isset($indicators['structure']['score']) && $indicators['structure']['score'] > 0.75) {
            $summary[] = 'Structured content pattern';
        }

        return implode(', ', $summary) ?: 'Minor indicators found';
    }

    /**
     * Get writing style summary.
     */
    public function getStyleSummaryAttribute(): string
    {
        if (!$this->writing_style) {
            return 'No style analysis available';
        }

        $style = $this->writing_style;
        $summary = [];

        if (isset($style['avg_sentence_length'])) {
            $length = $style['avg_sentence_length'];
            if ($length > 25) {
                $summary[] = 'Long sentences';
            } elseif ($length < 12) {
                $summary[] = 'Short sentences';
            } else {
                $summary[] = 'Medium sentence length';
            }
        }

        if (isset($style['vocabulary_diversity'])) {
            $diversity = $style['vocabulary_diversity'];
            if ($diversity > 0.8) {
                $summary[] = 'High vocabulary diversity';
            } elseif ($diversity < 0.5) {
                $summary[] = 'Low vocabulary diversity';
            }
        }

        return implode(', ', $summary) ?: 'Neutral writing style';
    }

    /**
     * Boot method for model events.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->session_id)) {
                $model->session_id = session()->getId();
            }
        });
    }
}