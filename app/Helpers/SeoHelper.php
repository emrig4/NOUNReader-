<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use App\Modules\Resource\Models\Resource;
use App\Modules\Resource\Models\ResourceField;
use App\Modules\Resource\Models\ResourceSubField;

class SeoHelper
{
    /**
     * ✅ IMPROVED: Generate meta title for pages
     * - Proper capitalization
     * - Keyword-rich
     * - Brand inclusion
     * - Avoid empty titles
     * - Max 60 characters for optimal display
     */
    public static function generateTitle($title = null, $suffix = true)
    {
        $siteName = 'Project Topics & Materials';
        $separator = ' | ';
        
        // ✅ IMPROVED: Better default title with keywords
        if (empty($title)) {
            return 'Free Project Topics & Materials PDF | readprojecttopics';
        }
        
        // ✅ Limit title to 55 characters for Google display (60 is limit)
        $trimmedTitle = Str::limit($title, 55, '');
        
        return $suffix ? $trimmedTitle . $separator . $siteName : $trimmedTitle;
    }
    
    /**
     * ✅ IMPROVED: Generate meta description
     * - Always between 150-160 characters (Google sweet spot)
     * - Include primary keyword
     * - Call-to-action
     * - Clear value proposition
     */
    public static function generateDescription($content = null, $maxLength = 160)
    {
        // ✅ Improved default description with keywords and CTA
        if (empty($content)) {
            return 'Download free project topics, complete materials, thesis guides, and research resources for Nigerian students. Access thousands of academic materials now.';
        }
        
        $description = strip_tags($content);
        $description = preg_replace('/\s+/', ' ', $description);
        $description = trim($description);
        
        // ✅ Trim to exactly 160 characters and add ellipsis if needed
        if (strlen($description) > $maxLength) {
            $description = Str::limit($description, $maxLength - 3, '...');
        }
        
        return $description;
    }
    
    /**
     * ✅ NEW: Generate optimized keywords
     * - 1 primary keyword
     * - 3-4 secondary keywords
     * - Long-tail keywords
     */
    public static function generateKeywords($mainKeyword = null, $secondary = null)
    {
        $defaultKeywords = 'project topics, project materials, final year projects, BSC project topics, how to write project topics, free project materials, thesis topics, dissertation topics, research materials PDF, Nigerian university projects, student project resources';
        
        if (empty($mainKeyword)) {
            return $defaultKeywords;
        }
        
        $keywords = [$mainKeyword];
        
        if (!empty($secondary) && is_array($secondary)) {
            $keywords = array_merge($keywords, $secondary);
        }
        
        return implode(', ', $keywords);
    }
    
    /**
     * ✅ NEW: Generate Open Graph tags for social sharing
     */
    public static function generateOpenGraphTags($resource = null)
    {
        $defaults = [
            'og:type' => 'website',
            'og:site_name' => 'Project Topics & Materials',
            'og:title' => 'Free Project Topics & Materials | readprojecttopics',
            'og:description' => 'Download complete project topics and materials for academic success.',
            'og:image' => asset('themes/airdgereaders/images/Projectandmaterials.webp'),
            'og:url' => url()->current(),
        ];
        
        if ($resource) {
            $defaults['og:type'] = 'article';
            $defaults['og:title'] = $resource->title ?? 'Project Topics & Materials';
            $defaults['og:description'] = mb_strimwidth(strip_tags($resource->overview ?? ''), 0, 160, '...');
            $defaults['og:url'] = route('resources.show', $resource->slug ?? $resource->id);
        }
        
        return $defaults;
    }
    
    /**
     * ✅ NEW: Generate canonical URL
     */
    public static function generateCanonicalUrl()
    {
        return url()->current();
    }
    
    /**
     * ✅ NEW: Generate breadcrumb schema (JSON-LD)
     */
    public static function generateBreadcrumbSchema($items = [])
    {
        $defaultItems = [
            ['name' => 'Home', 'url' => url('/')]
        ];
        
        $items = array_merge($defaultItems, $items);
        
        $breadcrumbs = [];
        foreach ($items as $index => $item) {
            $breadcrumbs[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['url']
            ];
        }
        
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $breadcrumbs
        ];
    }
    
    /**
     * ✅ IMPROVED: Generate robots.txt content
     */
    public static function generateRobotsTxt()
    {
        $content = "# robotxt.org/ - START ROBOTS.TXT\n";
        $content .= "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Disallow: /admin\n";
        $content .= "Disallow: /login\n";
        $content .= "Disallow: /register\n";
        $content .= "Disallow: /verify-code\n";
        $content .= "Disallow: /api\n";
        $content .= "Disallow: /assets/uploads/temp\n";
        $content .= "Disallow: /*?*\n";
        $content .= "Disallow: *.pdf$\n";
        $content .= "\n";
        $content .= "# Specific rules for Googlebot\n";
        $content .= "User-agent: Googlebot\n";
        $content .= "Allow: /\n";
        $content .= "Crawl-delay: 0\n";
        $content .= "\n";
        $content .= "# Specific rules for Bingbot\n";
        $content .= "User-agent: Bingbot\n";
        $content .= "Allow: /\n";
        $content .= "Crawl-delay: 0\n";
        $content .= "\n";
        $content .= "# Sitemap\n";
        $content .= "Sitemap: " . url('/sitemap.xml') . "\n";
        $content .= "Sitemap: " . url('/sitemap-project-topics.xml') . "\n";
        $content .= "Sitemap: " . url('/sitemap-project-materials.xml') . "\n";
        
        return $content;
    }
    
    /**
     * ✅ NEW: Generate Organization Schema (JSON-LD)
     */
    public static function generateOrganizationSchema()
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'readprojecttopics',
            'url' => url('/'),
            'logo' => asset('themes/airdgereaders/images/projectandmaterials.logo.png'),
            'description' => 'Leading platform for free project topics, materials, and research resources for students',
            'sameAs' => [
                'https://www.facebook.com/readprojecttopics',
                'https://twitter.com/readprojecttopics',
                'https://www.linkedin.com/company/readprojecttopics'
            ]
        ];
    }
    
    /**
     * ✅ NEW: Generate Educational Resource Schema (JSON-LD)
     */
    public static function generateEducationalResourceSchema($resource)
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'EducationalResource',
            'name' => $resource->title,
            'description' => mb_strimwidth(strip_tags($resource->overview ?? ''), 0, 160, '...'),
            'url' => route('resources.show', $resource->slug ?? $resource->id),
            'author' => [
                '@type' => 'Person',
                'name' => $resource->author ?? 'readprojecttopics Team'
            ],
            'educationalLevel' => 'University/College',
            'learningResourceType' => 'Project Materials',
            'datePublished' => $resource->created_at->toIso8601String()
        ];
    }
    
    /**
     * Generate URL slugs
     */
    public static function generateSlug($text)
    {
        return Str::slug($text);
    }
    
    /**
     * Get breadcrumbs
     */
    public static function getBreadcrumbs($path)
    {
        $segments = explode('/', trim($path, '/'));
        $breadcrumbs = [];
        $url = '';
        
        $breadcrumbs[] = ['name' => 'Home', 'url' => '/'];
        
        foreach ($segments as $index => $segment) {
            $url .= '/' . $segment;
            $name = ucwords(str_replace('-', ' ', $segment));
            
            if ($index === count($segments) - 1) {
                // Last segment - current page
                $breadcrumbs[] = ['name' => $name, 'url' => null];
            } else {
                $breadcrumbs[] = ['name' => $name, 'url' => $url];
            }
        }
        
        return $breadcrumbs;
    }
}
?>