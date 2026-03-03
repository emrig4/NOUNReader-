<?php

namespace App\Modules\Resource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class ResourceType extends Model
{
    protected static function boot() {
	    parent::boot();
	    // create slug on creating
	    static::creating(function ($type) {
	        $type->slug = Str::slug($type->title);
	    });
	}

    //
    /**
     * The attributes that should be guarded for arrays.
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];

}
