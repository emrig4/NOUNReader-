<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use SEO\Models\Page;


class SeaPageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */


    // protected $fillable = [
    //     'title',
    //     'description',
    //     'path',
    //     'canonical_url',
    //     'robot_index',
    //     'robot_follow',
    //     'change_frequency',
    //     'priority',
    //     'schema',
    //     'focus_keyword'
    // ];


    public function run()
    {
        
        $routes = Route::getRoutes();
        foreach ($routes as $route) {
            if ($route->getName() != '' &&  isset($route->middleware()[0]) && $route->middleware()[0]  == 'web') {
                $page = Page::where('title', $route->getName())->first();

                if (is_null($page)) {
                    Page::create([
                        'title' => $route->action['prefix'],
                        'path' => $route->action['prefix'],
                        'description' => $route->getName()
                    ]);
                }
            }
        }

    }
}
