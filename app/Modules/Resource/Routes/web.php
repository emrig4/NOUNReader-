<?php
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Modules\File\Models\TemporaryFile;
use App\Modules\Resource\Http\Controllers\ResourceController;
use App\Modules\Resource\Http\Controllers\ResourceSubFieldController;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\MountManager;
use Illuminate\Http\File;
use App\Modules\File\Models\File as Files;
use App\Modules\Resource\Models\Resource;
use App\Modules\Resource\Models\ResourceSubField;




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

Route::group(['prefix' => 'resources', 'middleware' => ['web'] ], function () {

    // ============================================
    // NEW: SUBMISSION SYSTEM ROUTES
    // Combined form + Admin Review Workflow
    // ============================================
    
    // Combined submission form (upload + publish in one page)
    Route::get('/submit', 'ResourceController@submit')
        ->name('resources.submit')
        ->middleware('auth', 'session.expiry');

    // Store submission (for review - not immediately published)
    Route::post('/submit', 'ResourceController@storeSubmission')
        ->name('resources.submit.store')
        ->middleware('auth', 'session.expiry');

    // Submission success page
    Route::get('/submit/success', 'ResourceController@submissionSuccess')
        ->name('resources.submit.success')
        ->middleware('auth', 'session.expiry');

    // ============================================
    // EXISTING ROUTES (unchanged)
    // ============================================

	// upload resource file
    Route::get('/upload','ResourceController@createUpload' )
        ->name('resources.create.upload')
        ->middleware('auth', 'session.expiry');

    // create resource
    Route::get('/publish', 'ResourceController@createPublish')
        ->name('resources.create.publish')
        ->middleware('fileuploaded','auth', 'session.expiry');

    // store resource
    Route::post('/publish',  'ResourceController@store')
        ->name('resources.store.publish')
        ->middleware('auth', 'session.expiry');

    // resource index
    Route::get('/', 'ResourceFieldController@index')
        ->name('resources.index');


    // fields index
    Route::get('/fields', 'ResourceFieldController@index')
        ->name('resources.fields');

    // single field
    Route::get('/fields/{slug}', 'ResourceFieldController@show')
        ->name('resources.fields.show');



    // single resource type
    Route::get('/types/{slug}', 'ResourceTypeController@show')
        ->name('resources.types.show');



    // subfields index (topics)
    Route::get('/topics', 'ResourceSubFieldController@index')
        ->name('resources.topics');

    // single subfield (topic)
    Route::get('/topics/{slug}', 'ResourceSubFieldController@show')
        ->name('resources.topics.show');

    // resource search
    Route::post('/search', 'ResourceController@search')
        ->name('resources.search');
    
    // search results
    Route::get('/search', 'ResourceController@search')->name('resources.searches');

    // single resource
    Route::get('/{slug}', 'ResourceController@show')
        ->name('resources.show');
        // ->middleware('auth');


    // single resource citation
    Route::get('/{slug}/cite', 'ResourceController@cite')
        ->name('resources.cite')
        ->middleware('session.expiry');

    // edit single resource page
    Route::get('/{slug}/edit', 'ResourceController@edit')
        ->name('resources.edit')
        ->middleware('auth', 'session.expiry');


    // edit single resource pagec
    Route::post('/{slug}/edit', 'ResourceController@update')
        ->name('resources.update')
        ->middleware('auth', 'session.expiry');

    Route::get('/{slug}/delete',  'ResourceController@destroy')
            ->name('resources.delete')
            ->middleware('auth', 'session.expiry');



    // reader - FIXED: Added session.expiry middleware to prevent errors
    Route::get('/{slug}/read', 'ResourceController@read')
        ->name('resources.read')
        ->middleware('session.expiry');  // CHANGED: Added auth and session.expiry


    // download - FIXED: Added session.expiry middleware
      Route::get('/{slug}/download', 'ResourceController@download')
        ->name('resources.download')
         ->middleware('auth', 'session.expiry');  // CHANGED: Added session.expiry
        
  
    // download - FIXED: Added session.expiry middleware  
    Route::get('/{slug}/freedownload', 'ResourceController@freeDownload')
        ->name('resources.freedownload')
        ->middleware('auth', 'session.expiry');  // CHANGED: Added auth and session.expiry


    // review
    Route::resource('/{slug}/reviews', 'ResourceReviewController')
        ->middleware('auth', 'session.expiry');

    // report
    Route::resource('/{resource}/reports', 'ResourceReportController')
        ->middleware('auth', 'session.expiry');

});


Route::group(['prefix' => 'subfields'], function () {
    // get subfields for ajax calls
    Route::get('/', function(){
        if (request()->has('field')) {
            $query = request()->input('field');
             return ResourceSubField::where('parent_field', $query )->get();
        }
        return  ResourceSubField::all();
    })->name('subfields');
});



Route::group(['prefix' => 'authors'], function () {
    // get subfields for ajax calls
    Route::get('/', function(){
        return  \App\Models\User::all();
    })->name('authors');
});


Route::get('/test', function(){
     $resourc = Resource::all();
            return $resourc;
});


Route::get('/docs', function(){
    $resource = Resource::first();
    return view('resource.viewer.dochtmlviewer', ['resource' => $resource]);
});


Route::get('/project-topics-materials', 'ResourceTypeController@projectTopicType')->name('resources.project.type');




Route::namespace('Admin')->prefix('admin')->middleware('role:sudo|admin|publisher')->group(function() {

    Route::group([ 'prefix' => 'resources',  'middleware' => ['auth', 'web', 'permission'] ], function() {

        // ============================================
        // NEW: ADMIN APPROVAL SYSTEM ROUTES
        // ============================================
        
        // Pending resources list (awaiting approval)
        Route::get('/pending', 'ResourceController@pending')
            ->name('admin.resources.pending');

        // Approve resource
        Route::post('/{id}/approve', 'ResourceController@approve')
            ->name('admin.resources.approve');

        // Reject resource
        Route::post('/{id}/reject', 'ResourceController@reject')
            ->name('admin.resources.reject');

        // ============================================
        // EXISTING ADMIN ROUTES (unchanged)
        // ============================================

        // upload resource file
        Route::get('/upload','ResourceController@createUpload' )
            ->name('admin.resources.create.upload')
            ->middleware('auth');

        // create resource
        Route::get('/publish', 'ResourceController@createPublish')
            ->name('admin.resources.create.publish')
            ->middleware('fileuploaded','auth');

        // store resource
        Route::post('/publish',  'ResourceController@store')
            ->name('resources.store.publish')
            ->middleware('auth');


         // store resource
        Route::post('/publish',  'ResourceController@resourcePublishImport')
            ->name('admin.resources.import')
            ->middleware('auth');


        // resource index
        Route::get('/', 'ResourceController@index')
        ->name('admin.resources.index');


        Route::get('/{slug}',  'ResourceController@show')
            ->name('admin.resources.show');


        Route::get('/{slug}/edit',  'ResourceController@edit')
            ->name('admin.resources.edit');

         Route::delete('/{resource}/delete',  'ResourceController@destroy')
            ->name('admin.resources.delete');


        Route::get('/{resource}/unpublish',  'ResourceController@unpublish')
            ->name('admin.resources.unpublish');


        Route::patch('/{slug}/update',  'ResourceController@update')
            ->name('admin.resources.update');



        // list s3 index
        Route::post('/list-s3-files', 'ResourceController@listS3Files')
        ->name('admin.resources.lists3');

    });
});