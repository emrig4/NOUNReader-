<?php

namespace Themes\Airdgereaders\Providers;

use Themes\Airdgereaders\Http\ViewComposers\PublishPageComposer;
use Themes\Airdgereaders\Http\ViewComposers\EditResourcePageComposer;
use Themes\Airdgereaders\Http\ViewComposers\ResourceFieldsComposer;
use Themes\Airdgereaders\Http\ViewComposers\FeaturedResourceFieldsComposer;
use Themes\Airdgereaders\Http\ViewComposers\RecentResourceComposer;
use Themes\Airdgereaders\Http\ViewComposers\AdminStatsComposer;
use Themes\Airdgereaders\Http\ViewComposers\BlogSidebarComposer;
use Themes\Airdgereaders\Http\ViewComposers\SearchComposer;
use Caffeinated\Themes\Support\ServiceProvider;
use Illuminate\Support\Facades\View;


class ThemeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        parent::boot();

        // Register error view namespace to prevent "No hint path defined for [errors]" error
        $this->loadViewsFrom(resource_path('views/errors'), 'errors');

        // public pubish page  either ::composer of ::creator
        View::creator('resource.create.publish', PublishPageComposer::class);
        View::creator('resource.edit', EditResourcePageComposer::class);
        View::creator('admin.resources.edit', EditResourcePageComposer::class);
        View::creator('resource.partials.fields_grid', ResourceFieldsComposer::class);
        View::creator('resource.partials.featured_fields_grid', FeaturedResourceFieldsComposer::class);
        View::creator('resource.partials.recent_resource_grid', RecentResourceComposer::class);
        
        View::creator('admin.partials.stats', AdminStatsComposer::class);
        View::creator('blog.partials.blog_sidebar', BlogSidebarComposer::class);
        View::creator('partials.search_mini', SearchComposer::class);
        View::creator('partials.search_mega', SearchComposer::class);

    }

    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }
}