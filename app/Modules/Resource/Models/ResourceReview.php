<?php

namespace App\Modules\Resource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResourceReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'resource_id',
        'user_id',
        'name',
        'rating',
        'comment',
    ];

    public function user() {
         return $this->belongsTo(\App\Models\User::class);
    }

    public function resource() {
         return $this->belongsTo(\App\Models\Resource::class);
    }
    
    protected static function newFactory()
    {
        return \App\Modules\Resource\Database\factories\ResourceReviewFactory::new();
    }
}
