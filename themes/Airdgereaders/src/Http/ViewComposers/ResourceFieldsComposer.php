<?php

namespace Themes\Airdgereaders\Http\ViewComposers;

use App\Modules\Resource\Models\ResourceField;


class ResourceFieldsComposer
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
            'resourceFields' => $this->getResourceFields()
        ]);
    }

    // airon
    private function getResourceFields()
    {
        return ResourceField::all();
    }
}
