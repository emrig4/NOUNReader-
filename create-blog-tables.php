<?php
/**
 * Blog Tables Creator Script - Updated with Nested Set columns
 */

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== Creating Blog Tables (Updated) ===\n\n";

try {
    // Drop tables if they exist (in correct order due to foreign keys)
    $tables = [
        'binshops_post_categories',
        'binshops_category_translations',
        'binshops_post_translations',
        'binshops_comments',
        'binshops_tagged_posts',
        'binshops_categories',
        'binshops_posts',
        'binshops_languages'
    ];
    
    foreach ($tables as $table) {
        if (Schema::hasTable($table)) {
            Schema::drop($table);
            echo "Dropped: $table\n";
        } else {
            echo "Already gone: $table\n";
        }
    }
    
    echo "\n--- Creating tables ---\n\n";
    
    // Create languages table
    Schema::create('binshops_languages', function ($table) {
        $table->increments('id');
        $table->string('locale', 20);
        $table->string('name', 255);
        $table->boolean('is_default')->default(0);
    });
    echo "Created: binshops_languages\n";
    
    // Create categories table (with Nested Set columns)
    Schema::create('binshops_categories', function ($table) {
        $table->increments('id');
        $table->string('slug', 255)->unique();
        $table->string('category_image', 255)->nullable();
        $table->integer('parent_id')->nullable()->unsigned();
        $table->integer('lft')->nullable()->unsigned();
        $table->integer('rgt')->nullable()->unsigned();
        $table->integer('depth')->nullable()->unsigned();
        $table->timestamps();
    });
    echo "Created: binshops_categories (with nested set)\n";
    
    // Create category translations table
    Schema::create('binshops_category_translations', function ($table) {
        $table->increments('id');
        $table->integer('category_id')->unsigned();
        $table->integer('lang_id')->unsigned();
        $table->string('category_name', 255);
        $table->text('category_description')->nullable();
        $table->string('meta_title', 255)->nullable();
        $table->text('meta_desc')->nullable();
        $table->index('category_id');
        $table->index('lang_id');
    });
    echo "Created: binshops_category_translations\n";
    
    // Create posts table
    Schema::create('binshops_posts', function ($table) {
        $table->increments('id');
        $table->string('slug', 255)->unique();
        $table->datetime('posted_at');
        $table->boolean('is_published')->default(0);
        $table->integer('author_id')->nullable()->unsigned();
        $table->string('image_large', 255)->nullable();
        $table->string('image_medium', 255)->nullable();
        $table->string('image_thumbnail', 255)->nullable();
        $table->timestamps();
    });
    echo "Created: binshops_posts\n";
    
    // Create post translations table
    Schema::create('binshops_post_translations', function ($table) {
        $table->increments('id');
        $table->integer('post_id')->unsigned();
        $table->integer('lang_id')->unsigned();
        $table->string('title', 255);
        $table->string('slug', 255);
        $table->text('intro')->nullable();
        $table->longText('body')->nullable();
        $table->string('meta_title', 255)->nullable();
        $table->text('meta_desc')->nullable();
        $table->index('post_id');
        $table->index('lang_id');
    });
    echo "Created: binshops_post_translations\n";
    
    // Create post categories table
    Schema::create('binshops_post_categories', function ($table) {
        $table->integer('post_id')->unsigned();
        $table->integer('category_id')->unsigned();
        $table->primary(['post_id', 'category_id']);
    });
    echo "Created: binshops_post_categories\n";
    
    // Create comments table
    Schema::create('binshops_comments', function ($table) {
        $table->increments('id');
        $table->integer('post_id')->unsigned();
        $table->integer('user_id')->nullable()->unsigned();
        $table->integer('parent_id')->nullable()->unsigned();
        $table->text('comment');
        $table->boolean('is_approved')->default(1);
        $table->timestamps();
        $table->index('post_id');
    });
    echo "Created: binshops_comments\n";
    
    // Create tagged posts table
    Schema::create('binshops_tagged_posts', function ($table) {
        $table->integer('post_id')->unsigned();
        $table->integer('tag_id')->unsigned();
        $table->primary(['post_id', 'tag_id']);
    });
    echo "Created: binshops_tagged_posts\n";
    
    // Insert default language
    DB::table('binshops_languages')->insert([
        'id' => 1,
        'locale' => 'en',
        'name' => 'English',
        'is_default' => 1
    ]);
    echo "\nAdded default language (English)\n";
    
    echo "\n=== Blog Tables Created Successfully! ===\n";
    
    // Verify tables
    echo "\nVerifying tables...\n";
    $count = 0;
    foreach ($tables as $table) {
        if (Schema::hasTable($table)) {
            $rows = DB::table($table)->count();
            echo "  ✓ $table ($rows rows)\n";
            $count++;
        }
    }
    echo "\nTotal tables created: $count\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}