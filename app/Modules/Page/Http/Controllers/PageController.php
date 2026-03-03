<?php

namespace App\Modules\Page\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function faq()
    {
       return view('pages.faq');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function privacy(Request $request)
    {
        return view('pages.privacy');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function copyright(Request $request)
    {
        return view('pages.copyright');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function howItWorks()
    {
        return view('pages.howto');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('page::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
