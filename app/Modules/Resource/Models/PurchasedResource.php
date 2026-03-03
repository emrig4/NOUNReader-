<?php

namespace App\Modules\Resource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Payment\Models\Transaction;
use App\Models\User;

class PurchasedResource extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_delivered' => 'boolean',
        'last_accessed_at' => 'datetime',
        'access_expires_at' => 'datetime',
    ];

    public function ebook()
    {
        return $this->belongsTo(Resource::class, 'resource_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
    
    protected static function newFactory()
    {
        return \App\Modules\Resource\Database\factories\PurchasedResourceFactory::new();
    }
}
