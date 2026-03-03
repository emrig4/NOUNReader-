<?php

namespace App\Modules\Account\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Resource\Models\Resource;

class AccountFavorite extends Model
{
    public $fillable = [ 'resource_id', 'user_id'];
    public $timestamps = false;

    public function resource(){
        return $this->belongsTo(Resource::class);
    }
}
