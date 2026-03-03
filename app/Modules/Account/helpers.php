<?php
use  App\Modules\Setting\Models\Setting;
use  App\Models\User;
use  App\Modules\Account\Models\AccountFollow;

if (! function_exists('account')) {

    function account($email = null)
    {
        if(!$email){
            return auth()->user()->account;
        }

        return User::whereEmail($email)->firstOrFail()->account;
    }
}


if (! function_exists('is_follow')) {

    function is_follow($userId)
    {
        return AccountFollow::where([
            'followee_id' => $userId,
            'follower_id' => auth()->user()->id
        ])->first();
    }
}

if (! function_exists('has_profile')) {

    function has_profile($userId)
    {
        return User::where(
            'id', $userId
        )->OrWhere('username',$userId)->first();
    }
}


