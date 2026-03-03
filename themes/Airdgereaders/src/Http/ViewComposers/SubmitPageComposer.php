<?php

namespace Themes\Airdgereaders\Http\ViewComposers;

use Illuminate\View\View;
use App\Modules\Resource\Models\ResourceType;
use App\Modules\Resource\Models\ResourceField;
use App\Modules\Resource\Models\Currency;

class SubmitPageComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $resourceTypes = ResourceType::orderBy('title')->get();
        $resourceFields = ResourceField::with('subfields')->orderBy('title')->get();
        $currencies = Currency::orderBy('title')->get();
        
        $view->with([
            'resourceTypes' => $resourceTypes,
            'resourceFields' => $resourceFields,
            'currencies' => $currencies
        ]);
    }
}
