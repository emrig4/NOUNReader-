<?php

namespace Themes\Airdgereaders\Http\ViewComposers;

use Illuminate\Support\Facades\DB;
use App\Modules\Resource\Models\ResourceType;
use App\Modules\Resource\Models\ResourceField;
use App\Modules\Resource\Models\ResourceAuthor;
use App\Modules\Resource\Models\ResourceSubField;
use App\Modules\File\Models\TemporaryFile;
use App\Modules\Payment\Models\Currency;
use App\Models\User;






class UserStatsComposer
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
            'uniqueReads' => $this->getUniqueReads(),
            'downloadsCount' => $this->getDownloadsCount(),
            'payoutsCount' => $this->getPayoutsCount(),
            'worksCount' => $this->getWorksCount(),

        ]);
    }

    // airon
    private function getUniqueReads()
    {
        return ResourceField::all();
    }
    private function getDownloadsCount()
    { 
        return ResourceSubField::all();
    }
    private function getPayoutsCount()
    {
      return ResourceType::all();
    }
    private function getWorksCount()
    {
      return ResourceType::all();
    }
    

}
