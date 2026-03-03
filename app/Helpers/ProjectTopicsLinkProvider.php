<?php

namespace App\Helpers;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use App\Modules\Resource\Models\Resource;
use SEO\Contracts\LinkProvider;

/**
 * Project Topics Link Provider
 * Following architect's pattern from aupdate-fixit2
 */
class ProjectTopicsLinkProvider implements LinkProvider
{
    public function all()
    {
        $resources = Resource::where('is_published', true)
            ->where(function($query) {
                $query->where('title', 'like', '%project%')
                      ->orWhere('title', 'like', '%topics%')
                      ->orWhere('title', 'like', '%research%')
                      ->orWhere('title', 'like', '%final year%');
            })
            ->get()
            ->map(function($resource) {
                $resource->link = route('resources.show', $resource->slug);

                // FOLLOWING ARCHITECT'S PATTERN
                $resource->robot_index = 'index';
                $resource->robot_follow = 'follow';
                $resource->description = $this->generateProjectDescription($resource->overview);
                $resource->change_frequency = 'weekly'; // Higher frequency for project topics
                $resource->priority = 1.0; // Highest priority for project topics
                
                // PROJECT-SPECIFIC KEYWORDS
                $resource->focus_keyword = $this->extractProjectKeywords($resource->title);
                $resource->tags = $this->generateProjectTags($resource);
                
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
                    // PROJECT-SPECIFIC META TAGS
                    'project_type' => 'topics',
                    'educational_level' => $this->getEducationalLevel($resource),
                    'department' => $this->getDepartment($resource),
                ];

                return $resource;
            });

        return $resources;
    }

    /**
     * Generate project-focused description
     */
    private function generateProjectDescription($overview)
    {
        $description = substr(strip_tags($overview), 0, 150);
        
        // Add project context if not present
        if (!preg_match('/project/i', $description)) {
            $description .= ' - Project topics and research materials for academic success.';
        }
        
        return $description;
    }

    /**
     * Extract project keywords from title
     */
    private function extractProjectKeywords($title)
    {
        $keywords = ['project topics', 'research topics'];
        
        if (preg_match('/final year/i', $title)) {
            $keywords[] = 'final year projects';
        }
        if (preg_match('/bsc|bachelor/i', $title)) {
            $keywords[] = 'BSC project topics';
        }
        if (preg_match('/thesis|dissertation/i', $title)) {
            $keywords[] = 'thesis topics';
        }
        if (preg_match('/computer|software|engineering/i', $title)) {
            $keywords[] = 'engineering project topics';
        }
        
        return $keywords;
    }

    /**
     * Generate project-specific tags
     */
    private function generateProjectTags($resource)
    {
        $tags = ['project', 'topics', 'academic', 'research'];
        
        // Add department-specific tags
        if ($resource->field) {
            $tags[] = strtolower(str_replace(' ', '_', $resource->field->name));
        }
        
        // Add level-specific tags
        if (preg_match('/undergraduate|bsc|bachelor/i', $resource->title)) {
            $tags[] = 'undergraduate';
        }
        if (preg_match('/masters|msc|graduate/i', $resource->title)) {
            $tags[] = 'masters';
        }
        if (preg_match('/phd|doctoral/i', $resource->title)) {
            $tags[] = 'phd';
        }
        
        return $tags;
    }

    /**
     * Determine educational level from resource
     */
    private function getEducationalLevel($resource)
    {
        if (preg_match('/undergraduate|bsc|bachelor|final year/i', $resource->title)) {
            return 'Undergraduate';
        }
        if (preg_match('/masters|msc|graduate/i', $resource->title)) {
            return 'Masters';
        }
        if (preg_match('/phd|doctoral/i', $resource->title)) {
            return 'Doctoral';
        }
        return 'General';
    }

    /**
     * Get department from field relationship
     */
    private function getDepartment($resource)
    {
        return $resource->field ? $resource->field->name : 'General';
    }
}

/*

EXAMPLE OUTPUT (following architect's format):

"id" => 1
"path" => "http://projectandmaterials.com/resources/computer-science-project-topics-2025"
"object" => "App\Helpers\ProjectTopicsLinkProvider"
"object_id" => 46
"robot_index" => "index"
"robot_follow" => "follow"
"canonical_url" => "http://projectandmaterials.com/resources/computer-science-project-topics-2025"
"title" => "Computer Science Project Topics 2025 - Latest Research Areas"
"title_source" => "Computer Science Project Topics 2025 - Latest Research Areas"
"description" => "Comprehensive list of computer science project topics for 2025. Includes AI, machine learning, web development, and mobile app projects. - Project topics and research materials for academic success."
"description_source" => "Comprehensive list of computer science project topics for 2025. Includes AI, machine learning, web development, and mobile app projects. - Project topics and research materials for academic success."
"change_frequency" => "weekly"
"priority" => "1.0"
"schema" => null
"focus_keyword" => ["project topics", "research topics", "engineering project topics"]
"tags" => ["project", "topics", "academic", "research", "computer_science", "undergraduate"]
"created_at" => "2022-07-09 00:52:44"
"updated_at" => "2025-11-24 08:30:00"
"meta" => [
    6 => "Comprehensive list of computer science project topics for 2025. Includes AI, machine learning, web development, and mobile app projects. - Project topics and research materials for academic success.",
    "project_type" => "topics",
    "educational_level" => "Undergraduate",
    "department" => "Computer Science"
]

*/
