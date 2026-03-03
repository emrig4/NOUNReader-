<?php

namespace App\Modules\File\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Modules\File\Http\Traits\FileUploadTrait;
use App\Modules\File\Http\Traits\FileProcessTrait;
use Illuminate\Support\Carbon;


class FileController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }



    /**
     * Transfer the specified resource from tmp storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function transferTmp($id)
    {
        //
    }


    /**
     * Upload a new resource and redirect to publish page.
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadTmp(Request $request)
    {   
        
        $file = $request->file('file');

        $current = Carbon::now()->format('YmdHs');
        $fileExtension = $file->getClientOriginalExtension();

        // regular filename
        // $nnn =str_replace( $fileExtension,  '.' .$fileExtension,  Str::slug($file->getClientOriginalName()) );
        // $filename = strtoupper($nnn);

        // unique filename with timestamp
        $nnn =str_replace( $fileExtension, '-'.$current ,  Str::slug($file->getClientOriginalName()) );
        $filename = strtoupper($nnn)  . '.' . strtolower($fileExtension);

        // $filename = $this->getRequestFileName($file);
        $label = 'original';
        $disk = config('filesystems.cloud_tmp');

        // save original
        $response = FileUploadTrait::saveTmpFile ($file, $filename, $disk, $label );


        $tmpfile_id = $response->getData();
        // dd($tmpfile_id);


        $statusCode = $response->getStatusCode();
        // check upload status then redirect for web request and json for api
        if($statusCode == 201){
            if($request->header('Accept') == 'application/json'){
            return $response;
            }else{
                return redirect()->route('resources.create.publish');
            }
        }
    }


    public function getRequestFileName ($file) {
        try {
            $current = Carbon::now()->format('YmdHis');
            $fileExtension = $file->getClientOriginalExtension();
           
            // regular filename
            // $fileName = strtoupper( Str::slug($file->getClientOriginalName()) . '.' .$fileExtension );
            
            // unique filename with timestamp
            $fileName =strtoupper( Str::slug($file->getClientOriginalName()) . '-'.$current . '.'. $fileExtension );
           
            return $fileName;
            
        } catch (Exception $e) {
            
        }
    }

    public function getRequestFileMime ($file) {
        try {
          return $file->getClientMimeType();
            
        } catch (Exception $e) {
            
        }
    }


}
