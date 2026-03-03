<x-jet-form-section submit="updatePersonalInformation(Object.fromEntries(new FormData($event.target)))">
    <x-slot name="title">
    </x-slot>

    <x-slot name="description">
    </x-slot>

    <x-slot name="form">
    	<!-- Biography -->
        <div class="">
            <x-jet-label for="biography" value="{{ __('Biography') }}" />
            <x-textarea id="biography" type="text" class="mb-4 block w-full" wire:model.defer="state.biography" autocomplete="biography" />
            <x-jet-input-error for="biography" class="mt-2 contents" />
        </div>
        <!--  -->
        <div class="flex mt-10 space-x-2">
            <!-- DOB -->
            <div class="w-1/2">
                <x-jet-label for="dob" value="{{ __('Date of Birth') }}" />
                <x-date id="dob"  type="text" class="mb-4 block w-full" wire:model.defer="state.dob" autocomplete="dob" :error="$errors->first('dob')"/>
                <x-jet-input-error for="dob" class="mt-2" />
            </div>
            <!-- Gender -->
            <div class="w-1/2">
                <x-jet-label for="gender" value="{{ __('Gender') }}" />
                <x-select id="gender" type="text" class="mb-4-1 block w-full" wire:model.defer="state.gender" autocomplete="gender">
                    <x-slot name="options">
                        <option value="">Choose Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </x-slot>
                </x-select>
                <x-jet-input-error for="gender" class="mt-2" />
            </div>
        </div>
        <!-- Interest -->
        <div class="flex mt-10 space-x-2">
            <!-- Interest -->
            <div class="w-full">
                <x-jet-label for="Interest" value="{{ __('Interest') }}" />

                <div class="tag-wrapper">
                    <div class="tag-container" id="subfieldscontainer">
                        @foreach($interests as $interest)
                        <div class="tag subfield">
                            <span>{{$interest}}</span>
                            <i class="fa fa-close text-xs" data-item="{{$interest}}"></i>
                        </div>
                        @endforeach

                        <x-jet-input  list="subfieldslist" type="text" class=" mb-4-1 block w-full"  placeholder="Select or type new" autocomplete="Interest" />
                    </div>
                  </div>

                  <!-- actual new subfields input hidden -->
                  <x-jet-input value="" id="subfields" type="text" class="hidden mb-4-1 block w-full"  name="interest"  autocomplete="Interest" />

               

                <datalist id="subfieldslist">
                    @foreach($subfields as $subfield)
                        <option value="{{$subfield->slug}}">{{$subfield->title}}</option>
                    @endforeach
                </datalist>
                <x-jet-input-error for="twitter" class="mt-2" />
            </div>
        </div>

    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-jet-action-message>

        <x-jet-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>


