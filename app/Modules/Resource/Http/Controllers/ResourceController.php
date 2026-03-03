<?php

namespace App\Modules\Resource\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Resource\Models\Resource;
use App\Modules\Resource\Models\ResourceField;
use App\Modules\Resource\Http\Requests\ResourceStoreRequest;
use App\Modules\Resource\Http\Requests\ResourceUpdateRequest;
use Illuminate\Support\Str;
use App\Modules\File\Http\Traits\FileUploadTrait;
use App\Modules\File\Http\Traits\FileProcessTrait;
use DB;
use Bouncer;
use App\Modules\Resource\Models\ResourceAuthor;
use App\Models\User;
use Digikraaft\PaystackSubscription\Payment;
use Illuminate\Support\Facades\Storage;
use App\Modules\Wallet\Http\Traits\WalletTrait;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResourceSubmissionNotification;
use App\Modules\Resource\Models\ResourceType;
use App\Modules\Payment\Models\Currency;
use App\Modules\File\Models\File as Files;
use App\Services\EmailNotificationService;
use App\Services\PdfPreviewService;
use Illuminate\Support\Facades\Log;

// Import for bulk upload - ENCODING FIX
use App\Imports\ResourcePublishImport;
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
        //
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
     * Show the combined submission form (upload + publish in one page)
     *
     * @return \Illuminate\Http\Response
     */
     public function submit()
{
    $resourceTypes = ResourceType::all();
    $resourceFields = ResourceField::all();
    $currencies = collect([
        (object)['code' => 'NGN', 'name' => 'Naira', 'symbol' => '₦'],
        (object)['code' => 'RANC', 'name' => 'readprojecttopics Credit', 'symbol' => 'RANC']
    ]);
    
    return view('resource.create.submit', compact('resourceTypes', 'resourceFields', 'currencies'));
}


    /**
     * Store a newly submitted resource (for review - not immediately published)
     *
     * @param  App\Modules\Resource\Http\Requests\ResourceStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function storeSubmission(ResourceStoreRequest $request)
    {   
        try {
            $authUser = auth()->user();
            $file = null;
            
            // Check if file is uploaded directly (new method)
            if ($request->hasFile('file')) {
                $uploadedFile = $request->file('file');
                
                // Generate unique filename
                $originalName = $uploadedFile->getClientOriginalName();
                $extension = $uploadedFile->getClientOriginalExtension();
                $filename = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '-' . time() . '.' . $extension;
                
                // Upload to S3
                $path = Storage::disk('s3')->putFileAs('documents', $uploadedFile, $filename);
                
                if ($path) {
                    // Get page count for PDF files
                    $pageCount = 0;
                    if (strtolower($extension) === 'pdf') {
                        try {
                            $tempPath = $uploadedFile->getPathname();
                            $pdfContent = file_get_contents($tempPath);
                            $pageCount = preg_match_all("/\/Page\W/", $pdfContent, $dummy);
                        } catch (\Exception $e) {
                            $pageCount = 0;
                        }
                    }
                    
                    // Create file record
                    $file = Files::create([
                        'user_id' => $authUser->id,
                        'disk' => 's3',
                        'filename' => $filename,
                        'path' => $path,
                        'extension' => $extension,
                        'mime' => $uploadedFile->getClientMimeType(),
                        'size' => $uploadedFile->getSize(),
                        'location' => 'upload',
                        'page_count' => $pageCount,
                    ]);
                }
            } 
            // Fallback to temp file method (old method)
            elseif ($request->input('tmpfile_name')) {
                $tempfile = $request->input('tmpfile_name');
                $file = FileUploadTrait::transferTmpFile($tempfile, 's3');
            }
            
            if($file){
                $params = $request->all();
                $params['page_count'] = $file->page_count;
                
                // Set submission status - NOT published until approved
                $params['is_published'] = false;
                $params['approval_status'] = 'pending';
                $params['submitted_at'] = now();

                // Create the resource (not published yet)
                $resource = Resource::withoutGlobalScopes()->create($params);
                
                // Link file to resource
                $entity = DB::table('entity_files')->insert([
                    'file_id' => $file->id,
                    'entity_type' => 'App\Modules\Resource\Models\Resource',
                    'entity_id' => $resource->id,
                    'label' => 'main_file',
                    'created_at' => $resource->created_at,
                    'updated_at' => $resource->updated_at,
                ]);

                // Save publisher user as lead author
                ResourceAuthor::create([
                    'fullname' => $authUser->first_name . ' ' . $authUser->last_name,
                    'resource_id' => $resource->id,
                    'is_lead' => 1,
                    'username' => $authUser->username
                ]);

                // Save coauthors
                if ($request->coauthors) {
                    $authors = explode(',', $request->coauthors);
                    foreach($authors as $author){
                        if(trim($author)) {
                            $resourceAuthor = new ResourceAuthor;
                            $resourceAuthor->resource_id = $resource->id;
                            $resourceAuthor->fullname = trim($author);

                            $user = User::whereRaw("CONCAT(`first_name`, ' ', `last_name`) LIKE ?", ['%'.trim($author).'%'])
                                    ->first();
                            if($user){
                                $resourceAuthor->username = $user->username;
                            }
                            $resourceAuthor->save();
                        }
                    }
                }

                // Send email notification to admin
                try {
                    Mail::to('emrig4@gmail.com')->send(new ResourceSubmissionNotification($resource, 'admin'));
                    
                    // Also send confirmation to user
                    Mail::to($authUser->email)->send(new ResourceSubmissionNotification($resource, 'user'));
                } catch (\Exception $e) {
                    \Log::error('Failed to send submission notification email: ' . $e->getMessage());
                }

                // Redirect to success page
                notify()->success('Your document has been submitted for review!');
                return redirect()->route('resources.submit.success')->with('resource', $resource);

            } else {
                notify()->error('Something went wrong with file upload. Please try again.');
                return redirect()->back()->withInput();
            }

        } catch (\Exception $e) {
            \Log::error('Resource submission failed: ' . $e->getMessage());
            notify()->error('Submission failed: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }


    /**
     * Show submission success page
     *
     * @return \Illuminate\Http\Response
     */
    public function submissionSuccess()
    {
        $resource = session('resource');
        return view('resource.create.submission_received', compact('resource'));
    }


    /**
     * Store a newly created resource in storage.
     * (Original method - still works for direct publish)
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

               // save publisher user as lead author
                ResourceAuthor::create([
                    'fullname' => $authUser->first_name . ' ' .  $authUser->last_name,
                    'resource_id' => $resource->id,
                    'is_lead' => 1,
                    'username' => $authUser->username
                ]);

                // save coauthors
                $authors = explode(',',  $request->coauthors);
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
        $resource = Resource::with(['reviews.user'])->where('slug', $slug)->first();
        $previewData = null;
        $mainFile = null;
        
        if($resource){
            $resource->increment('view_count', 1);
            $mainFile = $resource->filterFiles('main_file')->first();
            
            // 🔒 PREVIEW DISABLED - Commented out to save S3 costs
            // The viewer page now handles access control directly
            // No more 3-page preview generation on detail page
            // 
            // if ($mainFile) {
            //     try {
            //         $pdfService = new PdfPreviewService();
            //         $previewData = $pdfService->generatePreview($mainFile->path, $resource->id);
            //         
            //         if ($previewData) {
            //             Log::info("Preview generated for resource detail page: {$resource->title}");
            //         } else {
            //             Log::warning("Preview generation failed for resource: {$resource->title}");
            //         }
            //     } catch (\Exception $e) {
            //         Log::error("Preview service error for resource {$resource->id}: " . $e->getMessage());
            //     }
            // }
            
            $previewData = null;
        }
        
        // Generate proper title for SEO
        $pageTitle = $resource ? ucwords(strtolower($resource->title)) : 'Document Not Available';

        return view('resource::single', [
            'resource' => $resource, 
            'mainFile' => $mainFile ?? '', 
            'previewData' => $previewData,
            'title' => $pageTitle
        ]);
    }


     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cite($slug)
    {
        $resource = Resource::where('slug', $slug)->first();
        
        // Check if resource exists
        if (!$resource) {
            abort(404);
        }
        
        $authors = $resource->authors()->pluck('fullname')->toArray();
        
        if (empty($authors)) {
            // If no authors found, try to get from author relationship
            $author = $resource->author()->first();
            if ($author) {
                $authors = [$author->fullname];
            }
        }
        
        $ap6_authors = implode(' , ', $authors);
        $ap7_authors = implode(' & ', $authors);
        $mla8_authors = implode(' and ', $authors);
        return view('resource::citation', compact('resource', 'authors', 'ap7_authors', 'ap6_authors', 'mla8_authors' ));
    }


    /**
     * Read the specified resource file.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
 public function read($slug)
{
    $user = auth()->user();
    
    // Load resource - use withoutGlobalScopes() to bypass the is_published filter
    // This ensures resources can be accessed even if they're not published
    $resource = Resource::withoutGlobalScopes()->where('slug', $slug)->first();
    
    // ✅ FIX: Add null check for resource to prevent 500 error
    if (!$resource) {
        notify()->error('Resource not found');
        return redirect()->route('resources.index');
    }
    
    $mainFile = $resource->filterFiles('main_file')->first();
    $sessionRead = \Session::get($slug);
    $resource->increment('read_count', 1);

    if($resource->price && $resource->currency && $resource->price > 0 && auth()->user() ){  
            if(!$sessionRead){

                // charge 150 rancs per unique_reads, 
                $ranc_per_onread = setting('ranc_per_onread');
                $resource_owner_percent_onread = (double) setting('resource_owner_percent_onread')/100 ; // 10%
                $readprojecttopics_user_id = setting('readprojecttopics_user_id');

                $walletBal = WalletTrait::subscriptionWalletBalance();
                if($walletBal > $ranc_per_onread) {

                   // return redirect()->back()->with('message', 'you dont sufficient credit in you current subscription bundle to read this resourcs, your balance is ???');
               
                    // Get balance before deduction for email
                    $balanceBefore = $walletBal;

                    WalletTrait::debitSubscriptionWallet($ranc_per_onread);

                    // CREDIT RESOURCE OWNER
                    WalletTrait::creditSubscriptionWallet( ($ranc_per_onread * $resource_owner_percent_onread ), $resource->user_id, $resource->title); //90%

                    // CREDIT readprojecttopics 
                    WalletTrait::creditSubscriptionWallet($ranc_per_onread * (1 - $resource_owner_percent_onread) , $readprojecttopics_user_id, $resource->title ); //10%

                    $sessionRead = \Session::put($slug, true);
                    
                    // Send read operation email notification
                    try {
                        EmailNotificationService::sendReadOperationEmail(
                                $user,
                            $resource->title,
                            $ranc_per_onread,
                            $balanceBefore,
                            $balanceBefore - $ranc_per_onread
                        );
                    } catch (\Exception $e) {
                        Log::error('Failed to send read operation email', [
                            'user_id' => $user->id,  // ✅ FIXED: Use $user instead of auth()->id()
                            'resource_title' => $resource->title,
                            'error' => $e->getMessage()
                        ]);
                    }
                    
                    notify()->success('You have been charged ' . $ranc_per_onread . ' readprojecttopics credits');
                }else{
                    notify()->error('you dont sufficient credit in you current subscription bundle to read this resource, please buy extra credits ');
                    $sessionRead = \Session::put($slug, false);
                }
            }

            if($sessionRead){
                notify()->success('Reading ' . $resource->title);
            }

            return view('resource::viewer.index', ['resource' => $resource, 'mainFile' => $mainFile, 'sessionRead' => $sessionRead]);
        }else{
            return view('resource::viewer.index', ['resource' => $resource, 'mainFile' => $mainFile, 'sessionRead' => $sessionRead]);
        }

        

    }

 

	public function download(Request $request, $slug)
	{
	    // 🚨 CRITICAL FIX: CHECK AUTHENTICATION FIRST
	    $user = auth()->user();
	    if (!$user) {
	        // Check if AJAX request - return JSON error instead of redirect
	        if ($request->ajax() || $request->wantsJson()) {
	            return response()->json([
	                'success' => false,
	                'message' => 'Please login to download resources',
	                'redirect' => route('login')
	            ], 401);
	        }
	        notify()->error('Please login to download resources');
	        return redirect()->route('login');
	    }

	    // Load resource AFTER authentication check
	    $resource = Resource::where('slug', $slug)->first();
	    if (!$resource) {
	        if ($request->ajax() || $request->wantsJson()) {
	            return response()->json([
	                'success' => false,
	                'message' => 'Resource not found'
	            ], 404);
	        }
	        abort(404);
	    }

	    $mainFile = $resource->filterFiles('main_file')->first();
	    $sessionRead = \Session::get($slug);
	    $responseQuery = $request->query('response');

	    // BUSINESS RULE: ALL downloads charge wallet
	    $downloadPrice = $resource->price > 0 ? $resource->price : 1;
	    $ranc_per_download = ranc_equivalent($downloadPrice, $resource->currency);
	    $resource_owner_percent_ondownload = (double)setting('resource_owner_percent_ondownload')/100;
	    $readprojecttopics_user_id = setting('readprojecttopics_user_id');

	    if($responseQuery) {
	        $response = json_decode($responseQuery);
	        $transaction = Payment::hasValidTransaction($response->reference);
	        if ($transaction) {
	            WalletTrait::creditSubscriptionWallet(($ranc_per_download * $resource_owner_percent_ondownload), $resource->user_id, $resource->title);
	            WalletTrait::creditSubscriptionWallet($ranc_per_download * (1 - $resource_owner_percent_ondownload), $readprojecttopics_user_id, $resource->title);
	            $sessionRead = \Session::put($slug, true);
	        } else {
	            // Check if AJAX request - return JSON error instead of redirect
	            if ($request->ajax() || $request->wantsJson()) {
	                return response()->json([
	                    'success' => false,
	                    'message' => 'Invalid transaction. Please try again.'
	                ], 400);
	            }
	            notify()->error('Invalid transaction.');
	            return redirect()->back();
	        }
	    } else {
	        $walletBal = WalletTrait::subscriptionWalletBalance();
	        if($walletBal >= $ranc_per_download) {
	            WalletTrait::debitSubscriptionWallet($ranc_per_download);
	            WalletTrait::creditSubscriptionWallet(($ranc_per_download * $resource_owner_percent_ondownload), $resource->user_id, $resource->title);
	            WalletTrait::creditSubscriptionWallet($ranc_per_download * (1 - $resource_owner_percent_ondownload), $readprojecttopics_user_id, $resource->title);
	            $sessionRead = \Session::put($slug, true);
	        } else {
	            // Check if AJAX request - return JSON error instead of redirect
	            if ($request->ajax() || $request->wantsJson()) {
	                return response()->json([
	                    'success' => false,
	                    'message' => 'Insufficient credits. Please buy more credits to download this resource.',
	                    'required' => $ranc_per_download,
	                    'available' => $walletBal,
	                    'buy_credits_url' => route('pricings')
	                ], 402); // 402 Payment Required
	            }
	            notify()->error('Insufficient credits. Please buy more credits.');
	            return redirect()->back();
	        }
	    }

	    $resource->increment('download_count', 1);

	    // Stream file with proper headers for download
	    return $this->downloadFileWithProgress($mainFile, $resource);
	}

	/**
	 * Download file with mobile-friendly progress tracking
	 * ✅ Streams large files efficiently
	 * ✅ Shows download progress on mobile
	 */
	private function downloadFileWithProgress($mainFile, $resource)
	{
	    $file_name = basename($mainFile->path);
	    
	    // Get file from S3
	    $fileContent = Storage::disk('s3')->get($mainFile->path);
	    $fileSize = strlen($fileContent);
	    
	    // ✅ Log download for analytics
	    Log::info('File download started', [
	        'resource_id' => $resource->id,
	        'file' => $file_name,
	        'size' => $fileSize,
	        'user' => auth()->user()->email,
	        'device' => $this->detectDevice()
	    ]);
	    
	    return response($fileContent, 200, [
	        'Content-Type' => $mainFile->mime,
	        'Content-Disposition' => 'attachment; filename="' . $file_name . '"',
	        'Content-Length' => $fileSize,
	        'Cache-Control' => 'public, max-age=0',
	        'X-File-Download' => 'true', // ✅ Custom header for mobile detection
	        'X-File-Size' => $fileSize,
	        'X-File-Name' => $file_name,
	    ]);
	}

	/**
	 * Detect device type for logging
	 */
	private function detectDevice()
	{
	    $userAgent = request()->header('User-Agent');
	    if (preg_match('/Mobile|Android|iPhone/', $userAgent)) {
	        return 'mobile';
	    }
	    return 'desktop';
	}

	// ...existing code...

    /**
     * Download file helper method
     */
     private function downloadFile($mainFile)
    {
        // Extract only the filename (no folder path)
        $file_name = basename($mainFile->path);
        
        $headers = [
            'Content-Type' => $mainFile->mime,
            'Content-Disposition' => 'attachment; filename="'. $file_name .'"',
        ];

        return \Response::make(Storage::disk('s3')->get($mainFile->path), 200, $headers);
    }
    public function freeDownload(Request $request, $slug)
    {
        // BUSINESS RULE UPDATE: All downloads now charge - redirect to main download function
        // No more free downloads allowed
        return $this->download($request, $slug);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
      /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $slug
     * @return \Illuminate\Http\Response
     */
      public function edit($slug)
    {
        // Get the resource
        // ✅ FIXED: Use withoutGlobalScopes to find unpublished resources
        $resource = Resource::withoutGlobalScopes()->where('slug', $slug)->first();
        
        if (!$resource) {
            notify()->error('Resource not found');
            return redirect()->route('account.myworks');
        }
        
        // Authorization check: Allow if user is the owner OR user has admin role
        $user = auth()->user();
        $isAdmin = $user->hasRole('admin') || $user->hasRole('sudo') || ($user->is_admin ?? 0) == 1;
        $isOwner = $resource->user_id == $user->id;
        
        if (!$isOwner && !$isAdmin) {
            notify()->error('You do not have permission to edit this resource');
            return redirect()->route('account.myworks');
        }
        
        $existingSubfields = explode(',', $resource->sub_fields);
        $existingCoauthors = ResourceAuthor::where('resource_id', $resource->id)
                                            ->where('is_lead', false)->get();
        $mainFile = $resource->filterFiles('main_file')->first();
        
        // If no main file found via filterFiles, check the resource's files relationship
        if (!$mainFile) {
            $files = $resource->files()->get();
            if ($files->isNotEmpty()) {
                $mainFile = $files->first();
            }
        }
        
        return view('resource.edit', [
            'resource' => $resource, 
            'mainFile' => $mainFile, 
            'existingSubfields' => $existingSubfields,
            'existingCoauthors' => $existingCoauthors
        ]);
    }

      /**
     * Update the specified resource in storage.
     *
     * @param  App\Modules\Resource\HttpRequests\ResourceUpdateRequest  $request
     * @param  int  $slug
     * @return \Illuminate\Http\Response
     */
       public function update(ResourceUpdateRequest $request,  $slug)
    {

        try {
            $authUser = auth()->user();
            
            // ✅ FIXED: Use withoutGlobalScopes to find unpublished resources
            $resource = Resource::withoutGlobalScopes()->where('slug', $slug)->first();
            
            if (!$resource) {
                notify()->error('Resource not found');
                return redirect()->route('account.myworks');
            }
            
            // Authorization check: Allow if user is the owner OR user has admin role
            $isAdmin = $authUser->hasRole('admin') || $authUser->hasRole('sudo') || ($authUser->is_admin ?? 0) == 1;
            $isOwner = $resource->user_id == $authUser->id;
            
            if (!$isOwner && !$isAdmin) {
                notify()->error('You do not have permission to update this resource');
                return redirect()->route('account.myworks');
            }

            $params = $request->all();
            if($params['currency'] == ''){
                $params['price'] = null;
            }
            $resource->update( $params );

            // save author
            $resource->author()->update(['fullname' => $request->author,  'username' => \Str::slug($request->author) ]);
             

            // save coauthors
            $authors = explode(',',  $request->coauthors);
            $existingCoauthors = ResourceAuthor::where('resource_id', $resource->id)->where('is_lead', false)->delete();

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
            
            notify()->success('Resource updated successfully!');

        } catch (\Exception $e) {
            Log::error('Resource update failed: ' . $e->getMessage());
            notify()->error('Failed to update resource: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }


       // check if api or web
        if($request->header('Accept') == 'application/json'){
            return;
        }else{
            return redirect()->route('account.myworks');
        }
   }


        /**
     * Display a listing of the resource.
     * Two-stage search: Exact title match first, then keyword partial matching
     *
     * @return \Illuminate\Http\Response
    */
    public function search(Request $request){
        $field = $request->query('field');
        $search = trim($request->query('search'));
        $subfield = $request->query('subfield');
        $type = $request->query('type');

        $query = Resource::query();
        $searchApplied = false;

        // Two-stage search: Exact match first, then keyword partial matching
        if ($search) {
            // Parse search into keywords
            $searchKeywords = $this->parseSearchKeywords($search);
            $validKeywords = array_filter($searchKeywords, function($keyword) {
                return strlen($keyword) >= 2;
            });
            
            if (!empty($validKeywords)) {
                $searchApplied = true;
                
                // Stage 1: Check for exact title match first
                $exactMatchQuery = Resource::where('title', 'LIKE', '%' . $search . '%')
                    ->where('is_published', 1);
                
                // Apply filters to exact match query
                if ($type) {
                    $exactMatchQuery->where('type', $type);
                }
                if ($subfield) {
                    $exactMatchQuery->where('sub_fields', 'like', '%' . $subfield . '%');
                }
                if ($field) {
                    $exactMatchQuery->where('field', $field);
                }
                
                $exactMatches = $exactMatchQuery->get();
                
                if ($exactMatches->count() > 0) {
                    // Use exact matches - filter by IDs
                    $query->whereIn('id', $exactMatches->pluck('id')->toArray());
                } else {
                    // Stage 2: No exact matches - use keyword partial matching on title
                    $query->where(function($mainQ) use ($validKeywords) {
                        foreach ($validKeywords as $keyword) {
                            $likePattern = '%' . $keyword . '%';
                            $mainQ->orWhere('title', 'like', $likePattern);
                        }
                    });
                    
                    // Apply filters to partial match query
                    if ($type) {
                        $query->where('type', $type);
                    }
                    if ($subfield) {
                        $query->where('sub_fields', 'like', '%' . $subfield . '%');
                    }
                    if ($field) {
                        $query->where('field', $field);
                    }
                }
            }
        }

        // Apply type filter (for cases where search was empty or as fallback)
        if (!$searchApplied && $type) {
            $query->where('type', $type);
        }
        
        // Apply subfield filter
        if (!$searchApplied && $subfield) {
            $query->where('sub_fields', 'like', '%' . $subfield . '%');
        }
        
        // Apply field filter
        if (!$searchApplied && $field) {
            $query->where('field', $field);
        }

        // If no search and no filters, limit to recent resources
        if (!$searchApplied && !$type && !$subfield && !$field) {
            $query->where('is_published', 1)->orderBy('created_at', 'desc')->limit(100);
        }

        $resources = $query->get();

        $fieldModel = ResourceField::where('slug', $field)->first();
            
        return view('resource.search', ['resources' => $resources, 'field' => $fieldModel]);
    }

    /**
     * Parse search query into individual keywords
     * Handles various separators: space, comma, semicolon
     *
     * @param string $search
     * @return array
     */
    protected function parseSearchKeywords($search){
        // Replace common separators with spaces
        $search = str_replace([',', ';', '|', '/'], ' ', $search);
        
        // Split into keywords and remove empty entries
        $keywords = array_filter(array_map('trim', explode(' ', $search)));
        
        // Remove very short keywords (less than 2 characters)
        $keywords = array_filter($keywords, function($keyword) {
            return strlen($keyword) >= 2;
        });
        
        return array_values($keywords);
    }
   public function destroy($slug)
{       
    try {
        // ✅ FIX 1: Add null check first
        $resource = Resource::withoutGlobalScopes()->where('slug', $slug)->first();
        
        if (!$resource) {
            notify()->error('Resource not found or already deleted');
            return redirect()->route('account.myworks');
        }

        // ✅ FIX 2: Verify ownership before deletion
        if($resource->user_id != auth()->user()->id) {
            notify()->error('You are not authorized to delete this resource');
            return redirect()->route('account.myworks');
        }

        // ✅ FIX 3: Delete associated S3 files first (before database record)
        foreach ($resource->files as $file) {
            // This triggers the File model's boot method to delete S3 files
            $file->delete();
        }

        // ✅ FIX 4: Delete related data in proper order
        $resource->reports()->delete();
        $resource->authors()->delete();
        
        // ✅ FIX 5: Finally delete the resource
        $resource->delete();

        notify()->success('Resource and associated files deleted successfully');
        return redirect()->route('account.myworks');

    } catch (Exception $e) {
        // ✅ FIX 6: Add error handling
        Log::error('Resource deletion failed: ' . $e->getMessage(), [
            'slug' => $slug,
            'user_id' => auth()->id(),
            'trace' => $e->getTraceAsString()
        ]);
        
        notify()->error('Error deleting resource: ' . $e->getMessage());
        return back();
    }
}

    /**
     * BULK UPLOAD with ENCODING FIX
     * 
     * NEW METHOD ADDED - This method includes UTF-8 encoding handling to prevent
     * smart quotes corruption during bulk upload (CHINAΓò¼├┤Γö£├ºΓòPts├ÅS issue)
     */
    public function bulkUpload(Request $request)
    {
        try {
            // Set UTF-8 encoding headers to prevent character corruption
            // This fixes the smart quotes issue where "CHINA'S" becomes "CHINAΓò¼├┤Γö£├ºΓòPts├ÅS"
            header('Content-Type: text/html; charset=UTF-8');
            mb_internal_encoding('UTF-8');
            
            // Validate file upload
            if (!$request->hasFile('sheet')) {
                notify()->error('Please select an Excel file to upload.');
                return redirect()->back();
            }
            
            $file = $request->file('sheet');
            
            // Validate file type
            $allowedExtensions = ['xlsx', 'xls'];
            $fileExtension = $file->getClientOriginalExtension();
            
            if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
                notify()->error('Please upload a valid Excel file (.xlsx or .xls).');
                return redirect()->back();
            }
            
            // Log encoding fix start
            Log::info('🔧 Starting bulk upload with encoding fix', [
                'user' => auth()->user()->name ?? 'Unknown',
                'file' => $file->getClientOriginalName(),
                'size' => $file->getSize()
            ]);
            
            // Import with the enhanced ResourcePublishImport that includes encoding cleaning
            $import = new ResourcePublishImport;
            Excel::import($import, $file);
            
            // Get import statistics
            $stats = $import->getImportStats();
            $errors = $import->getValidationErrors();
            
            // Success message with statistics
            $message = "Files published successfully! ";
            $message .= "Processed: {$stats['processed']}, ";
            $message .= "Duplicates: {$stats['duplicates_prevented']}, ";
            $message .= "Updated: {$stats['updated']}, ";
            $message .= "Errors: {$stats['errors']}, ";
            $message .= "Encoding fixes: {$stats['encoding_issues_fixed']}";
            
            notify()->success($message);
            
            // Log completion
            Log::info('✅ Bulk upload completed with encoding fix', [
                'user' => auth()->user()->name ?? 'Unknown',
                'stats' => $stats,
                'encoding_issues_fixed' => $stats['encoding_issues_fixed']
            ]);
            
            // Display errors if any
            if (!empty($errors)) {
                Log::warning('⚠️ Bulk upload validation errors:', $errors);
                notify()->warning('Some rows had validation errors. Check logs for details.');
            }
            
            return redirect()->back();
            
        } catch (\Maatwebsite\Excel\Exceptions\NoTypeDetectedException $e) {
            Log::error('Excel import failed - invalid file type', ['error' => $e->getMessage()]);
            notify()->error('Invalid Excel file format. Please ensure the file is a valid .xlsx or .xls file.');
            return redirect()->back();
        } catch (\Maatwebsite\Excel\Exceptions\NoSheetsFoundException $e) {
            Log::error('Excel import failed - no sheets found', ['error' => $e->getMessage()]);
            notify()->error('No sheets found in the Excel file. Please ensure the file contains data.');
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error('Bulk upload failed with encoding fix', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user' => auth()->user()->name ?? 'Unknown'
            ]);
            notify()->error('Bulk upload failed: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}