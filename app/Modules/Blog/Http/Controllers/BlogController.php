<?php

namespace App\Modules\Blog\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


use Carbon\Carbon;
use BinshopsBlog\Laravel\Fulltext\Search;
use BinshopsBlog\Models\BinshopsCategoryTranslation;
use BinshopsBlog\Captcha\UsesCaptcha;
use BinshopsBlog\Middleware\DetectLanguage;
use BinshopsBlog\Models\BinshopsCategory;
use BinshopsBlog\Models\BinshopsLanguage;
use BinshopsBlog\Models\BinshopsPost;
use BinshopsBlog\Models\BinshopsPostTranslation;



class BlogController extends Controller
{
    use UsesCaptcha;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    public function index(Request $request, $locale = 'en', $category_slug = null)
    {   
        // $posts = BinshopsPost::paginate(12);
        // return view('blog.index', compact('posts'));

        $lang_id = 1;
        $title = '';

        $categoryChain = null;
        $posts = array();
        if ($category_slug) {
            $category = BinshopsCategoryTranslation::where("slug", $category_slug)->with('category')->firstOrFail()->category;
            $categoryChain = $category->getAncestorsAndSelf();
            $posts = $category->posts()->where("binshops_post_categories.category_id", $category->id)->with([ 'postTranslations' => function($query) use ($request){
                $query->where("lang_id" , '=' , $request->get("lang_id"));
            }
            ])->get();

            $posts = BinshopsPostTranslation::join('binshops_posts', 'binshops_post_translations.post_id', '=', 'binshops_posts.id')
                ->where('lang_id', $request->get("lang_id"))
                ->where("is_published" , '=' , true)
                ->where('posted_at', '<', Carbon::now()->format('Y-m-d H:i:s'))
                ->orderBy("posted_at", "desc")
                ->whereIn('binshops_posts.id', $posts->pluck('id'))
                ->paginate(config("binshopsblog.per_page", 10));

            // at the moment we handle this special case (viewing a category) by hard coding in the following two lines.
            // You can easily override this in the view files.
            \View::share('binshopsblog_category', $category); // so the view can say "You are viewing $CATEGORYNAME category posts"
            $title = 'Posts in ' . $category->category_name . " category"; // hardcode title here...
        } else {
            $posts = BinshopsPostTranslation::join('binshops_posts', 'binshops_post_translations.post_id', '=', 'binshops_posts.id')
                ->where('lang_id', $lang_id)
                ->where("is_published" , '=' , true)
                ->where('posted_at', '<', Carbon::now()->format('Y-m-d H:i:s'))
                ->orderBy("posted_at", "desc")
                ->paginate(config("binshopsblog.per_page", 10));
        }

        //load category hierarchy
        $rootList = BinshopsCategory::roots()->get();
        BinshopsCategory::loadSiblingsWithList($rootList);

        return view("blog.index", [
            'lang_list' => BinshopsLanguage::all('locale','name'),
            'locale' => $request->get("locale"),
            'lang_id' => $lang_id,
            'category_chain' => $categoryChain,
            'categories' => $rootList,
            'posts' => $posts,
            'title' => $title,
        ]);



    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('blog::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Request $request,  $blogPostSlug)
    {

        // return view('blog.single');

        $lang_id = 1;
        $locale = 'en';


        // the published_at + is_published are handled by BinshopsBlogPublishedScope, and don't take effect if the logged in user can manage log posts
        $blog_post = BinshopsPostTranslation::where([
            ["slug", "=", $blogPostSlug],
            ['lang_id', "=" , $lang_id]
        ])->firstOrFail();

        if ($captcha = $this->getCaptchaObject()) {
            $captcha->runCaptchaBeforeShowingPosts($request, $blog_post);
        }

        $categories = $blog_post->post->categories()->with([ 'categoryTranslations' => function($query) use ($lang_id){
            $query->where("lang_id" , '=' , $lang_id);
        }
        ])->get();

        $related =   $categories[0]->posts()->pluck('post_id');

        $related_posts = BinshopsPostTranslation::whereIn("id", $related)
        ->where([
            ['lang_id', "=" , $lang_id]
        ])->get();

        
        return view("blog.single", [
            'post' => $blog_post,
            // the default scope only selects approved comments, ordered by id
            'comments' => $blog_post->post->comments()
                ->with("user")
                ->get(),
            'captcha' => $captcha,
            'categories' => $categories,
            'locale' =>  $locale,
            'related_posts' => $related_posts,
        ]);


    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('blog::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
