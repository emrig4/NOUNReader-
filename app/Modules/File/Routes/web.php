<?php

use App\Modules\File\Http\Controllers\FileController;
use App\Modules\File\Models\TemporaryFile;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\MountManager;
use Illuminate\Http\File;
use App\Modules\File\Models\File as Files;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your module. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::group(['prefix' => 'files', 'middleware' => 'auth'], function () {
    Route::post('/upload', 'FileController@uploadTmp')->name('files.store.upload');
});





Route::get('test', function(){

	// $tempFile = TemporaryFile::latest()->first();

	// // $file = Storage::disk('tmp')->get($tempFile->path);

	// 	// $mountManager = new MountManager([
	// //     'tmp' => \Storage::disk('tmp')->getDriver(),
	// //     'files' => \Storage::disk('files')->getDriver(),
	// // ]);
	// // $file = $mountManager->copy('tmp://' . $tempFile->path, 'files://' . $tempFile->path);

	// // get full path to temp file
	// $tempFilePath =  Storage::disk('tmp')->path($tempFile->filename);
	// $tempFileUrl =  Storage::disk('tmp')->url($tempFile->filename);
	// // copy tmp file in main file dir
 //    $path = Storage::disk(config('filesystems.rfiles'))->putFileAs(null, $tempFileUrl, $tempFile->filename );


 //    $data=Files::create([
 //        'user_id' => 1,
 //        'disk' => config('filesystems.rfiles'),
 //        'filename' => $tempFile->filename,
 //        'path' => $path,
 //        'extension' => $tempFile->extension,
 //        'mime' => $tempFile->mime,
 //        'size' => $tempFile->size,
 //        'location' => 'upload',
 //    ]);


	// dd($data);

	$resource  = App\Modules\Resource\Models\Resource::first();
	dd($resource);
});
