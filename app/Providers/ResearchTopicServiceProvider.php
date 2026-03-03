<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class ResearchTopicServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register routes
        $this->loadRoutesFrom(__DIR__ . '/../../routes/research-topics.php');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'research-topics');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // Publish configuration
        $this->publishes([
            __DIR__ . '/../../config/research-topics.php' => config_path('research-topics.php'),
        ], 'research-topics-config');

        // Publish views
        $this->publishes([
            __DIR__ . '/../../resources/views' => resource_path('views/vendor/research-topics'),
        ], 'research-topics-views');

        // Publish assets
        $this->publishes([
            __DIR__ . '/../../public' => public_path('vendor/research-topics'),
        ], 'research-topics-assets');

        // Register middleware for authenticated routes (if needed)
        Route::middlewareGroup('research-topics', []);
    }
}