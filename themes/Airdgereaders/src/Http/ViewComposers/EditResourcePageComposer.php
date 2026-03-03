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






class EditResourcePageComposer
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
            'resourceFields' => $this->getResourceFields(),
            'resourceTypes' => $this->getResourceTypes(),
            // 'resourceSubfields' => $this->getResourceSubFields(),
            'uploadedFile' => $this->getUploadedFile(),
            'currencies' => $this->getCurrencies(),
            'authors' => $this->getAuthors(),
        ]);
    }

    // airon
    private function getResourceFields()
    {
        return ResourceField::all();
    }
    private function getResourceSubFields()
    { 
        return ResourceSubField::all();
    }
    private function getResourceTypes()
    {
      return ResourceType::all();
    }
    private function getUploadedFile()
    {
         // get the last temp file from session
        $sessionId = session()->getId();
        $file = TemporaryFile::where('session_id', $sessionId)->latest()->first();
        return $file;
    }

    private function getCurrencies()
    {
       return Currency::all();
    }

    private function getAuthors()
    {
        return ResourceAuthor::select('fullname')->pluck('fullname')->unique();
    }

}
