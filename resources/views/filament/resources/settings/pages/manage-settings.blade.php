<x-filament-panels::page>
    {{ $this->form }}

    <div class="flex justify-end">
        <x-filament::button wire:click="save" wire:loading.attr="disabled">
            {{ __('settings.actions.save') }}
        </x-filament::button>
    </div>
</x-filament-panels::page>