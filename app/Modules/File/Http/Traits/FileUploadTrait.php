<?php

namespace App\Modules\File\Http\Traits;
use App\Modules\File\Models\TemporaryFile;
use App\Modules\File\Models\File as Files;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;



trait FileUploadTrait
{
    public static function saveTmpFile($file, $filename, $disk, $label,  $dir = null )
    {
        try {

	        $sessionId = session()->getId();
	        $path = Storage::disk($disk)->putFileAs($dir, $file, $filename );

	        // save original file
	        $data=TemporaryFile::create([
	            'disk' => $disk,
	            'filename' => $filename,
	            'session_id' => $sessionId,
	            'path' => $path,
	            'extension' => $file->guessClientExtension() ?? '',
	            'mime' => $file->getClientMimeType(),
	            'size' => $file->getSize(),
	            'label' => $label,
	            // 'page_count' => $this->countPages($path)
	        ]);
 
	        return response()->json(['status' => 'success', 'tmpfile_id' => $data->id ], 201);
	        // return response('Created', Response::HTTP_CREATED);	
        } catch (Exception $e) {
        	
        }
    }




  	public static function transferTmpFile($tmpfile_id, $disk = null )
    {
        try {

	       	$tempFile = TemporaryFile::where('filename', $tmpfile_id)->first();
	       	$disk = $disk ? $disk : config('filesystems.rfiles');

	       	$filename = strtok($tmpfile_id,  "."  ) . '.pdf';
			$exists =  Storage::disk('s3')->exists($filename);
			if($exists){
				// get full path to s3 file
				$path =  Storage::disk('s3')->url($filename);
				$fileMeta = Storage::disk('s3')->getMetaData($filename);

				$data = Files::create([
			        'user_id' => $tempFile->user_id,
			        'disk' => $disk,
			        'filename' => $fileMeta['filename'],
			        'path' => $fileMeta['path'],
			        'extension' => $fileMeta['extension'],
			        'mime' => $fileMeta['mimetype'],
			        'size' =>$fileMeta['size'],
			        'location' => 'upload',
			        'page_count' => self::countPages($path),
			    ]);

				return $data;
			}

	        // return response()->json(['status' => 'success', 'tmpfile_id' => $data->id ], 201);
        } catch (Exception $e) {
        	return $e;
        }
    }


    public function defaultUpload($fileid) {
        try {

        	$file = new File('/path/to/photo');

        	$current = Carbon::now()->format('YmdHs');
	        $fileExtension = $file->getClientOriginalExtension();

	        // regular filename
	        $nnn =str_replace( $fileExtension,  '.' .$fileExtension,  Str::slug($file->getClientOriginalName()) );
	        $fileName = strtoupper($nnn);

	        // unique filename with timestamp
	        // $nnn =str_replace( $fileExtension, '-'.$current . '.' .$fileExtension,  Str::slug($file->getClientOriginalName()) );
	        // $fileName = strtoupper($nnn);


	        $path = Storage::putFileAs('resource', $file, $filename );
	         
	        $data=File::create([
	            'user_id' => auth()->id(),
	            'disk' => config('filesystems.default'),
	            'filename' => $fileName,
	            'path' => $path,
	            'extension' => $file->guessClientExtension() ?? '',
	            'mime' => $file->getClientMimeType(),
	            'size' => $file->getSize(),
	        ]);
	        
	        // activity('file')
	        //         ->performedOn($data)
	        //         ->causedBy(auth()->user())
	        //         ->withProperties(['subject' => $data,'causer'=>auth()->user()])
	        //         ->log('created');
	        

	        return response('Created', Response::HTTP_CREATED);
        	
        } catch (Exception $e) {
        	
        }
    }



    public static function countPages($path) {
	  $pdftext = file_get_contents($path);
	  $num = preg_match_all("/\/Page\W/", $pdftext, $dummy);
	  return $num;
	}

}