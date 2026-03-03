<?php

namespace App\Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
     /**
     * The attributes that should be guarded for arrays.
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];
}
