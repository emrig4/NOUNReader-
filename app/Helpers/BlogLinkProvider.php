<?php
namespace App\Helpers;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;


use BinshopsBlog\Models\BinshopsPostTranslation;

use  SEO\Contracts\LinkProvider;
/**
 *
 */
class BlogLinkProvider implements LinkProvider
{

	public function all()
    {

       
        $posts = BinshopsPostTranslation::all()->map(function($post){
            $post->link = route('blog.show', $post->slug);
            $post->robot_index = 'index';
            $post->robot_follow = 'follow';
            $post->description = substr($post->short_description, 0, 150);
            $post->change_frequency = 'monthly';
            $post->priority = 0.7;
            $post->focus_keyword = [];
            $post->tags = [];
            $post->meta = [
                1 => null,
                4 => null,
                5 => null,
                6 =>  substr($post->short_description, 0, 70), //og:description
                7 => null,
                10 => null,
                22 => null,
                25 => null,
                26 => null,
            ];

            return $post;
        });

        return $posts;

    }


    
}



/*

        "id" => 1
        "post_id" => 1
        "slug" => "jam"
        "title" => "Jam"
        "subtitle" => null
        "meta_desc" => null
        "seo_title" => null
        "post_body" => """
          <blockquote><em>According to Wikipedia, &quot;Research is a creative and systematic work undertaken to increase the stock of knowledge. It involves the collecti ▶
          
          <p>Afribary&#39;s aim is to make the whole process of collecting information for your research work and study easier. Whether you are working on an essay, proje ▶
          We understand that whole process of writing a proper research paper can be daunting, so we also provide tools like&nbsp;<a href="https://afribary.com/plagiarism ▶
          """
        "short_description" => null
        "use_view_file" => null
        "image_large" => null
        "image_medium" => null
        "image_thumbnail" => null
        "lang_id" => 1
        "created_at" => "2022-07-13 15:18:30"
        "updated_at" => "2022-07-13 15:18:30"



*/