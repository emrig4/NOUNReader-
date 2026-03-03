<?php

namespace App\Helpers;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use App\Modules\Resource\Models\Resource;
use SEO\Contracts\LinkProvider;

/**
 * Project Materials Link Provider
 * Following architect's pattern from aupdate-fixit2
 */
class ProjectMaterialsLinkProvider implements LinkProvider
{
    public function all()
    {
        $resources = Resource::where('is_published', true)
            ->where(function($query) {
                $query->where('title', 'like', '%material%')
                      ->orWhere('title', 'like', '%resource%')
                      ->orWhere('title', 'like', '%document%')
                      ->orWhere('title', 'like', '%paper%')
                      ->orWhere('title', 'like', '%guide%')
                      ->orWhere('title', 'like', '%tutorial%')
                      ->orWhere('title', 'like', '%reference%')
                      ->orWhere('title', 'like', '%sample%');
            })
            ->get()
            ->map(function($resource) {
                $resource->link = route('resources.show', $resource->slug);

                // FOLLOWING ARCHITECT'S PATTERN
                $resource->robot_index = 'index';
                $resource->robot_follow = 'follow';
                $resource->description = $this->generateMaterialDescription($resource->overview);
                $resource->change_frequency = 'weekly'; // Weekly updates for materials
                $resource->priority = 1.0; // High priority for materials
                
                // PROJECT-SPECIFIC KEYWORDS
                $resource->focus_keyword = $this->extractMaterialKeywords($resource->title);
                $resource->tags = $this->generateMaterialTags($resource);
                
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
                    'project_type' => 'materials',
                    'material_type' => $this->getMaterialType($resource),
                    'format' => $this->getResourceFormat($resource),
                    'accessibility' => 'free', // Materials are free
                ];

                return $resource;
            });

        return $resources;
    }

    /**
     * Generate materials-focused description
     */
    private function generateMaterialDescription($overview)
    {
        $description = substr(strip_tags($overview), 0, 150);
        
        // Add material context if not present
        if (!preg_match('/material|resource/i', $description)) {
            $description .= ' - Comprehensive project materials and educational resources for academic success.';
        }
        
        return $description;
    }

    /**
     * Extract material keywords from title
     */
    private function extractMaterialKeywords($title)
    {
        $keywords = ['project materials', 'educational resources'];
        
        if (preg_match('/sample|example/i', $title)) {
            $keywords[] = 'project samples';
        }
        if (preg_match('/guide|tutorial/i', $title)) {
            $keywords[] = 'project guides';
        }
        if (preg_match('/reference|bibliography/i', $title)) {
            $keywords[] = 'project references';
        }
        if (preg_match('/document|paper/i', $title)) {
            $keywords[] = 'project documents';
        }
        if (preg_match('/template|format/i', $title)) {
            $keywords[] = 'project templates';
        }
        
        return $keywords;
    }

    /**
     * Generate material-specific tags
     */
    private function generateMaterialTags($resource)
    {
        $tags = ['materials', 'resources', 'documents', 'academic'];
        
        // Add material type tags
        if (preg_match('/sample|example/i', $resource->title)) {
            $tags[] = 'samples';
        }
        if (preg_match('/guide|tutorial/i', $resource->title)) {
            $tags[] = 'guides';
        }
        if (preg_match('/template|format/i', $resource->title)) {
            $tags[] = 'templates';
        }
        if (preg_match('/reference|bibliography/i', $resource->title)) {
            $tags[] = 'references';
        }
        
        // Add department-specific tags
        if ($resource->field) {
            $tags[] = strtolower(str_replace(' ', '_', $resource->field->name));
        }
        
        return $tags;
    }

    /**
     * Determine material type from title/description
     */
    private function getMaterialType($resource)
    {
        $title = strtolower($resource->title);
        
        if (preg_match('/sample|example/i', $title)) {
            return 'samples';
        }
        if (preg_match('/guide|tutorial/i', $title)) {
            return 'guides';
        }
        if (preg_match('/reference|bibliography/i', $title)) {
            return 'references';
        }
        if (preg_match('/template|format/i', $title)) {
            return 'templates';
        }
        if (preg_match('/document|paper/i', $title)) {
            return 'documents';
        }
        
        return 'general';
    }

    /**
     * Determine resource format
     */
    private function getResourceFormat($resource)
    {
        // This would ideally check the actual file format
        // For now, infer from title
        $title = strtolower($resource->title);
        
        if (preg_match('/pdf/i', $title)) {
            return 'pdf';
        }
        if (preg_match('/doc|word/i', $title)) {
            return 'document';
        }
        if (preg_match('/ppt|presentation/i', $title)) {
            return 'presentation';
        }
        if (preg_match('/excel|spreadsheet/i', $title)) {
            return 'spreadsheet';
        }
        
        return 'mixed';
    }
}