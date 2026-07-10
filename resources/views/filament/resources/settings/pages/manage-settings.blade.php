<x-filament-panels::page>
    {{ $this->form }}

    @if ($this->group === 'api')
        {{ $this->table }}
    @else
        <div class="flex justify-end">
            <x-filament::button wire:click="save" wire:loading.attr="disabled">
                {{ __('settings.actions.save') }}
            </x-filament::button>
        </div>
    @endif
</x-filament-panels::page>
