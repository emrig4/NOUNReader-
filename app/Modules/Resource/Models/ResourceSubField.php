<?php

namespace App\Modules\Resource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Modules\Resource\Models\Resource;

class ResourceSubField extends Model
{
	
    /**
     * The attributes that should be guarded for arrays.
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    protected static function boot() {
	    parent::boot();
	    // create slug on creating
	    static::creating(function ($subfield) {
	        $subfield->slug = Str::slug($subfield->title);
	    });
	}

    public function field(){
        return $this->belongsTo(\App\Modules\Resource\Models\ResourceField::class, 'slug', 'parent_field');
    }

    public function resources(){
         return Resource::whereIn('sub_fields', [$this->slug])->get();
    }

    public function resourceCount(){
       $resources = Resource::whereIn('sub_fields', [$this->slug]);
       // DB::table('stories')->whereRaw("find_in_set('4',author)")->get();
       return $resources->count();

    }
}
