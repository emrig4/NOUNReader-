<?php
return [
    /**
     * Layout's to be used on package pages
     */
    'layout' => 'seo::layouts.app',
    
    /**
     * Middleware that will wrap up seo routes
     */
    'middleware' => 'auth',
    
    /**
     * Link Providers - Class references as strings
     */
    'linkProviders' => [
        // Existing providers
        'App\Helpers\ResourceLinkProvider',
        'App\Helpers\ResourceFieldLinkProvider', 
        'App\Helpers\ResourceReadLinkProvider',
        'App\Helpers\BlogLinkProvider',
        
        // NEW PROJECT-SPECIFIC PROVIDERS
        'App\Helpers\ProjectTopicsLinkProvider',
        'App\Helpers\ProjectMaterialsLinkProvider',
        'App\Helpers\FinalYearProjectsLinkProvider',
        'App\Helpers\BSCProjectsLinkProvider',
        'App\Helpers\ThesisDissertationsLinkProvider',
    ],
    
    /**
     * Name of the flash variable that holds success message
     */
    'flash_message' => 'permit_message',

    /**
     * Name of the flash variable that holds error message
     */
    'flash_error' => 'permit_error',

    /**
     * Full path where robot.txt file will be saved.
     */
    'robot_txt' => public_path('robots.txt'),

    /**
     * Full path where .htaccess file will be saved.
     */
    'htaccess' => public_path('.htaccess'),

    /**
     * Public folder of your xml sitemap
     */
    'sitemap_location' => 'sitemaps',

    /**
     * PROJECT-SPECIFIC SITEMAP CONFIGURATION
     */
    'project_sitemaps' => [
        'enabled' => true,
        'location' => 'sitemaps',
        'files' => [
            'sitemap-projects.xml' => 'All project-related content',
            'sitemap-project-topics.xml' => 'Project topics specifically',
            'sitemap-project-materials.xml' => 'Project materials and resources',
            'sitemap-final-year-projects.xml' => 'Final year projects focus',
            'sitemap-bsc-projects.xml' => 'BSC/Bachelor of Science projects',
            'sitemap-thesis-dissertations.xml' => 'Thesis and dissertation resources',
        ],
        'priority_mapping' => [
            'project_topics' => '1.0',
            'project_materials' => '1.0', 
            'final_year_projects' => '0.9',
            'bsc_projects' => '0.9',
            'thesis_dissertations' => '0.9',
            'regular_resources' => '0.7',
            'blog_posts' => '0.6',
        ]
    ],

    /**
     * PROJECT KEYWORDS CONFIGURATION
     */
    'project_keywords' => [
        'primary' => [
            'project topics',
            'project materials',
            'final year projects',
            'project topics and materials',
        ],
        'secondary' => [
            'BSC project topics',
            'thesis',
            'dissertations',
            'read project topics',
            'download project topics',
            'project and materials',
            'read project materials',
        ],
        'long_tail' => [
            'free project topics download',
            'BSC final year project topics',
            'thesis and dissertation samples',
            'project materials for students',
            'final year project ideas',
            'undergraduate research topics',
            'postgraduate thesis topics',
        ],
        'department_specific' => [
            'computer_science' => [
                'computer science project topics',
                'software engineering projects',
                'web development projects',
                'mobile app development projects',
            ],
            'engineering' => [
                'engineering project topics',
                'mechanical engineering projects', 
                'electrical engineering projects',
                'civil engineering projects',
            ],
            'business' => [
                'business project topics',
                'marketing project ideas',
                'finance project topics',
                'management project ideas',
            ],
            'medicine' => [
                'medical project topics',
                'healthcare project ideas',
                'nursing project topics',
                'pharmacy project ideas',
            ],
            'arts' => [
                'arts project topics',
                'design project ideas',
                'literature project topics',
                'fine arts project ideas',
            ],
        ]
    ],

    /**
     * Cache setting
     */
    'cache' => [
        /**
         * file or database
         */
        'driver' => 'file',
        
        /**
         * Enable caching for production
         */
        'enable' => true,

        /**
         * Path where html files will be saved.
         */
        'storage' => storage_path('app/seo'),

        /**
         * Cache expiration time in seconds
         */
        'expire' => 1800, // 30 minutes
        'project_content_expire' => 1800, // 30 minutes for project content
        'regular_content_expire' => 3600, // 1 hour for regular content
    ],

    /**
     * Image Storage
     */
    'storage' => [
        /**
         * Storage driver
         */
        'driver' => 'public',

        /**
         * Prefix which will be used before every image url
         */
        'prefix' => 'storage',

        /**
         * Which folder on your driver will storage all the files
         */
        'folder' => 'seo',
        
        /**
         * PROJECT-SPECIFIC IMAGE FOLDERS
         */
        'project_images' => [
            'folder' => 'seo/projects',
            'sizes' => [
                'thumbnail' => ['width' => 150, 'height' => 150],
                'medium' => ['width' => 600, 'height' => 400], 
                'large' => ['width' => 1000, 'height' => 700],
            ],
            'formats' => ['jpg', 'jpeg', 'png', 'webp'],
        ],
    ],
    
    /**
     * SEO PERFORMANCE CONFIGURATION
     */
    'performance' => [
        'lazy_load_images' => true,
        'minify_html' => false, // Set to true in production
        'compress_css' => true,
        'compress_js' => true,
        'enable_caching' => true,
        'project_content_optimization' => true,
        'sitemap_chunk_size' => 1000, // Maximum URLs per sitemap file
        'max_sitemap_files' => 25, // Maximum sitemap index files
    ],

    /**
     * PROJECT-SPECIFIC META CONFIGURATION
     */
    'project_meta_templates' => [
        'project_topics' => [
            'title_template' => '{title} - Project Topics & Research Materials',
            'description_template' => 'Download {title} project topics and materials. Free access to research topics, final year projects, and academic resources for students.',
            'keywords_template' => '{title}, project topics, research topics, final year projects, academic materials',
            'og_title_template' => '{title} - Free Project Topics & Materials',
            'og_description_template' => 'Access thousands of free project topics and materials for your research.',
        ],
        'project_materials' => [
            'title_template' => '{title} - Project Materials & Educational Resources',
            'description_template' => 'Comprehensive {title} project materials including research papers, data sets, and academic resources.',
            'keywords_template' => '{title}, project materials, educational resources, research materials, academic papers',
            'og_title_template' => '{title} - Project Materials Library',
            'og_description_template' => 'Professional project materials and educational resources for students.',
        ],
        'final_year_projects' => [
            'title_template' => '{title} - Final Year Project Topics & Materials',
            'description_template' => 'Complete {title} final year project resources including topics, materials, and research guides.',
            'keywords_template' => '{title}, final year projects, undergraduate projects, capstone projects, graduation projects',
            'og_title_template' => '{title} - Final Year Projects',
            'og_description_template' => 'Everything you need for your final year project success.',
        ],
    ],

    /**
     * STRUCTURED DATA CONFIGURATION
     */
    'structured_data' => [
        'enabled' => true,
        'organization' => [
            'name' => 'readprojecttopics - Project Topics & Materials',
            'description' => 'Leading platform for free project topics, materials, and research resources for students',
            'url' => env('APP_URL'),
            'logo' => env('APP_URL') . '/images/logo',
            'same_as' => [],
        ],
        'project_schemas' => [
            'educational_resource' => [
                '@type' => 'EducationalResource',
                'educationalLevel' => ['Undergraduate', 'Graduate'],
                'about' => [
                    '@type' => 'Thing',
                    'name' => 'Project Topics and Research Materials'
                ]
            ],
            'collection_page' => [
                '@type' => 'CollectionPage',
                'name' => 'Project Topics and Materials',
                'description' => 'Comprehensive collection of project topics and educational materials',
                'url' => env('APP_URL'),
            ]
        ]
    ],

    /**
     * ANALYTICS AND MONITORING
     */
    'monitoring' => [
        'log_seo_issues' => true,
        'track_page_performance' => true,
        'monitor_broken_links' => true,
        'project_content_tracking' => true,
        'alert_on' => [
            'sitemap_errors' => true,
            'high_error_rates' => true,
            'performance_issues' => true,
            'project_keyword_drops' => true,
        ],
    ],
];
