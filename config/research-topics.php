<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Research Topics Suggestion Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration settings for the Research Topics
    | Suggestion Tool. You can customize these settings according to your needs.
    |
    */

    'settings' => [
        'max_suggestions_per_request' => 15,
        'default_suggestions_count' => 10,
        'enable_database_topics' => true,
        'enable_generated_topics' => true,
        'cache_duration' => 3600, // 1 hour in seconds
        'enable_export' => true,
        'enable_search' => true,
        'enable_favorites' => true,
    ],

    'ui' => [
        'theme' => 'default', // default, dark, modern
        'show_descriptions' => true,
        'show_source_indicators' => true,
        'show_export_button' => true,
        'show_save_favorites' => true,
        'results_per_page' => 10,
    ],

    'departments' => [
        'enabled' => true,
        'show_all' => true,
        'custom_departments' => [], // Add custom departments here
    ],

    'topic_types' => [
        'enabled' => true,
        'available_types' => ['project', 'thesis', 'dissertation'],
        'show_type_labels' => true,
    ],

    'generation' => [
        'enable_ai_suggestions' => false, // Set to true if you have AI integration
        'topic_variations_per_base' => 3,
        'include_keywords' => true,
        'include_difficulty_levels' => true,
    ],

    'export' => [
        'formats' => ['csv', 'pdf', 'json'],
        'include_metadata' => true,
        'max_export_records' => 1000,
    ],

    'search' => [
        'min_keyword_length' => 2,
        'max_keyword_length' => 100,
        'search_in_titles' => true,
        'search_in_descriptions' => true,
        'search_in_tags' => true,
        'search_in_keywords' => true,
    ],

    'performance' => [
        'enable_caching' => true,
        'cache_key_prefix' => 'research_topics_',
        'enable_pagination' => true,
        'items_per_page' => 20,
        'enable_lazy_loading' => true,
    ],

    'security' => [
        'rate_limit_requests' => 60, // requests per minute
        'rate_limit_window' => 60, // seconds
        'enable_csrf_protection' => true,
        'sanitize_input' => true,
        'max_input_length' => 1000, // characters
    ],

    'logging' => [
        'enable_logging' => true,
        'log_searches' => true,
        'log_suggestions' => true,
        'log_exports' => true,
    ],

    'analytics' => [
        'track_page_views' => true,
        'track_searches' => true,
        'track_downloads' => true,
        'google_analytics_id' => null, // Set your GA ID here
    ],
];