<?php

namespace App\Modules\Account\Http\Livewire\Settings;
use App\Modules\Account\Http\Requests\SaveContactInformationRequest;
use App\Modules\Account\Models\Account;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;



use Livewire\Component;

class UpdateContactInfo extends Component
{
    public $state = '';


    /**
     * Prepare the component.
     *
     * @return void
     */
    public function mount()
    {
        $this->state = Auth::user()->account->toArray();
    }

	/**
     * Update the user's contact information.
     *
     * @param  \Laravel\Fortify\Contracts\UpdatesUserProfileInformation  $updater
     * @return void
     */
    public function updateContactInformation(SaveContactInformationRequest  $request)
    {
        $this->resetErrorBag();

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

        $this->emit('refresh-navigation-menu');
    }

    public function render()
    {
        return view('account::livewire.settings.update-contact-info');
    }
}
