<?php

namespace App\Helpers;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use App\Modules\Resource\Models\Resource;
use SEO\Contracts\LinkProvider;

/**
 * BSC Projects Link Provider
 * Following architect's pattern from aupdate-fixit2
 */
class BSCProjectsLinkProvider implements LinkProvider
{
    public function all()
    {
        $resources = Resource::where('is_published', true)
            ->where(function($query) {
                $query->where('title', 'like', '%bsc%')
                      ->orWhere('title', 'like', '%bachelor of science%')
                      ->orWhere('title', 'like', '%science%')
                      ->orWhereHas('field', function($q) {
                          $q->whereIn('slug', ['computer-science', 'engineering', 'physics', 'chemistry', 'biology', 'mathematics']);
                      });
            })
            ->get()
            ->map(function($resource) {
                $resource->link = route('resources.show', $resource->slug);

                $resource->robot_index = 'index';
                $resource->robot_follow = 'follow';
                $resource->description = $this->generateBSCDescription($resource->overview);
                $resource->change_frequency = 'weekly';
                $resource->priority = 0.9;
                
                $resource->focus_keyword = $this->extractBSCKeywords($resource->title);
                $resource->tags = $this->generateBSCTags($resource);
                
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
                    'project_type' => 'bsc',
                    'degree_type' => 'bachelor_science',
                    'subject_area' => $this->getSubjectArea($resource),
                ];

                return $resource;
            });

        return $resources;
    }

    private function generateBSCDescription($overview)
    {
        $description = substr(strip_tags($overview), 0, 150);
        if (!preg_match('/bsc|bachelor|science/i', $description)) {
            $description .= ' - Bachelor of Science project topics and materials for science students.';
        }
        return $description;
    }

    private function extractBSCKeywords($title)
    {
        $keywords = ['BSC project topics', 'bachelor science projects'];
        
        if (preg_match('/computer|software/i', $title)) {
            $keywords[] = 'computer science BSC projects';
        }
        if (preg_match('/engineering/i', $title)) {
            $keywords[] = 'engineering BSC projects';
        }
        if (preg_match('/biology|life/i', $title)) {
            $keywords[] = 'biology BSC projects';
        }
        if (preg_match('/chemistry/i', $title)) {
            $keywords[] = 'chemistry BSC projects';
        }
        if (preg_match('/physics/i', $title)) {
            $keywords[] = 'physics BSC projects';
        }
        
        return $keywords;
    }

    private function generateBSCTags($resource)
    {
        $tags = ['bsc', 'bachelor_science', 'science'];
        
        if ($resource->field) {
            $fieldName = strtolower(str_replace(' ', '_', $resource->field->name));
            if (in_array($fieldName, ['computer_science', 'engineering', 'physics', 'chemistry', 'biology', 'mathematics'])) {
                $tags[] = $fieldName;
            }
        }
        
        return $tags;
    }

    private function getSubjectArea($resource)
    {
        if ($resource->field) {
            return $resource->field->name;
        }
        return 'Science';
    }
}