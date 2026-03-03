<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class PlagiarismCheckerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Merge config
        $this->mergeConfigFrom(__DIR__ . '/../../config/plagiarism-checker.php', 'plagiarism-checker');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load routes
        if (file_exists(__DIR__ . '/../../routes/plagiarism-checker.php')) {
            Route::middleware('web')
                ->prefix('plagiarism-checker')
                ->group(__DIR__ . '/../../routes/plagiarism-checker.php');
        }

        // Load views
        if (is_dir(__DIR__ . '/../../resources/views')) {
            $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'plagiarism-checker');
        }

        // Publish assets
        $this->publishes([
            __DIR__ . '/../../public' => public_path(),
            __DIR__ . '/../../config' => config_path(),
        ], 'plagiarism-checker');

        // Publish migrations
        $this->publishes([
            __DIR__ . '/../../database/migrations' => database_path('migrations'),
        ], 'plagiarism-checker-migrations');
    }
}
