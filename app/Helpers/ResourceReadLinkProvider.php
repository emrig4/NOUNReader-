<?php
namespace App\Helpers;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use App\Modules\Resource\Models\Resource;
use  SEO\Contracts\LinkProvider;
/**
 *
 */
class ResourceReadLinkProvider implements LinkProvider
{

	public function all()
    {
        $resources = Resource::all()->map(function($resource){
            $resource->link = route('resources.read',  $resource->slug);
            

            $resource->robot_index = 'index';
            $resource->robot_follow = 'follow';
            $resource->description = substr($resource->overview, 0, 150);
            $resource->change_frequency = 'monthly';
            $resource->priority = 0.7;
            $resource->focus_keyword = [];
            $resource->tags = [];
            $resource->meta = [
                4 => null,
                5 => null,
                6 =>  $resource->description, // og:description
                7 => null,
                10 => null,
                22 => null,
                25 => null,
                26 => null,
                1 => null,
            ];

            return $resource;
        });

        // dd($resources);
        return $resources;

    }


    
}



/*

    "id" => 10
    "path" => "http://readprojecttopics.test/resources/effects-and-administration-of-value-added-tax-in-the-nigeria-economy-a-study-of-federal-board-of"
    "object" => "App\Helpers\ResourceLinkProvider"
    "object_id" => 46
    "robot_index" => "index"
    "robot_follow" => "follow"
    "canonical_url" => "http://readprojecttopics.test/resources/effects-and-administration-of-value-added-tax-in-the-nigeria-economy-a-study-of-federal-board-of"
    "title" => "EFFECTS AND ADMINISTRATION OF VALUE ADDED TAX IN THE NIGERIA ECONOMY. "
    "title_source" => "EFFECTS AND ADMINISTRATION OF VALUE ADDED TAX IN THE NIGERIA ECONOMY. "
    "description" => "Many countries of the world today have been striving very hard to achieve rapid overall development through the optimum assessment and tax administrat"
    "description_source" => "Many countries of the world today have been striving very hard to achieve rapid overall development through the optimum assessment and tax administrat"
    "change_frequency" => "monthly"
    "priority" => "0.5"
    "schema" => null
    "focus_keyword" => null
    "tags" => null
    "created_at" => "2022-07-09 00:52:44"
    "updated_at" => "2022-07-22 08:46:07"



*/
