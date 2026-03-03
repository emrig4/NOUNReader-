<?php
$lang_id = 1;

// Get posts directly
$posts = \BinshopsBlog\Models\BinshopsPost::where("is_published", "=", true)
    ->where("posted_at", "<", \Carbon\Carbon::now()->format("Y-m-d H:i:s"))
    ->orderBy("posted_at", "desc")
    ->with(["postTranslations" => function($query) use ($lang_id) {
        $query->where("lang_id", "=", $lang_id);
    }])
    ->paginate(10);

echo "<h1>Blog Debug</h1>";
echo "<p>Total posts in DB: " . \BinshopsBlog\Models\BinshopsPost::count() . "</p>";
echo "<p>Published posts: " . $posts->count() . "</p>";

echo "<h2>Posts:</h2>";
foreach($posts as $p) {
    $trans = $p->postTranslations->first();
    echo "<div style='border:1px solid #ccc; padding:10px; margin:10px;'>";
    echo "<h3>Post ID: " . $p->id . "</h3>";
    echo "<p>Translation: " . ($trans ? $trans->title : "NO TRANSLATION FOUND") . "</p>";
    echo "<p>Slug: " . ($trans ? $trans->slug : "N/A") . "</p>";
    echo "<p>Body: " . ($trans ? substr($trans->post_body, 0, 100) . "..." : "N/A") . "</p>";
    echo "<p><a href='/blog/" . ($trans ? $trans->slug : "") . "'>View Post</a></p>";
    echo "</div>";
}

echo "<h2>View Check:</h2>";
echo "<p>View exists: " . (file_exists("themes/Airdgereaders/resources/views/blog/index.blade.php") ? "YES" : "NO") . "</p>";
echo "<p>Layout exists: " . (file_exists("themes/Airdgereaders/resources/views/layouts/public.blade.php") ? "YES" : "NO") . "</p>";
