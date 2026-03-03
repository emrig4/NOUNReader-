<?php

namespace Themes\Airdgereaders\Http\ViewComposers;
use App\Modules\Resource\Models\ResourceType;

class SearchComposer
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
            'resourceTypes' => $this->getTypes(),
        ]);
    }


    private function getTypes()
    {
       
        return ResourceType::all();
    }

    

}
