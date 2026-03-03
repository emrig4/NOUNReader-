<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SitemapController;

/*
|--------------------------------------------------------------------------
| SEO & Sitemap Routes
|--------------------------------------------------------------------------
*/

// Main XML Sitemap routes
Route::get('/sitemap.xml', [SitemapController::class, 'xml'])->name('sitemap.xml');
Route::get('/sitemap', [SitemapController::class, 'index'])->name('sitemap.index');

// PROJECT-FOCUSED SITEMAP ROUTES
Route::get('/sitemap-projects.xml', [SitemapController::class, 'projects'])->name('sitemap.projects');
Route::get('/sitemap-project-topics.xml', [SitemapController::class, 'projectTopics'])->name('sitemap.project-topics');
Route::get('/sitemap-project-materials.xml', [SitemapController::class, 'projectMaterials'])->name('sitemap.project-materials');
Route::get('/sitemap-final-year-projects.xml', [SitemapController::class, 'finalYearProjects'])->name('sitemap.final-year-projects');
Route::get('/sitemap-bsc-projects.xml', [SitemapController::class, 'bscProjects'])->name('sitemap.bsc-projects');
Route::get('/sitemap-thesis-dissertations.xml', [SitemapController::class, 'thesisDissertations'])->name('sitemap.thesis-dissertations');

// SEO-friendly project routes (for future implementation)
Route::get('/project-topics', function() {
    return view('project-topics.index');
})->name('project-topics.index');

Route::get('/project-materials', function() {
    return view('project-materials.index');
})->name('project-materials.index');

Route::get('/final-year-projects', function() {
    return view('final-year-projects.index');
})->name('final-year-projects.index');

Route::get('/bsc-projects', function() {
    return view('bsc-projects.index');
})->name('bsc-projects.index');

Route::get('/thesis-dissertations', function() {
    return view('thesis-dissertations.index');
})->name('thesis-dissertations.index');

// Sitemap generation routes (for admin/cron)
Route::middleware(['auth', 'web'])->group(function () {
    Route::post('/sitemap/generate', [SitemapController::class, 'generateAndCache'])->name('sitemap.generate');
    Route::get('/sitemap/cached', [SitemapController::class, 'cached'])->name('sitemap.cached');
    
    // TEST ROUTE: Test LinkProvider integration
    Route::get('/sitemap/test-providers', [SitemapController::class, 'testLinkProviders'])->name('sitemap.test-providers');
});

// Robots.txt route
Route::get('/robots.txt', function () {
    $content = \App\Helpers\SeoHelper::generateRobotsTxt();
    return response($content, 200, ['Content-Type' => 'text/plain']);
})->name('robots.txt');

// SEO utilities
Route::middleware(['web'])->group(function () {
    // Meta tag preview for admin
    Route::get('/seo/preview', function () {
        return view('seo.preview');
    })->name('seo.preview');
    
    // SEO analysis (basic)
    Route::get('/seo/analyze/{url}', [SitemapController::class, 'analyzeSeo'])->name('seo.analyze');
});