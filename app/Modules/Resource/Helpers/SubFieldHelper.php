<?php

namespace App\Modules\Resource\Helpers;
use Illuminate\Support\Str;
use App\Modules\Resource\Models\ResourceSubField;

class SubFieldHelper
{

    public static function processSubfields($subFields){
        $sluggedSubfields = array_map(function($e){
            return Str::slug($e);
        }, explode(',', $subFields));

        foreach ($sluggedSubfields as $subField) {
            $exists = ResourceSubField::firstWhere(['slug' => $subField]) !== null;
            if($exists) continue;
            ResourceSubField::create([
                'title' => ucwords(str_replace('-', ' ', $subField))
            ]);
        }
        $stringedSubfields = implode(',', $sluggedSubfields);
        return $stringedSubfields;
    }
}
