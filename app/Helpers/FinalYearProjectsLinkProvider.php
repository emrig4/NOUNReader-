<?php

namespace App\Helpers;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use App\Modules\Resource\Models\Resource;
use SEO\Contracts\LinkProvider;

/**
 * Final Year Projects Link Provider
 * Following architect's pattern from aupdate-fixit2
 */
class FinalYearProjectsLinkProvider implements LinkProvider
{
    public function all()
    {
        $resources = Resource::where('is_published', true)
            ->where(function($query) {
                $query->where('title', 'like', '%final year%')
                      ->orWhere('title', 'like', '%undergraduate%')
                      ->orWhere('title', 'like', '%capstone%')
                      ->orWhere('title', 'like', '%bsc%')
                      ->orWhere('title', 'like', '%hnd%')
                      ->orWhere('title', 'like', '%graduation%')
                      ->orWhere('title', 'like', '%senior project%');
            })
            ->get()
            ->map(function($resource) {
                $resource->link = route('resources.show', $resource->slug);

                // FOLLOWING ARCHITECT'S PATTERN
                $resource->robot_index = 'index';
                $resource->robot_follow = 'follow';
                $resource->description = $this->generateFinalYearDescription($resource->overview);
                $resource->change_frequency = 'weekly';
                $resource->priority = 0.9; // High priority for final year content
                
                // FINAL YEAR KEYWORDS
                $resource->focus_keyword = $this->extractFinalYearKeywords($resource->title);
                $resource->tags = $this->generateFinalYearTags($resource);
                
                // FOLLOWING ARCHITECT'S META ARRAY STRUCTURE
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
                    'project_type' => 'final_year',
                    'academic_level' => 'undergraduate',
                    'completion_status' => 'graduation_ready',
                ];

                return $resource;
            });

        return $resources;
    }

    private function generateFinalYearDescription($overview)
    {
        $description = substr(strip_tags($overview), 0, 150);
        if (!preg_match('/final year|undergraduate/i', $description)) {
            $description .= ' - Final year project resources for undergraduate students preparing for graduation.';
        }
        return $description;
    }

    private function extractFinalYearKeywords($title)
    {
        $keywords = ['final year projects', 'undergraduate projects'];
        
        if (preg_match('/capstone/i', $title)) {
            $keywords[] = 'capstone projects';
        }
        if (preg_match('/bsc|bachelor/i', $title)) {
            $keywords[] = 'BSC final year projects';
        }
        if (preg_match('/hnd/i', $title)) {
            $keywords[] = 'HND final year projects';
        }
        
        return $keywords;
    }

    private function generateFinalYearTags($resource)
    {
        $tags = ['final_year', 'undergraduate', 'capstone', 'graduation'];
        
        if ($resource->field) {
            $tags[] = strtolower(str_replace(' ', '_', $resource->field->name));
        }
        
        return $tags;
    }
}