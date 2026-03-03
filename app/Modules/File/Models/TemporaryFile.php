<?php

namespace App\Modules\File\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Modules\File\Helpers\FilesIcon;


class TemporaryFile extends Model
{   

    protected static function boot() {
        parent::boot();
        static::creating(function ($file) {
            $file->user_id = auth()->user()->id;
        });
    }

    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    protected $casts = [];

    
    public function uploader()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function icon()
    {
        return FilesIcon::getIcon($this->mime);
    }
    

    public function isPDF()
    {
        return strtok($this->mime, '/') === 'pdf';
    }
    
    public function isDOC()
    {
        return strtok($this->mime, '/') === 'doc';
    }
    
}
