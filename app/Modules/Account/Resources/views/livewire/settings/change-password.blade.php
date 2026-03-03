<div>
    <x-jet-form-section submit="updatePassword">
	    <x-slot name="form">
	        <div class="col-span-6 sm:col-span-4">
	            <x-jet-label for="current_password" value="{{ __('Current Password') }}" />
	            <x-jet-input id="current_password" type="password" class="mb-4 block w-full" wire:model.defer="state.current_password" autocomplete="current-password" />
	            <x-jet-input-error for="current_password" class="mt-2" />
	        </div>

	        <div class="col-span-6 sm:col-span-4">
	            <x-jet-label for="password" value="{{ __('New Password') }}" />
	            <x-jet-input id="password" type="password" class="mb-4 block w-full" wire:model.defer="state.password" autocomplete="new-password" />
	            <x-jet-input-error for="password" class="mt-2" />
	        </div>

	        <div class="col-span-6 sm:col-span-4">
	            <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
	            <x-jet-input id="password_confirmation" type="password" class="mb-4 block w-full" wire:model.defer="state.password_confirmation" autocomplete="new-password" />
	            <x-jet-input-error for="password_confirmation" class="mt-2" />
	        </div>

	        <x-jet-input id="username" type="text" class="hidden mb-4 block w-full" wire:model.defer="state.username" autocomplete="username" />

	    </x-slot>

	    <x-slot name="actions">
	        <x-jet-action-message class="mr-3" on="saved">
	            {{ __('Saved.') }}
	        </x-jet-action-message>

	        <x-jet-button>
	            {{ __('Save') }}
	        </x-jet-button>
	    </x-slot>
	</x-jet-form-section>

</div>


