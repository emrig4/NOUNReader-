<?php
// Safe blog category creator
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use BinshopsBlog\Models\BinshopsCategory;
use BinshopsBlog\Models\BinshopsCategoryTranslation;

echo "Creating blog categories...\n";

$categories = [
    ['slug' => 'news', 'name' => 'News'],
    ['slug' => 'tutorials', 'name' => 'Tutorials'],
    ['slug' => 'updates', 'name' => 'Updates'],
    ['slug' => 'tips', 'name' => 'Tips & Tricks'],
];

foreach ($categories as $cat) {
    $exists = BinshopsCategory::where('slug', $cat['slug'])->first();
    if (!$exists) {
        $category = BinshopsCategory::create(['slug' => $cat['slug'], 'category_image' => null]);
        BinshopsCategoryTranslation::create([
            'category_id' => $category->id, 'lang_id' => 1,
            'category_name' => $cat['name'], 'meta_title' => $cat['name'],
            'meta_desc' => '', 'category_description' => ''
        ]);
        echo "✓ Created: {$cat['name']}\n";
    } else {
        echo "○ Already exists: {$cat['name']}\n";
    }
}

echo "\nTotal categories: " . BinshopsCategory::count() . "\n";
echo "Done!\n";