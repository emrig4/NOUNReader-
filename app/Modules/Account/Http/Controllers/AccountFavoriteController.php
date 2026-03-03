<?php

namespace App\Modules\Account\Http\Controllers;

use Illuminate\Routing\Controller;

class AccountFavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $favorites = auth()->user()
            ->favoriteResources()
            ->paginate(10);
        return view('account.favorites', compact('favorites'));
    }


    public function store($resourceId)
    {
        $favorites = auth()->user()
            ->favoriteResources()->create(['resource_id' => $resourceId,  'user_id' => auth()->user()->id]);
        return redirect()->back();
    }


    public function destroy($resourceId)
    {
        auth()->user()->favoriteResources()->delete($resourceId);
        return redirect()->back();
    }
}
