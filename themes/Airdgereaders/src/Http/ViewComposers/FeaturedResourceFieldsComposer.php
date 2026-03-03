<?php

namespace Themes\Airdgereaders\Http\ViewComposers;

use App\Modules\Resource\Models\ResourceField;


class FeaturedResourceFieldsComposer
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
            'featuredResourceFields' => $this->getResourceFields()
        ]);
    }

    // airon
    private function getResourceFields()
    {
        return ResourceField::limit(12)->get();
    }
}
