<?php

namespace App\Modules\Resource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Modules\Resource\Models\Resource;

class ResourceField extends Model
{
    
    protected static function boot() {
	    parent::boot();
	    // create slug on creating
	    static::creating(function ($field) {
	        $field->slug = Str::slug($field->title);
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

    public function subfields(){
        return $this->hasMany(\App\Modules\Resource\Models\ResourceSubField::class, 'parent_field', 'slug');
    }

    public function resources(){
        return $this->hasMany(\App\Modules\Resource\Models\Resource::class, 'field', 'slug');
    }

    public function queryResources($query = null, $limit= null, $paginate = null, $order = null){
        $resources = Resource::where($query);
        if($limit){
            return $resources->limit($limit)->get();
        }
        if($paginate){
            return $resources->paginate($paginate);
        }
        if($order){
            return $resources->orderBy($order)->get();
        }
    }

   
}
