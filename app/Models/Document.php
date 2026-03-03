<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'original_path',
        'approved_path',
        'original_filename',
        'approved_filename',
        'status',
        'rejection_reason',
    ];

    protected $casts = [
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the document
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the original file URL
     */
    public function getOriginalUrlAttribute(): ?string
    {
        return $this->original_path ? Storage::disk('s3')->url($this->original_path) : null;
    }

    /**
     * Get the approved file URL
     */
    public function getApprovedUrlAttribute(): ?string
    {
        return $this->approved_path ? Storage::disk('s3')->url($this->approved_path) : null;
    }

    /**
     * Check if document is pending review
     */
    public function isPendingReview(): bool
    {
        return $this->status === 'pending_review';
    }

    /**
     * Check if document is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if document is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Get status display name
     */
    public function getStatusNameAttribute(): string
    {
        return match($this->status) {
            'pending_review' => 'Pending Review',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    /**
     * Scope for pending review documents
     */
    public function scopePendingReview($query)
    {
        return $query->where('status', 'pending_review');
    }

    /**
     * Scope for approved documents
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for rejected documents
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}