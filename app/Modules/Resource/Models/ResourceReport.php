<?php

namespace App\Modules\Resource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Modules\Resource\Models\Resource;
use App\Models\User;

class ResourceReport extends Model
{

    /**
     * The attributes that should be guarded for arrays.
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function resource(){
        return $this->belongsTo(Resource::class);
    }

}
