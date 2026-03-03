<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | SEO Configuration - UPDATED FOR PROJECT TOPICS & MATERIALS
    |--------------------------------------------------------------------------
    |
    | Optimized for project topics, final year projects, and academic materials
    |
    */
    
    'sitemap' => [
        
        'enabled' => true,
        'cache_enabled' => true,
        'cache_duration' => 86400, // 24 hours in seconds
        'max_urls' => 50000, // Maximum URLs per sitemap
        'chunk_size' => 1000, // Process URLs in chunks
        
        'include' => [
            'homepage' => true,
            'static_pages' => true,
            'blog_posts' => true,
            'resources' => true,
            'resource_categories' => true,
            'resource_topics' => true,
            'resource_types' => true,
            'author_pages' => true,
            'pricing_pages' => true,
            'project_topics' => true,
            'final_year_projects' => true,
            'bsc_projects' => true,
            'thesis_dissertations' => true,
        ],
        
        'exclude' => [
            'admin_routes' => true,
            'auth_routes' => true,
            'api_routes' => true,
            'private_pages' => true,
        ],
        
        'priorities' => [
            'homepage' => '1.0',
            'main_pages' => '0.9',
            'project_topics' => '0.9',
            'final_year_projects' => '0.9',
            'blog_posts' => '0.8',
            'resources' => '0.8',
            'bsc_projects' => '0.8',
            'thesis_dissertations' => '0.8',
            'categories' => '0.7',
            'topics' => '0.6',
            'author_pages' => '0.5',
        ],
        
        'change_frequencies' => [
            'homepage' => 'daily',
            'main_pages' => 'weekly',
            'project_topics' => 'daily',
            'final_year_projects' => 'daily',
            'blog_posts' => 'weekly',
            'resources' => 'weekly',
            'bsc_projects' => 'weekly',
            'thesis_dissertations' => 'weekly',
            'categories' => 'weekly',
            'topics' => 'weekly',
            'author_pages' => 'weekly',
            'static_pages' => 'monthly',
        ],
    ],
    
    'meta' => [
        
        'default' => [
            'title' => 'Free Project Topics & Materials PDF | Download Academic Resources | readprojecttopics',
            'description' => 'Download free project topics, complete materials, thesis guides, and research resources for Nigerian students. Access thousands of academic materials now.',
            'keywords' => 'project topics, project materials, final year projects, BSC project topics, how to write project topics, free project materials, thesis topics, dissertation topics, research materials PDF, Nigerian university projects, student project resources',
            'author' => 'readprojecttopics Team',
            'robots' => 'index, follow',
            'language' => 'en',
            'og_title' => 'Free Project Topics & Materials | Download Academic Resources',
            'og_description' => 'Access thousands of free project topics, complete materials, and research resources for students. Download now and ace your final year project.',
            'og_image' => env('APP_URL') . '/themes/airdgereaders/images/Projectandmaterials.webp',
        ],
        
        'project_specific' => [
            'project_topics' => [
                'title' => 'Free Project Topics | Download Complete Materials | readprojecttopics',
                'description' => 'Get project topics with complete materials. Download PDF files, research guides, and sample projects. Perfect for final year students.',
                'keywords' => 'project topics, project materials, research topics, free project downloads, final year projects, BSC project topics',
                'og_title' => 'Project Topics & Materials - Free Download',
                'og_description' => 'Access complete project topics with downloadable materials and research guides.',
            ],
            
            'project_materials' => [
                'title' => 'Project Materials & Research Guides | Download Now',
                'description' => 'Download comprehensive project materials including research papers, sample projects, and study guides. Perfect for academic research.',
                'keywords' => 'project materials, research materials, project PDF, academic resources, study guides, thesis materials',
                'og_title' => 'Complete Project Materials Library',
                'og_description' => 'Professional project materials and educational resources for students.',
            ],
            
            'final_year_projects' => [
                'title' => 'Final Year Project Topics & Materials | Free Download',
                'description' => 'Complete collection of final year project topics and materials. Download free final year project ideas, materials, and resources for BSC students.',
                'keywords' => 'final year projects, final year project topics, final year project materials, BSC final year projects, undergraduate projects',
                'og_title' => 'Final Year Projects - Topics & Materials',
                'og_description' => 'Everything you need for your final year project success.',
            ],
            
            'bsc_projects' => [
                'title' => 'BSC Project Topics & Materials - Free Download',
                'description' => 'BSC project topics and materials for Bachelor of Science students. Access free project ideas, materials, and resources for BSC final year projects.',
                'keywords' => 'BSC project topics, BSC projects, Bachelor of Science projects, science project topics, BSC final year projects',
                'og_title' => 'BSC Project Topics & Materials',
            ],
            
            'thesis_dissertations' => [
                'title' => 'Thesis & Dissertations - Topics & Materials | Free Resources',
                'description' => 'Find thesis and dissertation topics with sample materials. Download research guides and learn from completed projects.',
                'keywords' => 'thesis topics, dissertation topics, thesis materials, dissertation materials, graduate thesis, PhD thesis',
                'og_title' => 'Thesis & Dissertation Resources',
                'og_description' => 'Find your perfect thesis or dissertation topic with our comprehensive resources.',
            ],
        ],
        
        'og' => [
            'site_name' => 'readprojecttopics - Project Topics & Materials',
            'type' => 'website',
            'locale' => 'en_US',
            'app_id' => null, // Facebook App ID if available
        ],
        
        'twitter' => [
            'card' => 'summary_large_image',
            'site' => '@readprojecttopics', // Twitter handle
            'creator' => '@readprojecttopics',
        ],
        
        'structured_data' => [
            'enabled' => true,
            'organization' => [
                'name' => 'readprojecttopics',
                'url' => env('APP_URL'),
                'logo' => env('APP_URL') . '/images/logo.png',
                'same_as' => [
                    'https://www.facebook.com/readprojecttopics',
                    'https://twitter.com/readprojecttopics',
                    'https://www.linkedin.com/company/readprojecttopics',
                ],
            ],
            
            'educational_organization' => [
                'name' => 'readprojecttopics Project Resources',
                'description' => 'Leading platform for project topics and materials',
                'url' => env('APP_URL'),
                'type' => 'EducationalOrganization',
            ],
        ],
    ],
    
    'performance' => [
        
        'lazy_load_images' => true,
        'minify_html' => false, // Set to true in production
        'compress_css' => true,
        'compress_js' => true,
        'enable_caching' => true,
        
    ],
    
    'crawling' => [
        
        'user_agent' => 'Mozilla/5.0 (compatible; ProjectResourcesBot/1.0)',
        'crawl_delay' => 1, // Seconds between requests
        'respect_robots' => true,
        
        'sitemaps' => [
            'auto_discover' => true,
            'submit_to_search_engines' => false, // Set to true if you want auto-submission
        ],
    ],
    
    'analytics' => [
        
        'google_analytics' => [
            'enabled' => false,
            'tracking_id' => env('GOOGLE_ANALYTICS_ID'),
            'anonymize_ip' => true,
        ],
        
        'google_tag_manager' => [
            'enabled' => false,
            'container_id' => env('GOOGLE_TAG_MANAGER_ID'),
        ],
        
        'facebook_pixel' => [
            'enabled' => false,
            'pixel_id' => env('FACEBOOK_PIXEL_ID'),
        ],
    ],
    
    'social' => [
        
        'sharing' => [
            'enabled' => true,
            'networks' => [
                'facebook' => true,
                'twitter' => true,
                'linkedin' => true,
                'whatsapp' => true,
                'telegram' => true,
            ],
        ],
        
        'open_graph' => [
            'image' => env('APP_URL') . '/themes/airdgereaders/images/Projectandmaterials.webp',
            'image_width' => 1200,
            'image_height' => 630,
        ],
        
        'twitter_card' => [
            'image' => env('APP_URL') . '/themes/airdgereaders/images/Projectandmaterials.webp',
            'image_width' => 1200,
            'image_height' => 600,
        ],
    ],
    
    'security' => [
        
        'xss_protection' => true,
        'content_type_nosniff' => true,
        'frame_options' => 'SAMEORIGIN',
        'referrer_policy' => 'strict-origin-when-cross-origin',
        
    ],
    
    'cdn' => [
        
        'enabled' => env('CDN_ENABLED', false),
        'base_url' => env('CDN_BASE_URL'),
        'asset_versions' => true,
        
        'optimize_images' => true,
        'image_formats' => ['webp', 'jpg', 'png'],
        'max_image_size' => 2048, // KB
        
    ],
    
    'monitoring' => [
        
        'log_seo_issues' => true,
        'track_page_performance' => true,
        'monitor_broken_links' => true,
        
        'alert_on' => [
            'sitemap_errors' => true,
            'high_error_rates' => true,
            'performance_issues' => true,
        ],
    ],
    
    // NEW: Project-specific SEO configurations
    'project_keywords' => [
        'primary' => [
            'project topics',
            'project materials',
            'final year projects',
            'BSC project topics',
            'thesis',
            'dissertations',
        ],
        
        'secondary' => [
            'read project topics',
            'read project materials',
            'project and materials',
            'download project topics',
            'project ideas',
            'research materials',
            'academic resources',
            'project download',
            'free project topics',
            'project resources',
            'thesis topics',
            'dissertation topics',
            'undergraduate projects',
            'senior projects',
            'graduate thesis',
            'PhD thesis',
            'research thesis',
            'academic thesis',
            'project topics download',
            'project materials free',
        ],
        
        'long_tail' => [
            'free project topics for final year students',
            'BSC project topics and materials download',
            'thesis and dissertation topics free',
            'final year project ideas and materials',
            'undergraduate project topics and resources',
            'read project topics and materials online',
            'download free project materials for BSC',
            'thesis topics and dissertation ideas',
            'academic project topics and materials',
            'final year project resources and topics',
            'BSC final year project topics download',
            'research project topics and materials',
            'graduate thesis topics and dissertation ideas',
            'project topics and materials for students',
            'free academic project topics and resources',
        ],
        
        'location_based' => [
            'project topics Nigeria',
            'project materials Africa',
            'final year projects university',
            'BSC project topics college',
            'thesis topics Africa',
            'dissertation topics Nigeria',
        ],
        
        'subject_specific' => [
            'engineering project topics',
            'computer science project topics',
            'business project topics',
            'education project topics',
            'medicine project topics',
            'law project topics',
            'arts project topics',
            'science project topics',
            'technology project topics',
        ],
    ],
    
    'content_optimization' => [
        'title_length' => [
            'homepage' => '60 characters',
            'category' => '60 characters',
            'resource' => '60 characters',
            'blog' => '60 characters',
        ],
        
        'description_length' => [
            'homepage' => '160 characters',
            'category' => '155 characters',
            'resource' => '155 characters',
            'blog' => '155 characters',
        ],
        
        'keyword_density' => [
            'target' => '1-2%',
            'secondary' => '0.5-1%',
            'long_tail' => '0.5%',
        ],
        
        'heading_structure' => [
            'h1' => '1 per page (main keyword)',
            'h2' => '2-3 per page (secondary keywords)',
            'h3' => '3-5 per page (long-tail keywords)',
        ],
    ],
];

?>