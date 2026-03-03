<?php

namespace App\Modules\File\Http\Traits;
use App\Modules\Models\File as Files;
use Illuminate\Support\Carbon;
use App\Modules\File\Models\TemporaryFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;




trait FileProcessTrait
{

	// convert docx to pdf here
    public static function covertDocxToPdf($file, $filename, $label)
    {
        try {

            // do processing here...
            
            // save converted file
            $path = Storage::disk('tmp')->putFileAs(null, $file, $fileName );
            $data=TemporaryFile::create([
                'disk' => config('filesystems.tmp'),
                'filename' => $filename,
                'session_id' => $sessionId,
                'path' => $path,
                'extension' => $file->guessClientExtension() ?? '',
                'mime' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'label' => $label
            ]);

            return response()->json(['status' => 'success'], 201);
            // return response('Created', Response::HTTP_CREATED);
	        return response()->json(['status' => 'success'], 201);        	
        } catch (Exception $e) {
        	
        }
    }


    // add watermark to pdf
    public function addWatermark($file) {
        try {
	        return response('Created', Response::HTTP_CREATED);
        	
        } catch (Exception $e) {
        	
        }
    }

    // use to get page and word count
    public function getPdfDetails ($file) {
        try {

	        return response('Created', Response::HTTP_CREATED);
        	
        } catch (Exception $e) {
        	
        }
    }



}