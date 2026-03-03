<?php

namespace App\Modules\File\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Modules\File\Helpers\FilesIcon;

class File extends Model
{
    protected $guarded = [];

    protected $casts = [];

    protected static function boot()
    {
        parent::boot();

        // 🚨 FIXED: Commented out automatic S3 file deletion
        // This prevents files from being deleted when database records are deleted
        /*
        static::deleted(function ($file) {
            Storage::disk($file->disk)->delete($file->getOriginal('path'));
        });
        */

        // 🔧 Alternative: Manual deletion control
        static::deleting(function ($file) {
            // Check if manual deletion is requested
            if (request()->has('delete_s3_file') && request('delete_s3_file') === 'true') {
                Storage::disk($file->disk)->delete($file->getOriginal('path'));
            }
            // Otherwise, keep the file in S3 but remove database record
        });
    }
    
    public static function findById($id)
    {
        return static::where('id', $id)->first();
    }
    
    public static function findByIds($ids)
    {
        return static::whereIn('id', $ids)->get();
    }
    
    public function uploader()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    
    public function icon()
    {
        return FilesIcon::getIcon($this->mime);
    }
    
    

    public function url(){
        $path = rawurlencode($this->path);
        $fileLink =  Storage::disk($this->disk)->url($path);
        return $fileLink;
        // return $this->path;
    }
    
    

    public function isImage()
    {
        return strtok($this->mime, '/') === 'image';
    }
    
    public function isVideo()
    {
        return strtok($this->mime, '/') === 'video';
    }

    /**
     * 🔧 Manual S3 deletion method (optional - only when you want to delete)
     */
    public function deleteFileFromStorage()
    {
        return Storage::disk($this->disk)->delete($this->path);
    }

    /**
     * 🛡️ Safe delete method - preserves S3 files by default
     * 
     * @param bool $deleteS3File Set to true only if you want to delete S3 file too
     * @return bool
     */
    public function safeDelete($deleteS3File = false)
    {
        if ($deleteS3File) {
            $this->deleteFileFromStorage();
        }
        return $this->delete();
    }
}