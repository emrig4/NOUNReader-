<?php

namespace App\Modules\Resource\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Resource\Models\ResourceField;
use App\Modules\Resource\Models\Resource;


class ResourceFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('resource::fields.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('resource::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($slug, Request $request)
    {   
        $field = ResourceField::where('slug', $slug)->firstOrFail();
        $search = $request->query('search');
        $subfield = $request->query('subfield');
        $type = $request->query('type');
         if($search || $subfield || $type){
            
            $query = Resource::where(function($q)use($search, $subfield, $type, $slug){
                if($search){
                    $q->where('title', 'like', '%' . $search .'%');
                }
                if($type){
                    $q->where('type', $type);
                    
                }
                if($subfield){
                    $q->whereIn('sub_fields',  [$subfield]);
                }
                

               $q->where('field', $slug);
            });

            $resources =  $query->get();
            
            return view('resource::fields.search', ['field' => $field, 'resources' => $resources]);
        }
        return view('resource::fields.field', ['field' => $field]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('resource::edit');
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
