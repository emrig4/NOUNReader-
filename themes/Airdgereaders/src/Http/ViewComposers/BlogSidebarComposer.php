<?php

namespace Themes\Airdgereaders\Http\ViewComposers;
use BinshopsBlog\Models\BinshopsCategory;
use BinshopsBlog\Models\BinshopsCategoryTranslation;
use BinshopsBlog\Models\BinshopsLanguage;

class BlogSidebarComposer
{
    /**
     * Bind data to the view.
     *
     * @param \Illuminate\View\View $view
     * @return void
     */
    // either create or compose
    public function create($view)
    {
        $view->with([
            'all_categories' => $this->getCategories(),
        ]);
    }


    private function getCategories()
    {
        $language_id = 1;
        $all_categories = BinshopsCategoryTranslation::orderBy("category_id")->where('lang_id', $language_id)->paginate(25);        
        return $all_categories;
    }

    

}
