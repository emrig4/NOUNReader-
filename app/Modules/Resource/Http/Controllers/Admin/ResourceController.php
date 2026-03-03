<?php

namespace App\Modules\Resource\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Resource\Models\Resource;
use App\Modules\Resource\Models\ResourceField;
use App\Modules\Resource\Http\Requests\ResourceStoreRequest;
use App\Modules\Resource\Http\Requests\ResourceUpdateRequest;  // ✅ CORRECT
use Illuminate\Support\Str;
use App\Modules\File\Http\Traits\FileUploadTrait;
use App\Modules\File\Http\Traits\FileProcessTrait;
use DB;
use Bouncer;
use App\Modules\Resource\Models\ResourceAuthor;
use App\Models\User;
use App\Modules\Wallet\Http\Traits\WalletTrait;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use App\Imports\ResourcePublishImport;
use App\Exports\S3FilesExport;
use Maatwebsite\Excel\Facades\Excel;


class ResourceController extends Controller
{       

    use FileUploadTrait;
    use FileProcessTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $resources = Resource::withoutGlobalScopes()->get();
            $data = collect($resources);
            // $data =  PaystackSubscription::latest()->get();
            return Datatables::of($data)
                // ->addIndexColumn()
                ->setRowId('id')
                ->addColumn('action', function($row){
                    
                    $showUrl = route('admin.resources.show', $row->slug);
                    $editUrl = route('admin.resources.edit', $row->slug);
                   
                    $toggleBtn = route('admin.resources.unpublish', $row->id);
                    $toggleState = $row->is_published ? 'Disable' : 'Enable';

                    $btn = "<a href='$showUrl' class='text-xs h-4 btn btn_primary'>View</a>";
                    $btn = $btn."<a href='$editUrl' class='text-xs h-4 btn btn_secondary'>Edit</a>";
                    $btn = $btn."<a href='$toggleBtn' class='text-xs h-4 btn btn_warning'> $toggleState </a>";
         
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);

            // return new JsonResponse(Datatables::of($data)->make(true));

        }else{
            $resources = Resource::all();
            return view('admin.resources.index', compact('resources'));
        }
    }


    /**
     * Show the form for uploading a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createUpload()
    {
        return view('resource.create.upload'); 

    }


    /**
     * Show the form for publishin a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createPublish()
    {
       
        return view('resource.create.publish');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Modules\Resource\Http\Requests\ResourceStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ResourceStoreRequest $request)
    {   
        try {

            $tempfile = $request->input('tmpfile_name');
            $authUser = auth()->user();

            $file = FileUploadTrait::transferTmpFile($tempfile, 's3');
            if($file){

                $params = $request->all();
                $params['page_count'] = $file->page_count;

                // $params['cover'] = $file->page_count;


                $resource = Resource::create($params);
                $entity = DB::table('entity_files')->insert([
                    'file_id' => $file->id,
                    'entity_type'=>'App\Modules\Resource\Models\Resource',
                    'entity_id'=> $resource->id,
                    'label'=>'main_file',
                    'created_at'=>$resource->created_at,
                    'updated_at'=>$resource->updated_at,
                ]);


                /*  STORE  AUTHOR/CO AUTHOR*/

                if($request->has('author')){
                    // save publisher user as lead author
                    ResourceAuthor::create([
                        'fullname' =>  $request->author,
                        'resource_id' => $resource->id,
                        'is_lead' => 1
                    ]);
                }else{
                    // save publisher user as lead author
                    ResourceAuthor::create([
                        'fullname' => $authUser->first_name . ' ' .  $authUser->last_name,
                        'resource_id' => $resource->id,
                        'is_lead' => 1,
                        'username' => $authUser->username
                    ]);
                }
               

                // save coauthors
                $coauthors = explode(',',  $request->coauthors);
                $users = [];
                foreach($coauthors as $coauthor){
                    $resourceAuthor = new ResourceAuthor;
                    $resourceAuthor->resource_id = $resource->id;
                    $resourceAuthor->fullname = $coauthor;

                    $user = User::whereRaw("CONCAT(`first_name`, ' ', `last_name`) LIKE ?", ['%'.$coauthor.'%'])
                            ->first();
                    if($user){
                        $resourceAuthor->username = $user->username;
                    }

                    $resourceAuthor->save();
                }
            }else{
                return redirect()->back()->with('notify.message', 'something went wrong');
            }



        } catch (Exception $e) {
            
        }


       // check if api or web
        if($request->header('Accept') == 'application/json'){
            return;
        }else{
            return redirect()->route('account.index');
        }
    }
    
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $resource = Resource::withoutGlobalScopes()->where('slug', $slug)->first();
        if($resource){
            $mainFile = $resource->filterFiles('main_file')->first();
        }
        return view('admin.resources.show', ['resource' => $resource, 'mainFile' => $mainFile ?? '']);

    }
    


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        
        // ✅ FIXED: Use withoutGlobalScopes to find unpublished resources
        $resource = Resource::withoutGlobalScopes()->where('slug', $slug)->first();
        $existingSubfields = explode(',', $resource->sub_fields);
        // dd($existingSubfields);
        $existingCoauthors = ResourceAuthor::where('resource_id', $resource->id)
                                            ->where('is_lead', false)->get();
        $mainFile = $resource->filterFiles('main_file')->first() ;
        // dd($resource);
        return view('admin.resources.edit', [
            'resource' => $resource, 
            'mainFile' => $mainFile, 
            'existingSubfields' => $existingSubfields,
            'existingCoauthors' => $existingCoauthors
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Modules\Resource\Http\Requests\ResourceUpdateRequest  $request
     * @param  int  $slug
     * @return \Illuminate\Http\Response
     */
    public function update(ResourceUpdateRequest $request,  $slug)
    {

        try {
            $authUser = auth()->user();
            
            // ✅ FIXED: Use withoutGlobalScopes to find unpublished resources
            $resource = Resource::withoutGlobalScopes()->where('slug', $slug)->first();

            $params = $request->all();
            if($params['currency'] == ''){
                $params['price'] = null;
            }
            $resource->update( $params );

            // dd($resource);
            

            // save author
            $resource->author()->update(['fullname' => $request->author,  'username' => \Str::slug($request->author) ]);
             

            // save coauthors
            $authors = explode(',',  $request->coauthors);
            $existingCoauthors = ResourceAuthor::where('resource_id', $resource->id)->where('is_lead', false)->delete();


            // dd($existingCoauthors);



            $users = [];
            foreach($authors as $author){
                $resourceAuthor = new ResourceAuthor;
                $resourceAuthor->resource_id = $resource->id;
                $resourceAuthor->fullname = $author;

                $user = User::whereRaw("CONCAT(`first_name`, ' ', `last_name`) LIKE ?", ['%'.$author.'%'])
                        ->first();
                if($user){
                    $resourceAuthor->username = $user->username;
                }

                $resourceAuthor->save();
            }
            


        } catch (Exception $e) {
            
        }


       // check if api or web
        if($request->header('Accept') == 'application/json'){
            return;
        }else{
            return redirect()->back();
        }
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function listS3Files(Request $request){
        $response = Excel::download(new S3FilesExport, 'resources.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        ob_end_clean();
        return $response;
    }


    public function resourcePublishImport(Request $request)
    {
        try {

            $file = $request->file('sheet');
           
            Excel::import(new ResourcePublishImport, $file);
            notify()->success('Files publised successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            notify()->error( $th->getMessage() );
            return redirect()->back();
        }
        // return response()->json(['message' => 'Users successfully imported']);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function unpublish($id)
    {   

        $resource = Resource::withoutGlobalScopes()->find($id);
        $resource->update([
            'is_published' => DB::raw('NOT is_published')
        ]);
        
        notify()->success('Updated');
        return back();
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Resource $resource)
    {   
        $resource->reports()->delete();
        $resource->delete();
        notify()->success('Deleted');
        return redirect()->route('admin.resources.index');
    }
}