<?php

namespace Themes\Airdgereaders\Http\ViewComposers;

use Illuminate\Support\Facades\DB;
use App\Modules\Resource\Models\ResourceType;
use App\Modules\Resource\Models\ResourceField;
use App\Modules\File\Models\TemporaryFile;

class SubmitPageComposer
{
    /**
     * Bind data to the view.
     *
     * @param \Illuminate\View\View $view
     * @return void
     */
    public function create($view)
    {
        $view->with([
            'resourceFields' => $this->getResourceFields(),
            'resourceTypes' => $this->getResourceTypes(),
            'uploadedFile' => $this->getUploadedFile(),
            'currencies' => $this->getCurrencies(),
        ]);
    }

    private function getResourceFields()
    {
        return ResourceField::all();
    }

    private function getResourceTypes()
    {
        return ResourceType::all();
    }

    private function getUploadedFile()
    {
        $sessionId = session()->getId();
        $file = TemporaryFile::where('session_id', $sessionId)->latest()->first();
        return $file;
    }

    private function getCurrencies()
    {
        // Hardcoded: Only Naira and readprojecttopics Credit
        return collect([
            (object)['code' => 'NGN', 'name' => 'Naira', 'symbol' => '₦'],
            (object)['code' => 'RANC', 'name' => 'readprojecttopics Credit', 'symbol' => 'RANC']
        ]);
    }
}
