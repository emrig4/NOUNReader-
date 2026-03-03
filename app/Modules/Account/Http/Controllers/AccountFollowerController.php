<?php

namespace App\Modules\Account\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Modules\Account\Models\AccountFollow;

class AccountFollowerController extends Controller
{
    



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        $followings = auth()->user()
            ->followings()
            ->paginate(10);
        return view('account.followings', compact('followings'));
    }



    public function follow($userId)
    {
       
        // return view('public.account.favorite.index', compact('ebooks'));
        AccountFollow::firstOrCreate([
            'followee_id' => $userId,
            'follower_id' => auth()->user()->id,

        ]);
        notify()->success('Successfully followed author', 'Success');
        return back();
    }

    public function unfollow($userId)
    {
        
        AccountFollow::where([
            'followee_id' => $userId,
            'follower_id' => auth()->user()->id,
        ])->delete();

        notify()->success('Successfully unfollowed author', 'Success');
        return back();
    }
}
