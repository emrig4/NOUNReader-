<?php

namespace App\Helpers;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use App\Modules\Resource\Models\Resource;
use SEO\Contracts\LinkProvider;

/**
 * Thesis and Dissertations Link Provider
 * Following architect's pattern from aupdate-fixit2
 */
class ThesisDissertationsLinkProvider implements LinkProvider
{
    public function all()
    {
        $resources = Resource::where('is_published', true)
            ->where(function($query) {
                $query->where('title', 'like', '%thesis%')
                      ->orWhere('title', 'like', '%dissertation%')
                      ->orWhere('title', 'like', '%masters%')
                      ->orWhere('title', 'like', '%msc%')
                      ->orWhere('title', 'like', '%phd%')
                      ->orWhere('title', 'like', '%doctoral%')
                      ->orWhere('title', 'like', '%graduate%')
                      ->orWhere('title', 'like', '%academic writing%')
                      ->orWhere('title', 'like', '%research proposal%')
                      ->orWhere('title', 'like', '%literature review%');
            })
            ->get()
            ->map(function($resource) {
                $resource->link = route('resources.show', $resource->slug);

                $resource->robot_index = 'index';
                $resource->robot_follow = 'follow';
                $resource->description = $this->generateThesisDescription($resource->overview);
                $resource->change_frequency = 'monthly'; // Less frequent for thesis content
                $resource->priority = 0.9;
                
                $resource->focus_keyword = $this->extractThesisKeywords($resource->title);
                $resource->tags = $this->generateThesisTags($resource);
                
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
                    'project_type' => 'thesis_dissertation',
                    'academic_level' => $this->getAcademicLevel($resource),
                    'document_type' => $this->getDocumentType($resource),
                ];

                return $resource;
            });

        return $resources;
    }

    private function generateThesisDescription($overview)
    {
        $description = substr(strip_tags($overview), 0, 150);
        if (!preg_match('/thesis|dissertation|graduate/i', $description)) {
            $description .= ' - Professional thesis and dissertation resources for graduate students and researchers.';
        }
        return $description;
    }

    private function extractThesisKeywords($title)
    {
        $keywords = ['thesis', 'dissertations', 'graduate research'];
        
        if (preg_match('/masters|msc/i', $title)) {
            $keywords[] = 'masters thesis';
            $keywords[] = 'MSc dissertation';
        }
        if (preg_match('/phd|doctoral/i', $title)) {
            $keywords[] = 'PhD thesis';
            $keywords[] = 'doctoral dissertation';
        }
        if (preg_match('/proposal/i', $title)) {
            $keywords[] = 'research proposal';
        }
        if (preg_match('/literature review/i', $title)) {
            $keywords[] = 'literature review';
        }
        if (preg_match('/academic writing/i', $title)) {
            $keywords[] = 'academic writing';
        }
        
        return $keywords;
    }

    private function generateThesisTags($resource)
    {
        $tags = ['thesis', 'dissertation', 'graduate', 'academic_writing'];
        
        if (preg_match('/masters|msc/i', $resource->title)) {
            $tags[] = 'masters';
        }
        if (preg_match('/phd|doctoral/i', $resource->title)) {
            $tags[] = 'phd';
            $tags[] = 'doctoral';
        }
        if (preg_match('/proposal/i', $resource->title)) {
            $tags[] = 'research_proposal';
        }
        if (preg_match('/literature review/i', $resource->title)) {
            $tags[] = 'literature_review';
        }
        
        if ($resource->field) {
            $tags[] = strtolower(str_replace(' ', '_', $resource->field->name));
        }
        
        return $tags;
    }

    private function getAcademicLevel($resource)
    {
        if (preg_match('/masters|msc/i', $resource->title)) {
            return 'Masters';
        }
        if (preg_match('/phd|doctoral/i', $resource->title)) {
            return 'Doctoral';
        }
        return 'Graduate';
    }

    private function getDocumentType($resource)
    {
        if (preg_match('/thesis/i', $resource->title)) {
            return 'thesis';
        }
        if (preg_match('/dissertation/i', $resource->title)) {
            return 'dissertation';
        }
        if (preg_match('/proposal/i', $resource->title)) {
            return 'proposal';
        }
        if (preg_match('/literature review/i', $resource->title)) {
            return 'literature_review';
        }
        return 'academic';
    }
}