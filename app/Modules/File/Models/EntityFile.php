<?php

namespace App\Modules\File\Models;

use Illuminate\Database\Eloquent\Model;

class EntityFile extends Model
{

    protected $fillable = [];
    
    /**
     * Get all of the models that own comments.
     */
    public function entity ()
    {
        return $this->morphTo();
    }
}
