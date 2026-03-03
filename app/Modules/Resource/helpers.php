<?php
use  App\Modules\Setting\Models\Setting;
use  App\Models\User;
use  App\Modules\Account\Models\AccountFavorite;


if (! function_exists('is_favorite')) {
    
    function is_favorite($resourceId)
    {
        return AccountFavorite::where([
            'resource_id' => $resourceId,
            'user_id' => auth()->user()->id
        ])->first();
    }
}

