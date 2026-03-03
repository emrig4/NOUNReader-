<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Theme;
use Nwidart\Modules\LaravelModulesServiceProvider;
use Illuminate\Support\Facades\Schema;
use Digikraaft\Paystack\Paystack;
use Illuminate\Support\Facades\Gate;
// use SEO\SeoServiceProvider;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->register(LaravelModulesServiceProvider::class);
        // $this->app->register(SeoServiceProvider::class);

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $activeTheme = config('themes')['active'];
        Theme::set($activeTheme);

        Paystack::setApiKey(config('paystacksubscription.secret', env('PAYSTACK_SECRET')));

        Schema::defaultStringLength(125);


        Gate::before(function ($user, $ability) {
            return $user->hasRole('sudo') ? true : null;
        });

        // to be remove latter
         Gate::before(function ($user, $ability) {
            return $user->hasRole('admin') ? true : null;
        });
    }
}
