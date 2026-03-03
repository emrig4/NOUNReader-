<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SitemapController extends Controller
{
    /**
     * Generate sitemap for PROJECT TOPICS (ALL resources)
     * Uses resources table with is_published field
     */
    public function projectTopics()
    {
        return $this->generateSitemap(
            'All project topics - Find project topics online',
            1.0,
            function () {
                return DB::table('resources')
                    ->where('is_published', true)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        );
    }

    /**
     * Generate sitemap for PROJECT MATERIALS (ALL resources)
     */
    public function projectMaterials()
    {
        return $this->generateSitemap(
            'All project materials - Download project topics and read project materials',
            0.9,
            function () {
                return DB::table('resources')
                    ->where('is_published', true)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        );
    }

    /**
     * Generate sitemap for FINAL YEAR PROJECTS (ALL resources)
     */
    public function finalYearProjects()
    {
        return $this->generateSitemap(
            'All final year projects - Final year project topics and materials',
            0.8,
            function () {
                return DB::table('resources')
                    ->where('is_published', true)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        );
    }

    /**
     * Generate sitemap for BSC PROJECTS (ALL resources)
     */
    public function bscProjects()
    {
        return $this->generateSitemap(
            'All BSC projects - BSC project topics and undergraduate projects',
            0.7,
            function () {
                return DB::table('resources')
                    ->where('is_published', true)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        );
    }

    /**
     * Generate sitemap for THESIS AND DISSERTATIONS (ALL resources)
     */
    public function thesisDissertations()
    {
        return $this->generateSitemap(
            'All thesis and dissertations - Academic research and dissertation topics',
            0.6,
            function () {
                return DB::table('resources')
                    ->where('is_published', true)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        );
    }

    /**
     * Generate sitemap for PUBLICATION TOPICS (Filtered by academic keywords)
     */
    public function publicationTopics()
    {
        return $this->generateSitemap(
            'Publication topics - Academic research topics and publications',
            0.5,
            function () {
                return DB::table('resources')
                    ->where('is_published', true)
                    ->where(function ($query) {
                        $query->where('title', 'like', '%appraisal%')
                              ->orWhere('title', 'like', '%assessment%')
                              ->orWhere('title', 'like', '%case%')
                              ->orWhere('title', 'like', '%study%')
                              ->orWhere('title', 'like', '%analysis%')
                              ->orWhere('title', 'like', '%research%');
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        );
    }

    /**
     * Generate a complete sitemap of ALL published resources
     */
    public function complete()
    {
        return $this->generateSitemap(
            'Complete sitemap - All project topics, materials, and publications',
            1.0,
            function () {
                return DB::table('resources')
                    ->where('is_published', true)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        );
    }

    /**
     * SEO Preview - Show what URLs will be generated
     */
    public function preview()
    {
        $output = "SEO Sitemap Structure - Resources Table (90 records)\n";
        $output .= "====================================================\n\n";
        
        $output .= "🔧 Using Query Builder with resources table\n";
        $output .= "📊 Published field: is_published = true\n";
        $output .= "📋 Table structure: " . "id, slug, title, is_published, ..." . "\n\n";
        
        $output .= "🎯 TARGET KEYWORDS FOR EACH Sitemap:\n\n";
        $output .= "📋 sitemap-project-topics.xml → ALL 90 published resources\n";
        $output .= "📚 sitemap-project-materials.xml → ALL 90 published resources\n";
        $output .= "🎓 sitemap-final-year-projects.xml → ALL 90 published resources\n";
        $output .= "💼 sitemap-bsc-projects.xml → ALL 90 published resources\n";
        $output .= "📖 sitemap-thesis-dissertations.xml → ALL 90 published resources\n";
        $output .= "🔬 sitemap-publication-topics.xml → Filtered academic resources\n\n";
        
        $output .= "✅ URL Format: /resources/{slug}\n";
        $output .= "🚀 SEO Target: 50,000+ topics (current: 90 published)\n";

        return response($output);
    }

    /**
     * Helper method to generate sitemap XML
     */
    private function generateSitemap($description, $priority, $queryCallback)
    {
        $resources = $queryCallback();
        
        // CRITICAL FIX: Clear any previous output before XML declaration
        ob_clean();
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        $xml .= "  <!-- {$description} - " . $resources->count() . " resources -->\n";

        foreach ($resources as $resource) {
            // Use slug field for URL generation
            $url = url('/resources/' . $resource->slug);
            
            // Format date from updated_at
            $lastmod = date('Y-m-d', strtotime($resource->updated_at));
            
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$url}</loc>\n";
            $xml .= "    <lastmod>{$lastmod}</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>{$priority}</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    /**
     * Debug method to test resources table
     */
    public function debugResourcesTable()
    {
        $output = "RESOURCES TABLE DEBUG\n";
        $output .= "=====================\n\n";
        
        try {
            // Test resources table
            $total = DB::table('resources')->count();
            $output .= "✅ Total resources: {$total}\n";
            
            $published = DB::table('resources')->where('is_published', true)->count();
            $output .= "✅ Published resources (is_published=true): {$published}\n";
            
            // Show table columns for verification
            $columns = DB::getSchemaBuilder()->getColumnListing('resources');
            $output .= "📋 Table columns: " . implode(', ', $columns) . "\n";
            
            // Show sample published resource
            $sample = DB::table('resources')->where('is_published', true)->first();
            if ($sample) {
                $output .= "\n📝 Sample published resource:\n";
                $output .= "   Title: " . substr($sample->title, 0, 50) . "...\n";
                $output .= "   Slug: " . $sample->slug . "\n";
                $output .= "   URL: /resources/" . $sample->slug . "\n";
                $output .= "   Published: " . ($sample->is_published ? 'true' : 'false') . "\n";
            }
            
            // Test academic filtering
            $academicCount = DB::table('resources')
                ->where('is_published', true)
                ->where(function ($query) {
                    $query->where('title', 'like', '%appraisal%')
                          ->orWhere('title', 'like', '%assessment%')
                          ->orWhere('title', 'like', '%case%')
                          ->orWhere('title', 'like', '%study%')
                          ->orWhere('title', 'like', '%analysis%')
                          ->orWhere('title', 'like', '%research%');
                })
                ->count();
            
            $output .= "\n🔬 Academic topics (filtered): {$academicCount}\n";
            
            $output .= "\n🚀 Sitemap Structure:\n";
            $output .= "   • Project sitemaps: {$published} resources each\n";
            $output .= "   • Publication topics: {$academicCount} resources\n";
            $output .= "   • URL format: /resources/{slug}\n";
            
        } catch (\Exception $e) {
            $output .= "❌ Error: " . $e->getMessage() . "\n";
        }
        
        return response($output);
    }
}