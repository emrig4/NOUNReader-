<?php

namespace Themes\Airdgereaders\Http\ViewComposers;

use App\Modules\Resource\Models\Resource;


class RecentResourceComposer
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
            'resources' => $this->getRecentResource()
        ]);
    }

    // airon
    private function getRecentResource()
    {
        return Resource::limit(6)->get();
    }
}
