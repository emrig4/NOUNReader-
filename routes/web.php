<?php



use Illuminate\Http\Request;
use Digikraaft\PaystackSubscription\PaystackSubscription;
use Spatie\SlackAlerts\Facades\SlackAlert;
use Digikraaft\Paystack\Plan;
use App\Modules\Subscription\Http\Controllers\SubscriptionController;
use App\Modules\Subscription\Models\PaystackSubscription as Subscription;
use App\Modules\Subscription\Events\PaystackWebhookEvent;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\Admin\AcademicDashboardController;
use App\Modules\Resource\Models\Resource;
use App\Mail\ResourceApprovalNotification;
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\PlagiarismCheckerController;

    // Email verification notice page
    Route::get('/verification/notice', [App\Http\Controllers\AuthController::class, 'showVerificationNotice'])
        ->name('verification.notice');

    // Resend verification link
    Route::post('/verification/resend', [App\Http\Controllers\AuthController::class, 'resendVerificationLink'])
        ->name('verification.resend');
        
// ========================================================================
// CRITICAL SEO REDIRECTS - Must be at top to catch requests first
// Redirect /public/resources/... to /resources/... (301 for SEO canonical)
// ========================================================================

// Redirect /public/resources/{slug} to /resources/{slug}
Route::get('/public/resources/{slug}', function($slug) {
    return redirect('/resources/' . $slug, 301);
});

// Redirect /public/resources/{slug}/read to /resources/{slug}/read
Route::get('/public/resources/{slug}/read', function($slug) {
    return redirect('/resources/' . $slug . '/read', 301);
});

// Redirect /public/resources to /resources
Route::get('/public/resources', function() {
    return redirect('/resources', 301);
});

// Redirect /public/login to /login
Route::get('/public/login', function() {
    return redirect('/login', 301);
});

// Redirect /users/user_login to /login
Route::get('/users/user_login', function() {
    return redirect('/login', 301);
});


// Fix /public/login duplicate → /login
Route::get('/public/login', function() {
    return redirect('/login', 301);
});

// Fix /users/user_login duplicate → /login
Route::get('/users/user_login', function() {
    return redirect('/login', 301);
});

// Fix /user duplicate → /account
Route::get('/user', function() {
    return redirect('/account', 301);
});

// Fix /public/resources/... duplicate → /resources/...
Route::get('/public/resources/{slug}/read', function($slug) {
    return redirect('/resources/' . $slug . '/read', 301);
});


// Redirect old books preview URLs to new resource URLs (like projects redirect)
Route::get('/books/preview/{slug}', function ($slug) {
    return redirect('/resources/' . $slug, 301);  // ✅ Correct
});
Route::get('/books/preview', function () {
    return redirect('/public/resources', 301);
});

// ========================================================================
// BLOG ROUTES - Fixed for binshops/laravel-blog package
// ========================================================================

// Check if blog routes should be loaded (when package default routes are disabled)
if (!config('binshopsblog.include_default_routes', true)) {
    Route::prefix(config('binshopsblog.blog_prefix', 'blog'))->name('blog.')->group(function() {
        Route::get('/', 'App\Modules\Blog\Http\Controllers\BlogController@index')->name('index');
        Route::get('/{slug}', 'App\Modules\Blog\Http\Controllers\BlogController@show')->name('show');
        Route::get('/category/{slug}', 'App\Modules\Blog\Http\Controllers\BlogController@index')->name('category');
    });
}

// ========================================================================

Route::get('/backup-db', function() {
    $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql.gz';
    $path = storage_path('backups/' . $filename);
    
    // Create backup directory if not exists
    if (!file_exists(dirname($path))) {
        mkdir(dirname($path), 0755, true);
    }
    
    // Create compressed backup
    $command = sprintf(
        'mysqldump -u %s -p%s %s | gzip > %s',
        'projtpyy_pam_user',
        'Ny+A[mJ!52?z',
        'projtpyy_pam_db',
        $path
    );
    
    exec($command);
    
    return response()->download($path)->deleteFileAfterSend(true);
});

// Redirect old project URLs to new resource URLs
Route::get('/projects/{slug}', function ($slug) {
    return redirect('/resources/' . $slug, 301);
});

Route::get('/projects', function () {
    return redirect('/resources', 301);
});
Route::get('/project-guide', function () {
    return view('pages.project-guide');
})->name('project-guide');
// Bulk update form - use FULL controller namespace to avoid slug conflict
Route::get('/bulk-update', 'App\Modules\Resource\Http\Controllers\ResourceController@bulkUpdateForm')
    ->name('admin.resources.bulk-update.form')
    ->middleware('auth');

// Bulk update process
Route::post('/bulk-update', 'App\Modules\Resource\Http\Controllers\ResourceController@bulkUpdateProcess')
    ->name('admin.resources.bulk-update.process')
    ->middleware('auth');

// Find your existing admin routes group and ADD this inside:
Route::prefix('subfields')->name('admin.subfields.')->group(function() {
    
    Route::get('/', [
        \App\Modules\Resource\Http\Controllers\Admin\SubfieldAdminController::class, 
        'index'
    ])->name('index');
    
    Route::get('/{id}/edit', [
        \App\Modules\Resource\Http\Controllers\Admin\SubfieldAdminController::class, 
        'edit'
    ])->name('edit');
    
    Route::put('/{id}', [
        \App\Modules\Resource\Http\Controllers\Admin\SubfieldAdminController::class, 
        'update'
    ])->name('update');
    
    Route::delete('/{id}', [
        \App\Modules\Resource\Http\Controllers\Admin\SubfieldAdminController::class, 
        'destroy'
    ])->name('destroy');
    
    Route::post('/bulk-delete', [
        \App\Modules\Resource\Http\Controllers\Admin\SubfieldAdminController::class, 
        'bulkDelete'
    ])->name('bulk-delete');
    
    Route::get('/{id}/usage', [
        \App\Modules\Resource\Http\Controllers\Admin\SubfieldAdminController::class, 
        'usage'
    ])->name('usage');
    
    Route::post('/clear-all', [
        \App\Modules\Resource\Http\Controllers\Admin\SubfieldAdminController::class, 
        'clearAll'
    ])->name('clear-all');
});
// ========================================================================
// SEARCH TOPIC ROUTE - STANDALONE SEARCH PAGE
// ========================================================================

Route::post('/logout', function() {
    try {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Log logout attempt
            \Log::info('User logged out successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'timestamp' => now()
            ]);
            
            // Perform logout
            auth()->logout();
            session()->invalidate();
            session()->regenerateToken();
            
            return redirect('/')->with('success', 'You have been logged out successfully!');
        }
        
        return redirect('/')->with('info', 'You are not logged in.');
        
    } catch (\Exception $e) {
        // Log errors and force logout
        \Log::error('Logout error', [
            'error' => $e->getMessage(),
            'user_id' => auth()->id(),
            'timestamp' => now()
        ]);
        
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        
        return redirect('/')->with('warning', 'Logout completed with minor issues.');
    }
})->middleware(['web'])->name('logout');
// Search topic page route
Route::get('/search-topic', function() {
    // Get resource types for dropdown
    $resourceTypes = \App\Modules\Resource\Models\ResourceType::all();
    
    return view('pages.search-topic', compact('resourceTypes'));
})->name('search-topic');

// Handle search topic form submission
Route::post('/search-topic', function() {
    $search = request()->input('search');
    $type = request()->input('type');
    
    // Redirect to the existing search functionality
    return redirect()->route('resources.searches', [
        'search' => $search,
        'type' => $type
    ]);
})->name('search-topic.submit');

// ========================================================================
// TEST AND DEBUG ROUTES (REMOVE IN PRODUCTION)
// ========================================================================

// Authentication Routes
// Simple Logout Routes (No Auth::routes() needed)
Route::post('/logout', function() {
    if (auth()->check()) {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
    }
    return redirect('/')->with('success', 'Logged out successfully!');
})->name('logout');

Route::get('/logout', function() {
    // Redirect to home for GET requests
    return redirect('/');
});

// Logout Routes (add these)
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::get('/logout', function() {
    return redirect('/');
});

/*
|--------------------------------------------------------------------------
| Research Topics Suggestion Routes
|--------------------------------------------------------------------------
|
| Here is where you can register routes for the Research Topics Suggestion Tool.
| These routes are loaded by the ResearchTopicServiceProvider.
|
*/

// Main interface route
Route::get('/topics-suggestion', [ResearchTopicController::class, 'index'])->name('topics-suggestion.index');

// API Routes
Route::prefix('api/topics-suggestion')->name('topics-suggestion.')->group(function () {
    
    // Get suggestions
    Route::post('/suggestions', [ResearchTopicController::class, 'getSuggestions'])->name('suggestions');
    
    // Search topics
    Route::post('/search', [ResearchTopicController::class, 'search'])->name('search');
    
    // Get popular topics
    Route::get('/popular', [ResearchTopicController::class, 'getPopular'])->name('popular');
    
    // Get topics by department
    Route::post('/by-department', [ResearchTopicController::class, 'getByDepartment'])->name('by-department');
    
    // Get departments and types
    Route::get('/departments', [ResearchTopicController::class, 'getDepartments'])->name('departments');
    
    // Save favorite topic
    Route::post('/save-favorite', [ResearchTopicController::class, 'saveFavorite'])->name('save-favorite');
    
    // Get statistics
    Route::get('/statistics', [ResearchTopicController::class, 'getStatistics'])->name('statistics');
    
    // Export topics
    Route::post('/export', [ResearchTopicController::class, 'exportTopics'])->name('export');
});

// Alternative shorter routes
Route::post('/topics-suggestions', [ResearchTopicController::class, 'getSuggestions'])->name('topics-suggestions.get');
Route::post('/topics-search', [ResearchTopicController::class, 'search'])->name('topics-search.get');
Route::get('/topics-popular', [ResearchTopicController::class, 'getPopular'])->name('topics-popular.get');

// Web routes (for AJAX calls from forms)
Route::middleware(['web'])->group(function () {
    Route::post('/topics-suggestion/get-suggestions', [ResearchTopicController::class, 'getSuggestions'])->name('topics-suggestion.get-suggestions');
    /*Route::post('/topics-suggestion/search', [ResearchTopicController::class, 'search'])->name('topics-suggestion.search');*/
});

/*
|--------------------------------------------------------------------------
| PLAGARISM CHECKER
|--------------------------------------------------------------------------
| 
*/
 
// Plagiarism Checker Routes
Route::middleware(['web'])->group(function () {
    
    // Main checker interface (public access)
    Route::get('/plagiarism-checker', [PlagiarismCheckerController::class, 'index'])
        ->name('plagiarism-checker.index');
    
    // API endpoint for checking plagiarism (public access with rate limiting)
    Route::post('/plagiarism-checker/check', [PlagiarismCheckerController::class, 'check'])
        ->name('plagiarism-checker.check');
    
    // Check history (requires authentication)
    Route::middleware(['auth'])->group(function () {
        Route::get('/plagiarism-checker/history', [PlagiarismCheckerController::class, 'history'])
            ->name('plagiarism-checker.history');
        
        Route::get('/plagiarism-checker/report/{checkId}', [PlagiarismCheckerController::class, 'downloadReport'])
            ->name('plagiarism-checker.report');
    });
});

 
/*
|--------------------------------------------------------------------------
| RESOURCE APPROVAL EMAIL TEST ROUTE
|--------------------------------------------------------------------------
| Test route to preview the resource approval email HTML output
| Shows exactly what users will receive when their resource is approved
*/
Route::get('/test/resource-approved-email', function () {
    // Create a fake resource for testing
    $resource = new Resource([
        'id' => 123,
        'title' => 'Advanced Machine Learning Algorithms: A Comprehensive Study',
        'user_id' => 456,
        'category' => 'Computer Science',
        'description' => 'This comprehensive study explores advanced machine learning algorithms including deep learning, neural networks, and ensemble methods. The research covers theoretical foundations, practical implementations, and real-world applications.',
        'file_path' => 'uploads/resources/ml-algorithms-study.pdf',
        'approval_status' => 'approved',
        'admin_notes' => 'Excellent resource with comprehensive coverage of ML algorithms.',
        'is_published' => 1,
        'created_at' => now()->subDays(2),
        'updated_at' => now(),
    ]);

    // Add user relationship
    $resource->setRelation('user', (object)[
        'name' => 'Dr. Sarah Johnson',
        'email' => 'sarah.johnson@email.com'
    ]);

    // Create the email
    $email = new ResourceApprovalNotification($resource, 'approved');

    // Render the email HTML
    $html = $email->render();

    // Add some styling to make the preview look better
    $html = '<html><head><title>Approval Email Preview</title></head><body style="background-color: #f5f5f5; padding: 20px;">'
          . '<div style="background: white; padding: 20px; border-radius: 10px; max-width: 600px; margin: 0 auto;">'
          . '<h2 style="color: #2563eb; margin-bottom: 20px;">📧 Resource Approval Email Preview</h2>'
          . '<div style="border: 2px solid #2563eb; border-radius: 8px; overflow: hidden;">'
          . $html
          . '</div>'
          . '<div style="margin-top: 20px; padding: 15px; background: #f0f9ff; border-radius: 8px;">'
          . '<h3 style="color: #2563eb; margin: 0 0 10px 0;">Test Details:</h3>'
          . '<p style="margin: 5px 0;"><strong>Resource ID:</strong> 123</p>'
          . '<p style="margin: 5px 0;"><strong>Title:</strong> ' . $resource->title . '</p>'
          . '<p style="margin: 5px 0;"><strong>Status:</strong> Approved</p>'
          . '<p style="margin: 5px 0;"><strong>Published:</strong> Yes</p>'
          . '<p style="margin: 5px 0;"><strong>Date:</strong> ' . now()->format('M d, Y h:i A') . '</p>'
          . '</div>'
          . '</div>'
          . '</body></html>';

    return $html;
});

/*
|--------------------------------------------------------------------------
| RESOURCE REJECTION EMAIL TEST ROUTE
|--------------------------------------------------------------------------
| Test route to preview the resource rejection email HTML output
| Shows exactly what users will receive when their resource is rejected
*/
Route::get('/test/resource-rejected-email', function () {
    // Create a fake resource for testing
    $resource = new Resource([
        'id' => 124,
        'title' => 'Basic Programming Tutorial for Beginners',
        'user_id' => 789,
        'category' => 'Programming',
        'description' => 'A beginner-friendly guide to programming fundamentals covering variables, loops, and functions.',
        'file_path' => 'uploads/resources/programming-tutorial.pdf',
        'approval_status' => 'rejected',
        'admin_notes' => 'While the content is good, this tutorial already exists in our collection. Please consider submitting unique content that provides new insights or approaches. We encourage you to resubmit with more original material.',
        'is_published' => 0,
        'created_at' => now()->subDays(1),
        'updated_at' => now(),
    ]);

    // Add user relationship
    $resource->setRelation('user', (object)[
        'name' => 'Alex Rodriguez',
        'email' => 'alex.rodriguez@email.com'
    ]);

    // Create the email
    $email = new ResourceApprovalNotification($resource, 'rejected');

    // Render the email HTML
    $html = $email->render();

    // Add some styling to make the preview look better
    $html = '<html><head><title>Rejection Email Preview</title></head><body style="background-color: #f5f5f5; padding: 20px;">'
          . '<div style="background: white; padding: 20px; border-radius: 10px; max-width: 600px; margin: 0 auto;">'
          . '<h2 style="color: #dc2626; margin-bottom: 20px;">📧 Resource Rejection Email Preview</h2>'
          . '<div style="border: 2px solid #dc2626; border-radius: 8px; overflow: hidden;">'
          . $html
          . '</div>'
          . '<div style="margin-top: 20px; padding: 15px; background: #fef2f2; border-radius: 8px;">'
          . '<h3 style="color: #dc2626; margin: 0 0 10px 0;">Test Details:</h3>'
          . '<p style="margin: 5px 0;"><strong>Resource ID:</strong> 124</p>'
          . '<p style="margin: 5px 0;"><strong>Title:</strong> ' . $resource->title . '</p>'
          . '<p style="margin: 5px 0;"><strong>Status:</strong> Rejected</p>'
          . '<p style="margin: 5px 0;"><strong>Published:</strong> No</p>'
          . '<p style="margin: 5px 0;"><strong>Date:</strong> ' . now()->format('M d, Y h:i A') . '</p>'
          . '<p style="margin: 5px 0;"><strong>Admin Notes:</strong> ' . substr($resource->admin_notes, 0, 100) . '...</p>'
          . '</div>'
          . '</div>'
          . '</body></html>';

    return $html;
});

/*
|--------------------------------------------------------------------------
| RESOURCE EMAIL TESTS INDEX ROUTE
|--------------------------------------------------------------------------
| Provides a navigation page for all email test routes
*/
Route::get('/test/resource-emails', function () {
    return '<html><head><title>Resource Email Tests</title>'
          . '<style>'
          . 'body { font-family: Arial, sans-serif; background-color: #f5f5f5; margin: 0; padding: 20px; }'
          . '.container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }'
          . '.header { text-align: center; margin-bottom: 30px; }'
          . '.test-card { border: 1px solid #e5e5e5; border-radius: 8px; padding: 20px; margin-bottom: 20px; background: #fafafa; }'
          . '.test-card h3 { margin-top: 0; color: #333; }'
          . '.btn { display: inline-block; padding: 12px 24px; background: #2563eb; color: white; text-decoration: none; border-radius: 6px; font-weight: bold; margin-right: 10px; }'
          . '.btn:hover { background: #1d4ed8; }'
          . '.btn-success { background: #059669; }'
          . '.btn-danger { background: #dc2626; }'
          . '.description { color: #666; margin: 10px 0; }'
          . '.status { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }'
          . '.status-working { background: #d1fae5; color: #065f46; }'
          . '</style>'
          . '</head><body>'
          . '<div class="container">'
          . '<div class="header">'
          . '<h1>📧 Resource Email Tests</h1>'
          . '<p>Test and preview approval & rejection email templates</p>'
          . '<div class="status status-working">✅ All Routes Working</div>'
          . '</div>'
          . '<div class="test-card">'
          . '<h3>✅ Resource Approval Email</h3>'
          . '<div class="description">Test the email users receive when their resource is approved</div>'
          . '<a href="/test/resource-approved-email" class="btn btn-success" target="_blank">Preview Approval Email</a>'
          . '</div>'
          . '<div class="test-card">'
          . '<h3>❌ Resource Rejection Email</h3>'
          . '<div class="description">Test the email users receive when their resource is rejected</div>'
          . '<a href="/test/resource-rejected-email" class="btn btn-danger" target="_blank">Preview Rejection Email</a>'
          . '</div>'
          . '<div style="margin-top: 30px; padding: 15px; background: #f0f9ff; border-radius: 8px;">'
          . '<h3 style="margin-top: 0; color: #2563eb;">Test Information:</h3>'
          . '<ul style="color: #666;">'
          . '<li>✅ These routes create fake resource data for testing</li>'
          . '<li>✅ No actual emails are sent - only HTML is displayed</li>'
          . '<li>✅ Safe to use in development/testing environments</li>'
          . '<li>✅ Verify design and content before production use</li>'
          . '</ul>'
          . '</div>'
          . '<div style="margin-top: 20px; padding: 15px; background: #fef3c7; border-radius: 8px;">'
          . '<h3 style="margin-top: 0; color: #92400e;">Next Steps:</h3>'
          . '<ol style="color: #92400e;">'
          . '<li>Test the approval email above (should show green celebration theme)</li>'
          . '<li>Test the rejection email above (should show orange/red encouraging theme)</li>'
          . '<li>Confirm both emails display correctly</li>'
          . '<li>Once both work, we will install the admin messaging system</li>'
          . '</ol>'
          . '</div>'
          . '</div>'
          . '</body></html>';
});

/*
|--------------------------------------------------------------------------
| END OF FIXED TEST ROUTES
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| RESOURCE EMAIL TESTS INDEX ROUTE
|--------------------------------------------------------------------------
| Provides a navigation page for all email test routes
*/
Route::get('/test/resource-emails', function () {
    return '<html><head><title>Resource Email Tests</title>'
          . '<style>'
          . 'body { font-family: Arial, sans-serif; background-color: #f5f5f5; margin: 0; padding: 20px; }'
          . '.container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }'
          . '.header { text-align: center; margin-bottom: 30px; }'
          . '.test-card { border: 1px solid #e5e5e5; border-radius: 8px; padding: 20px; margin-bottom: 20px; background: #fafafa; }'
          . '.test-card h3 { margin-top: 0; color: #333; }'
          . '.btn { display: inline-block; padding: 12px 24px; background: #2563eb; color: white; text-decoration: none; border-radius: 6px; font-weight: bold; }'
          . '.btn:hover { background: #1d4ed8; }'
          . '.description { color: #666; margin: 10px 0; }'
          . '</style>'
          . '</head><body>'
          . '<div class="container">'
          . '<div class="header">'
          . '<h1>📧 Resource Email Tests</h1>'
          . '<p>Preview and test approval & rejection email templates</p>'
          . '</div>'
          . '<div class="test-card">'
          . '<h3>✅ Resource Approval Email</h3>'
          . '<div class="description">Test the email users receive when their resource is approved</div>'
          . '<a href="/test/resource-approved-email" class="btn" target="_blank">Preview Approval Email</a>'
          . '</div>'
          . '<div class="test-card">'
          . '<h3>❌ Resource Rejection Email</h3>'
          . '<div class="description">Test the email users receive when their resource is rejected</div>'
          . '<a href="/test/resource-rejected-email" class="btn" target="_blank">Preview Rejection Email</a>'
          . '</div>'
          . '<div style="margin-top: 30px; padding: 15px; background: #f0f9ff; border-radius: 8px;">'
          . '<h3 style="margin-top: 0; color: #2563eb;">Test Information:</h3>'
          . '<ul style="color: #666;">'
          . '<li>These routes create fake resource data for testing</li>'
          . '<li>No actual emails are sent - only HTML is displayed</li>'
          . '<li>Safe to use in development/testing environments</li>'
          . '<li>Verify design and content before production use</li>'
          . '</ul>'
          . '</div>'
          . '</div>'
          . '</body></html>';
});

/*
|--------------------------------------------------------------------------
| END OF APPROVAL & REJECTION EMAIL TEST ROUTES
|--------------------------------------------------------------------------
/*
|--------------------------------------------------------------------------
| RESOURCE APPROVAL EMAIL TEST ROUTE
|--------------------------------------------------------------------------
| Test route to preview the resource approval email HTML output
| Shows exactly what users will receive when their resource is approved
*/
Route::get('/test/resource-approved-email', function () {
    // Create a fake resource for testing
    $resource = new Resource([
        'id' => 123,
        'title' => 'Advanced Machine Learning Algorithms: A Comprehensive Study',
        'user_id' => 456,
        'category' => 'Computer Science',
        'description' => 'This comprehensive study explores advanced machine learning algorithms including deep learning, neural networks, and ensemble methods. The research covers theoretical foundations, practical implementations, and real-world applications.',
        'file_path' => 'uploads/resources/ml-algorithms-study.pdf',
        'approval_status' => 'approved',
        'admin_notes' => 'Excellent resource with comprehensive coverage of ML algorithms.',
        'is_published' => 1,
        'created_at' => now()->subDays(2),
        'updated_at' => now(),
    ]);

    // Add user relationship
    $resource->setRelation('user', (object)[
        'name' => 'Dr. Sarah Johnson',
        'email' => 'sarah.johnson@email.com'
    ]);

    // Create the email
    $email = new ResourceApprovalNotification($resource, 'approved');

    // Render the email HTML
    $html = $email->render();

    // Add some styling to make the preview look better
    $html = '<html><head><title>Approval Email Preview</title></head><body style="background-color: #f5f5f5; padding: 20px;">'
          . '<div style="background: white; padding: 20px; border-radius: 10px; max-width: 600px; margin: 0 auto;">'
          . '<h2 style="color: #2563eb; margin-bottom: 20px;">📧 Resource Approval Email Preview</h2>'
          . '<div style="border: 2px solid #2563eb; border-radius: 8px; overflow: hidden;">'
          . $html
          . '</div>'
          . '<div style="margin-top: 20px; padding: 15px; background: #f0f9ff; border-radius: 8px;">'
          . '<h3 style="color: #2563eb; margin: 0 0 10px 0;">Test Details:</h3>'
          . '<p style="margin: 5px 0;"><strong>Resource ID:</strong> 123</p>'
          . '<p style="margin: 5px 0;"><strong>Title:</strong> ' . $resource->title . '</p>'
          . '<p style="margin: 5px 0;"><strong>Status:</strong> Approved</p>'
          . '<p style="margin: 5px 0;"><strong>Published:</strong> Yes</p>'
          . '<p style="margin: 5px 0;"><strong>Date:</strong> ' . now()->format('M d, Y h:i A') . '</p>'
          . '</div>'
          . '</div>'
          . '</body></html>';

    return $html;
});

/*
|--------------------------------------------------------------------------
| RESOURCE REJECTION EMAIL TEST ROUTE
|--------------------------------------------------------------------------
| Test route to preview the resource rejection email HTML output
| Shows exactly what users will receive when their resource is rejected
*/
Route::get('/test/resource-rejected-email', function () {
    // Create a fake resource for testing
    $resource = new Resource([
        'id' => 124,
        'title' => 'Basic Programming Tutorial for Beginners',
        'user_id' => 789,
        'category' => 'Programming',
        'description' => 'A beginner-friendly guide to programming fundamentals covering variables, loops, and functions.',
        'file_path' => 'uploads/resources/programming-tutorial.pdf',
        'approval_status' => 'rejected',
        'admin_notes' => 'While the content is good, this tutorial already exists in our collection. Please consider submitting unique content that provides new insights or approaches. We encourage you to resubmit with more original material.',
        'is_published' => 0,
        'created_at' => now()->subDays(1),
        'updated_at' => now(),
    ]);

    // Add user relationship
    $resource->setRelation('user', (object)[
        'name' => 'Alex Rodriguez',
        'email' => 'alex.rodriguez@email.com'
    ]);

    // Create the email
    $email = new ResourceApprovalNotification($resource, 'rejected');

    // Render the email HTML
    $html = $email->render();

    // Add some styling to make the preview look better
    $html = '<html><head><title>Rejection Email Preview</title></head><body style="background-color: #f5f5f5; padding: 20px;">'
          . '<div style="background: white; padding: 20px; border-radius: 10px; max-width: 600px; margin: 0 auto;">'
          . '<h2 style="color: #dc2626; margin-bottom: 20px;">📧 Resource Rejection Email Preview</h2>'
          . '<div style="border: 2px solid #dc2626; border-radius: 8px; overflow: hidden;">'
          . $html
          . '</div>'
          . '<div style="margin-top: 20px; padding: 15px; background: #fef2f2; border-radius: 8px;">'
          . '<h3 style="color: #dc2626; margin: 0 0 10px 0;">Test Details:</h3>'
          . '<p style="margin: 5px 0;"><strong>Resource ID:</strong> 124</p>'
          . '<p style="margin: 5px 0;"><strong>Title:</strong> ' . $resource->title . '</p>'
          . '<p style="margin: 5px 0;"><strong>Status:</strong> Rejected</p>'
          . '<p style="margin: 5px 0;"><strong>Published:</strong> No</p>'
          . '<p style="margin: 5px 0;"><strong>Date:</strong> ' . now()->format('M d, Y h:i A') . '</p>'
          . '<p style="margin: 5px 0;"><strong>Admin Notes:</strong> ' . substr($resource->admin_notes, 0, 100) . '...</p>'
          . '</div>'
          . '</div>'
          . '</body></html>';

    return $html;
});

/*
|--------------------------------------------------------------------------
| RESOURCE EMAIL TESTS INDEX ROUTE
|--------------------------------------------------------------------------
| Provides a navigation page for all email test routes
*/
Route::get('/test/resource-emails', function () {
    return '<html><head><title>Resource Email Tests</title>'
          . '<style>'
          . 'body { font-family: Arial, sans-serif; background-color: #f5f5f5; margin: 0; padding: 20px; }'
          . '.container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }'
          . '.header { text-align: center; margin-bottom: 30px; }'
          . '.test-card { border: 1px solid #e5e5e5; border-radius: 8px; padding: 20px; margin-bottom: 20px; background: #fafafa; }'
          . '.test-card h3 { margin-top: 0; color: #333; }'
          . '.btn { display: inline-block; padding: 12px 24px; background: #2563eb; color: white; text-decoration: none; border-radius: 6px; font-weight: bold; }'
          . '.btn:hover { background: #1d4ed8; }'
          . '.description { color: #666; margin: 10px 0; }'
          . '</style>'
          . '</head><body>'
          . '<div class="container">'
          . '<div class="header">'
          . '<h1>📧 Resource Email Tests</h1>'
          . '<p>Preview and test approval & rejection email templates</p>'
          . '</div>'
          . '<div class="test-card">'
          . '<h3>✅ Resource Approval Email</h3>'
          . '<div class="description">Test the email users receive when their resource is approved</div>'
          . '<a href="/test/resource-approved-email" class="btn" target="_blank">Preview Approval Email</a>'
          . '</div>'
          . '<div class="test-card">'
          . '<h3>❌ Resource Rejection Email</h3>'
          . '<div class="description">Test the email users receive when their resource is rejected</div>'
          . '<a href="/test/resource-rejected-email" class="btn" target="_blank">Preview Rejection Email</a>'
          . '</div>'
          . '<div style="margin-top: 30px; padding: 15px; background: #f0f9ff; border-radius: 8px;">'
          . '<h3 style="margin-top: 0; color: #2563eb;">Test Information:</h3>'
          . '<ul style="color: #666;">'
          . '<li>These routes create fake resource data for testing</li>'
          . '<li>No actual emails are sent - only HTML is displayed</li>'
          . '<li>Safe to use in development/testing environments</li>'
          . '<li>Verify design and content before production use</li>'
          . '</ul>'
          . '</div>'
          . '</div>'
          . '</body></html>';
});


// WALLET EMAIL TEST ROUTES - Add to routes/web.php

// Test Wallet Credit Email
Route::get('/test-wallet-credit', function () {
    try {
        // Create test user
        $testUser = new \App\Models\User();
        $testUser->first_name = 'John';
        $testUser->email = 'test@example.com';
        
        // Test credit email
        $creditEmail = new \App\Mail\WalletCreditMail(
            $testUser,
            50.00,           // Amount
            'earning',       // Type
            100.00,          // Balance before
            150.00           // Balance after
        );
        
        return $creditEmail->render();
        
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

// Test Wallet Deduction Email
Route::get('/test-wallet-deduction', function () {
    try {
        // Create test user
        $testUser = new \App\Models\User();
        $testUser->first_name = 'Jane';
        $testUser->email = 'test@example.com';
        
        // Test deduction email
        $deductionEmail = new \App\Mail\WalletDeductionMail(
            $testUser,
            25.00,           // Amount deducted
            150.00,          // Balance before
            125.00           // Balance after
        );
        
        return $deductionEmail->render();
        
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

// Test Wallet Trait Credit Method
Route::get('/test-wallet-credit-trait', function () {
    try {
        // Get first user or create test user
        $user = \App\Models\User::first();
        if (!$user) {
            $user = \App\Models\User::create([
                'first_name' => 'Test',
                'last_name' => 'User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
                'status' => 'active'
            ]);
        }
        
        // Test wallet credit via trait
        $result = \App\Modules\Wallet\Http\Traits\WalletTrait::creditSubscriptionWallet(25.00, 'earning', $user->id);
        
        return "Wallet credit test completed! Check logs for email status.";
        
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

// Test Wallet Trait Deduction Method
Route::get('/test-wallet-deduction-trait', function () {
    try {
        // Get first user
        $user = \App\Models\User::first();
        if (!$user) {
            return "No user found. Please create a user first.";
        }
        
        // Test wallet deduction via trait
        \App\Modules\Wallet\Http\Traits\WalletTrait::debitSubscriptionWallet(10.00, $user->id);
        
        return "Wallet deduction test completed! Check logs for email status.";
        
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/test-welcome-email', function () {
    // Test user
    $testUser = new \App\Models\User();
    $testUser->first_name = 'John';
    $testUser->email = 'test@example.com';
    
    try {
        $welcomeEmail = new \App\Mail\WelcomeVerifiedUserWithCreditMail(
            $testUser,
            100.00,        // Auto credit amount
            100.00,        // Current balance
            now()          // Verification date
        );
        
        // Return the email as HTML preview
        return $welcomeEmail->render();
        
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});
//SIMPLE PENDING APPROVAL ROUTES
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/simple-pending', [App\Http\Controllers\Admin\SimpleAdminController::class, 'pending'])->name('admin.simple-pending');
    Route::post('/simple-approve/{id}', [App\Http\Controllers\Admin\SimpleAdminController::class, 'approve'])->name('admin.simple-approve');
    Route::post('/simple-reject/{id}', [App\Http\Controllers\Admin\SimpleAdminController::class, 'reject'])->name('admin.simple-reject');
});

// Admin User Cleanup Routes
Route::get('/cleanup-users', [App\Http\Controllers\AdminController::class, 'index'])->name('cleanup-users');
Route::delete('/cleanup-users/{id}', [App\Http\Controllers\AdminController::class, 'destroy'])->name('cleanup-users.destroy');
// ========================================================================
// Theme-Optimized Loader 
// ========================================================================


// Apply authentication middleware to all dashboard routes
Route::middleware(['auth'])->group(function () {
    
    /**
     * Session Management Routes
     * Fast, lightweight endpoints for loader system
     */
    Route::prefix('api')->group(function () {
        
        // Session validation - used by loader
        Route::get('/session-check', [AcademicDashboardController::class, 'checkSession'])
            ->name('api.session.check');
            
        // Session validation with timeout handling
        Route::get('/validate-session', [AcademicDashboardController::class, 'validateSession'])
            ->name('api.session.validate');
            
        // Health check for loader system
        Route::get('/health-check', [AcademicDashboardController::class, 'healthCheck'])
            ->name('api.health.check');
            
        // Dashboard data loading
        Route::get('/dashboard/stats', [AcademicDashboardController::class, 'getDashboardStats'])
            ->name('api.dashboard.stats');
            
        // User profile (lightweight)
        Route::get('/user/profile', [AcademicDashboardController::class, 'getUserProfile'])
            ->name('api.user.profile');
    });
    
    /**
     * Admin Dashboard Routes (if using admin prefix)
     * Uncomment if your admin routes are under /admin
     */
    /*
    Route::prefix('admin/api')->middleware(['admin'])->group(function () {
        Route::get('/session-check', [AcademicDashboardController::class, 'checkSession']);
        Route::get('/validate-session', [AcademicDashboardController::class, 'validateSession']);
        Route::get('/health-check', [AcademicDashboardController::class, 'healthCheck']);
        Route::get('/dashboard/stats', [AcademicDashboardController::class, 'getDashboardStats']);
        Route::get('/user/profile', [AcademicDashboardController::class, 'getUserProfile']);
    });
    */
    
});

/**
 * Public Routes (no authentication required)
 * For public dashboard or landing pages
 */
Route::prefix('api')->group(function () {
    Route::get('/health-check', [AcademicDashboardController::class, 'healthCheck']);
});

/**
 * Web Routes for Dashboard Pages
 * Include loader system in your blade views
 */
Route::middleware(['auth'])->group(function () {
    
    // Main dashboard route
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); // or your dashboard view
    })->name('dashboard');
    
    // User dashboard route
    Route::get('/user/dashboard', function () {
        return view('user.dashboard'); // or your user dashboard view
    })->name('user.dashboard');
    
    // Admin dashboard route (if using admin)
    Route::prefix('admin')->middleware(['admin'])->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
    });
    
});



// ========================================================================
// AUTHENTICATION ROUTES - 2022 STYLE (No Verification, Manual Login)
// ========================================================================

Route::group(['middleware' => 'guest'], function() {
    // Registration routes - 2022 STYLE (No verification, manual login)
    Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegistrationForm'])
        ->name('register');

    Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);

    // Login route - 2022 STYLE (No verification check)
    Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
});

// Password reset routes - 2022 STYLE (Simple)
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('/forgot-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('/reset-password', [App\Http\Controllers\Auth\NewPasswordController::class, 'store'])
        ->name('password.update');
});

// Logout route - 2022 STYLE (Uses AuthController)
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
    
// ========================================================================
// CREDIT PAYMENT ROUTES (PRESERVED)
// ========================================================================
require __DIR__.'/seo.php';  // Include our SEO routes

// ... [rest of credit system routes preserved as-is] ...
// ========================================================================
// CREDIT PAYMENT ROUTES
// ========================================================================
require __DIR__.'/seo.php';  // Include our SEO routes

Route::group([ 'prefix' => 'credits', 'middleware' => ['auth', 'web'] ], function() {
    // Credit purchase routes are handled by pricings routes below
});

Route::group([ 'prefix' => 'wallet', 'middleware' => ['auth', 'web'] ], function() {
    Route::get('/', 'SubscriptionController@showWallet')->name('wallet.index');
    Route::get('/balance', 'SubscriptionController@getWalletBalance')->name('wallet.balance');
});

// ========================================================================
// PUBLIC PRICING ROUTES (No login required)
// ========================================================================

// Public pricing routes - accessible to all visitors (no auth middleware)
Route::group(['prefix' => 'pricings', 'middleware' => ['web']], function() {
    Route::get('/', 'App\Modules\Subscription\Http\Controllers\PricingController@index')
        ->name('pricings.index');
    Route::get('/{slug}', 'App\Modules\Subscription\Http\Controllers\PricingController@show')
        ->name('pricings.show');
});

// Protected payment routes (login required)
Route::group(['prefix' => 'pricings', 'middleware' => ['auth', 'web']], function() {
    Route::get('/{slug}/pay', 'App\Modules\Subscription\Http\Controllers\PricingController@pay')
        ->name('pricings.pay');
});

// ========================================================================
// PAYMENT WEBHOOKS AND VERIFICATION
// ========================================================================

// Public pricing routes (no login required)
Route::group([ 'prefix' => 'pricings', 'middleware' => ['web'] ], function() {
    Route::get('/', 'PricingController@index')->name('pricings.index');
    Route::get('/{slug}', 'PricingController@show')->name('pricings.show');
});

// Protected payment routes (login required)
Route::group([ 'prefix' => 'pricings', 'middleware' => ['auth', 'web'] ], function() {
    Route::get('/{slug}/pay', 'PricingController@pay')->name('pricings.pay');
});

// ========================================================================
// PAYMENT WEBHOOKS AND VERIFICATION
// ========================================================================

Route::post('paystack/webhook', function(){
    $payload = request()->all();
    event(new PaystackWebhookEvent($payload));
});

Route::post('paystack/verify', 'SubscriptionController@verifyPaystack');

// ========================================================================
// LEGACY SUBSCRIPTION ROUTES (DEPRECATED - KEPT FOR BACKWARDS COMPATIBILITY)
// ========================================================================

Route::prefix('subscriptions')->group(function() {    
    Route::get('/user-invoices', function(){
    	$user = auth()->user();
        // Return empty invoices for credit system
        return response()->json([
            'status' => 'success',
            'message' => 'Credit system does not use invoices. Check your wallet for transaction history.',
            'data' => []
        ]);
    });

    Route::get('user/{invoice}', function (Request $request, $invoice) {
        // Return 404 for credit system - no invoices
        abort(404, 'Invoices are not used in the credit system. Check your wallet for transaction history.');
    });

    Route::get('/refresh', 'SubscriptionController@refreshSubscriptions')->name('subscriptions.refresh');
    Route::get('/cancel', 'SubscriptionController@cancelSubscription')->name('subscriptions.cancel');
    Route::get('/enable', 'SubscriptionController@restartSubscription')->name('subscriptions.enable');
});

// ========================================================================
// PAYSTACK SUBSCRIPTION MANAGEMENT (DEPRECATED)
// ========================================================================

Route::post('paystack/subscription-disable', function(Request $request){
    \Log::warning('Deprecated subscription disable route accessed', [
        'user_id' => auth()->id(),
        'message' => 'Credit purchases are one-time transactions and cannot be disabled'
    ]);
    
    return response()->json([
        'status' => 'info',
        'message' => 'Credit purchases are one-time transactions and cannot be disabled'
    ]);
});

Route::post('paystack/subscription-enable', function(Request $request){
    \Log::warning('Deprecated subscription enable route accessed', [
        'user_id' => auth()->id(),
        'message' => 'Credit purchases are one-time transactions and cannot be disabled'
    ]);
    
    return response()->json([
        'status' => 'info',
        'message' => 'Credit purchases are one-time transactions and cannot be disabled'
    ]);
});

// ========================================================================
// ADMIN ROUTES (UPDATED FOR CREDIT SYSTEM)
// ========================================================================

Route::namespace('Admin')->prefix('admin')->middleware('role:sudo|admin|publisher')->group(function() {
    Route::group(['middleware' => ['auth', 'web', 'permission'] ], function() {
        // Credit package management (replaces subscription plans)
        Route::group([ 'prefix' => 'credits', 'middleware' => ['auth', 'web', 'permission'] ], function() {
            Route::get('', 'PricingController@index')->name('admin.credits.index');
            Route::get('/{slug}', 'PricingController@show')->name('admin.credits.single');
            Route::patch('/{pricing}', 'PricingController@update')->name('admin.credits.update');
            Route::post('', 'PricingController@store')->name('admin.credits.store');
        });
        
        // Legacy subscription routes (deprecated)
        Route::resource('plans', 'PaystackPlanController', ['names' => 'admin.plans']);
        Route::resource('subscriptions', 'SubscriptionController', ['names' => 'admin.subscriptions']);
        
        // Legacy pricing routes (deprecated)
        Route::group([ 'prefix' => 'pricings', 'middleware' => ['auth', 'web', 'permission'] ], function() {
            Route::get('', 'PricingController@index')->name('admin.pricings.index');
            Route::get('/{slug}', 'PricingController@show')->name('admin.pricings.single');
            Route::patch('/{pricing}', 'PricingController@update')->name('admin.pricings.update');
            Route::post('', 'PricingController@store')->name('admin.pricings.store');
        });
    });
});

// ========================================================================
// API ROUTES FOR CREDIT SYSTEM
// ========================================================================

Route::group([ 'prefix' => 'api/credits', 'middleware' => ['auth'] ], function() {
    Route::get('/balance', 'SubscriptionController@getWalletBalance');
    Route::get('/history', 'SubscriptionController@getCreditPurchaseHistory');
});

// ========================================================================
// REDIRECT ROUTES FOR LEGACY SUBSCRIPTIONS
// ========================================================================

// Fix for /account/subscription
Route::get('/account/subscription', function(){
    // This route exists in your Account module - just return the view
    return view('account.subscription');
});

// Fix for /subscriptions
Route::get('/subscriptions', function(){
    // Redirect to the correct existing route
    return redirect('/account/subscription')->with('info', 'The platform now uses a credit system. Check your wallet for credit balance and transaction history.');
});

// ========================================================================
// UTILITY ROUTES
// ========================================================================

Route::get('/credits/packages', function(){
    // Redirect to pricing page
    return redirect('/pricings');
});

Route::get('/buy-credits', function(){
    // Redirect to pricing page
    return redirect('/pricings');
});

// ========================================================================
// TEST AND DEBUG ROUTES (REMOVE IN PRODUCTION)
// ========================================================================

Route::get('/test-credit-system', function(){
    if (!app()->environment('local')) {
        abort(404);
    }
    
    $user = auth()->user();
    $subscriptionBalance = \App\Modules\Wallet\Http\Traits\WalletTrait::subscriptionWalletBalance($user->id);
    $creditBalance = \App\Modules\Wallet\Http\Traits\WalletTrait::creditWalletBalance($user->id);
    
    return response()->json([
        'user' => [
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->first_name . ' ' . $user->last_name
        ],
        'balances' => [
            'subscription_wallet' => $subscriptionBalance,
            'credit_wallet' => $creditBalance,
            'total' => $subscriptionBalance + $creditBalance
        ],
        'system_status' => 'Credit system active'
    ]);
})->middleware(['auth', 'web']);

// ========================================================================
// DOCUMENTATION ROUTES
// ========================================================================

Route::get('/credits/how-it-works', function(){
    return view('credits.how-it-works');
});

Route::get('/credits/faq', function(){
    return view('credits.faq');
});

// Admin Credit System Routes
Route::get('/admin/credit', [App\Http\Controllers\AdminCreditController::class, 'index'])->name('admin.credit.index');
Route::post('/admin/credit/process', [App\Http\Controllers\AdminCreditController::class, 'processCredit'])->name('admin.credit.process');
Route::post('/admin/credit/quick', [App\Http\Controllers\AdminCreditController::class, 'quickCredit'])->name('admin.credit.quick');

// Public pricing routes (no login required)
Route::group(['prefix' => 'pricings', 'middleware' => ['web']], function() {
    Route::get('/', 'App\Modules\Subscription\Http\Controllers\PricingController@index')
        ->name('pricings.index');
    Route::get('/{slug}', 'App\Modules\Subscription\Http\Controllers\PricingController@show')
        ->name('pricings.show');
});

// Protected payment routes (login required)
Route::group(['prefix' => 'pricings', 'middleware' => ['auth', 'web']], function() {
    Route::get('/{slug}/pay', 'App\Modules\Subscription\Http\Controllers\PricingController@pay')
        ->name('pricings.pay');
});

// Admin Auto Credit System Routes
Route::prefix('admin/auto-credit')->group(function() {
    Route::get('/', [App\Http\Controllers\Admin\AutoCreditController::class, 'index'])->name('admin.auto-credit.index');
    Route::post('/update', [App\Http\Controllers\Admin\AutoCreditController::class, 'updateSettings'])->name('admin.auto-credit.update');
    Route::get('/statistics', [App\Http\Controllers\Admin\AutoCreditController::class, 'statistics'])->name('admin.auto-credit.statistics');
    Route::post('/test/{userId}', [App\Http\Controllers\Admin\AutoCreditController::class, 'testAutoCredit'])->name('admin.auto-credit.test');
});

// ========================================================================
// FIXED SITEMAP ROUTES (XML DECLARATION ERROR RESOLVED)
// ========================================================================

Route::get('/sitemap-project-topics.xml', function() {
    header('Content-Type: application/xml; charset=utf-8');
    
    $resources = DB::table('resources')
        ->where('is_published', true)
        ->get();
    
    // CRITICAL FIX: Clear any previous output before XML declaration
    ob_clean();
    
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    
    foreach ($resources as $resource) {
        echo '    <url>' . "\n";
        echo '        <loc>' . htmlspecialchars(url('/resources/' . $resource->slug)) . '</loc>' . "\n";
        echo '        <lastmod>' . date('Y-m-d', strtotime($resource->updated_at ?? $resource->created_at)) . '</lastmod>' . "\n";
        echo '        <changefreq>weekly</changefreq>' . "\n";
        echo '        <priority>1.0</priority>' . "\n";
        echo '    </url>' . "\n";
    }
    
    echo '</urlset>';
    exit();
});

// Additional sitemap routes for complete SEO coverage
Route::get('/sitemap-project-materials.xml', function() {
    header('Content-Type: application/xml; charset=utf-8');
    
    $resources = DB::table('resources')
        ->where('is_published', true)
        ->get();
    
    // CRITICAL FIX: Clear any previous output before XML declaration
    ob_clean();
    
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    
    foreach ($resources as $resource) {
        echo '    <url>' . "\n";
        echo '        <loc>' . htmlspecialchars(url('/resources/' . $resource->slug)) . '</loc>' . "\n";
        echo '        <lastmod>' . date('Y-m-d', strtotime($resource->updated_at ?? $resource->created_at)) . '</lastmod>' . "\n";
        echo '        <changefreq>weekly</changefreq>' . "\n";
        echo '        <priority>1.0</priority>' . "\n";
        echo '    </url>' . "\n";
    }
    
    echo '</urlset>';
    exit();
});

Route::get('/sitemap-final-year-projects.xml', function() {
    header('Content-Type: application/xml; charset=utf-8');
    
    $resources = DB::table('resources')
        ->where('is_published', true)
        ->get();
    
    // CRITICAL FIX: Clear any previous output before XML declaration
    ob_clean();
    
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    
    foreach ($resources as $resource) {
        echo '    <url>' . "\n";
        echo '        <loc>' . htmlspecialchars(url('/resources/' . $resource->slug)) . '</loc>' . "\n";
        echo '        <lastmod>' . date('Y-m-d', strtotime($resource->updated_at ?? $resource->created_at)) . '</lastmod>' . "\n";
        echo '        <changefreq>weekly</changefreq>' . "\n";
        echo '        <priority>1.0</priority>' . "\n";
        echo '    </url>' . "\n";
    }
    
    echo '</urlset>';
    exit();
});

Route::get('/sitemap-bsc-projects.xml', function() {
    header('Content-Type: application/xml; charset=utf-8');
    
    $resources = DB::table('resources')
        ->where('is_published', true)
        ->get();
    
    // CRITICAL FIX: Clear any previous output before XML declaration
    ob_clean();
    
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    
    foreach ($resources as $resource) {
        echo '    <url>' . "\n";
        echo '        <loc>' . htmlspecialchars(url('/resources/' . $resource->slug)) . '</loc>' . "\n";
        echo '        <lastmod>' . date('Y-m-d', strtotime($resource->updated_at ?? $resource->created_at)) . '</lastmod>' . "\n";
        echo '        <changefreq>weekly</changefreq>' . "\n";
        echo '        <priority>1.0</priority>' . "\n";
        echo '    </url>' . "\n";
    }
    
    echo '</urlset>';
    exit();
});

Route::get('/sitemap-thesis-dissertations.xml', function() {
    header('Content-Type: application/xml; charset=utf-8');
    
    $resources = DB::table('resources')
        ->where('is_published', true)
        ->get();
    
    // CRITICAL FIX: Clear any previous output before XML declaration
    ob_clean();
    
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    
    foreach ($resources as $resource) {
        echo '    <url>' . "\n";
        echo '        <loc>' . htmlspecialchars(url('/resources/' . $resource->slug)) . '</loc>' . "\n";
        echo '        <lastmod>' . date('Y-m-d', strtotime($resource->updated_at ?? $resource->created_at)) . '</lastmod>' . "\n";
        echo '        <changefreq>weekly</changefreq>' . "\n";
        echo '        <priority>1.0</priority>' . "\n";
        echo '    </url>' . "\n";
    }
    
    echo '</urlset>';
    exit();
});

// Publication topics sitemap - filtered by academic keywords
Route::get('/sitemap-publication-topics.xml', function() {
    header('Content-Type: application/xml; charset=utf-8');
    
    $resources = DB::table('resources')
        ->where('is_published', true)
        ->where(function($query) {
            $query->where('title', 'like', '%appraisal%')
                  ->orWhere('title', 'like', '%assessment%')
                  ->orWhere('title', 'like', '%case study%')
                  ->orWhere('title', 'like', '%evaluation%')
                  ->orWhere('title', 'like', '%analysis%')
                  ->orWhere('title', 'like', '%impact%')
                  ->orWhere('title', 'like', '%effect%')
                  ->orWhere('title', 'like', '%influence%')
                  ->orWhere('title', 'like', '%relationship%')
                  ->orWhere('title', 'like', '%comparison%');
        })
        ->get();
    
    // CRITICAL FIX: Clear any previous output before XML declaration
    ob_clean();
    
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    
    foreach ($resources as $resource) {
        echo '    <url>' . "\n";
        echo '        <loc>' . htmlspecialchars(url('/resources/' . $resource->slug)) . '</loc>' . "\n";
        echo '        <lastmod>' . date('Y-m-d', strtotime($resource->updated_at ?? $resource->created_at)) . '</lastmod>' . "\n";
        echo '        <changefreq>weekly</changefreq>' . "\n";
        echo '        <priority>1.0</priority>' . "\n";
        echo '    </url>' . "\n";
    }
    
    echo '</urlset>';
    exit();
});

// Master sitemap containing all resources
Route::get('/sitemap-complete.xml', function() {
    header('Content-Type: application/xml; charset=utf-8');
    
    $resources = DB::table('resources')
        ->where('is_published', true)
        ->get();
    
    // CRITICAL FIX: Clear any previous output before XML declaration
    ob_clean();
    
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    
    foreach ($resources as $resource) {
        echo '    <url>' . "\n";
        echo '        <loc>' . htmlspecialchars(url('/resources/' . $resource->slug)) . '</loc>' . "\n";
        echo '        <lastmod>' . date('Y-m-d', strtotime($resource->updated_at ?? $resource->created_at)) . '</lastmod>' . "\n";
        echo '        <changefreq>weekly</changefreq>' . "\n";
        echo '        <priority>1.0</priority>' . "\n";
        echo '    </url>' . "\n";
    }
    
    echo '</urlset>';
    exit();
});

// Add this to the end of your routes/web.php file
Route::prefix('api/s3')->middleware(['web', 'auth'])->group(function () {
    
    // Get optimized URL for fast loading
    Route::post('optimize-url', [App\Http\Controllers\S3FileController::class, 'getOptimizedUrl'])
        ->name('s3.optimize-url');
    
    // Get file metadata
    Route::get('metadata', [App\Http\Controllers\S3FileController::class, 'getFileMetadata'])
        ->name('s3.metadata');
    
    // Preload file with progressive loading
    Route::post('preload', [App\Http\Controllers\S3FileController::class, 'preloadFile'])
        ->name('s3.preload');
    
    // Clear S3 cache
    Route::post('clear-cache', [App\Http\Controllers\S3FileController::class, 'clearCache'])
        ->name('s3.clear-cache');
    
    // Get performance statistics
    Route::get('stats', [App\Http\Controllers\S3FileController::class, 'getPerformanceStats'])
        ->name('s3.stats');
    
    // Generate presigned URL
    Route::post('presigned-url', [App\Http\Controllers\S3FileController::class, 'generatePresignedUrl'])
        ->name('s3.presigned-url');
        
});