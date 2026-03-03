<?php

namespace App\Modules\Account\Http\Livewire\Settings;
use App\Modules\Account\Http\Requests\SaveAccountSettingsRequest;
use App\Modules\Account\Models\Account;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Modules\Resource\Models\ResourceSubField;



use Livewire\Component;

class UpdatePersonalInformation extends Component
{
    public $state = '';
    public $subfields = '';
    public $interests;


    /**
     * Prepare the component.
     *
     * @return void
     */
    public function mount()
    {   
        $this->subfields = ResourceSubField::all();
        $this->state = Auth::user()->account->toArray();
        $this->interests = explode(',', $this->state['interest']);

    }

	/**
     * Update the user's contact information.
     *
     * @param  \Laravel\Fortify\Contracts\UpdatesUserProfileInformation  $updater
     * @return void
     */
    public function updatePersonalInformation(SaveAccountSettingsRequest $request, $formData)
    {
        
        // dd($formData);
        $this->resetErrorBag();
        $this->state['interest'] = $formData['interest'];
         $validated = Validator::make(
                $this->state,
                $request->rules(),
                $request->messages()
         )->validate();


        $account = Account::where('user_id', auth()->user()->id)->first();
        if($account){
            $account->update($validated);
            $this->emit('saved');
        }else{
            Account::create($validated);
            $this->emit('saved');
        }



        // if (isset($this->photo)) {
        //     return redirect()->route('account.settings');
        // }

        // $this->emit('saved');

        // $this->emit('refresh-navigation-menu');
    }

    public function render()
    {
        return view('account::livewire.settings.update-personal-infomation');
    }
}
