<x-filament-panels::page>
    {{ $this->form }}

    <div class="flex justify-end">
        <x-filament::button wire:click="save" wire:loading.attr="disabled">
            {{ __('Save Settings') }}
        </x-filament::button>
    </div>
</x-filament-panels::page>