<?php

namespace App\Modules\Account\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class AccountFollow extends Model
{
    use HasFactory;

    protected $fillable = ['follower_id', 'followee_id'];
    public $timestamps = false;
    

    public function followee(){
        return $this->belongsTo(User::class,  'followee_id');
    }

    public function follower(){
        return $this->belongsTo(User::class,  'follower_id');
    }
    protected static function newFactory()
    {
        return \App\Modules\Account\Database\factories\AccountReadFactory::new();
    }
}
