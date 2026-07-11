<x-filament-panels::page>
    {{ $this->form }}

    <div class="flex justify-end">
        <x-filament::button wire:click="invite" wire:loading.attr="disabled">
            {{ __('laravix::users.actions.send_invitation') }}
        </x-filament::button>
    </div>
</x-filament-panels::page>