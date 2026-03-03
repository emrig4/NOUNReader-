<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\DocumentNotificationService;

class DocumentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(DocumentNotificationService::class, function ($app) {
            return new DocumentNotificationService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}