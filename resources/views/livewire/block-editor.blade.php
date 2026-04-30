<div>
    @if($editingIndex === null)
        {{-- LIST VIEW --}}
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Blocks') }}</h3>
            <button
                type="button"
                wire:click="addBlock('text')"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-500"
            >
                + {{ __('Add Block') }}
            </button>
        </div>

        @if(empty($blocks))
            <div class="flex flex-col items-center justify-center py-16 text-gray-400 dark:text-gray-600">
                <p class="text-sm">{{ __('No blocks yet. Add your first block.') }}</p>
            </div>
        @else
            <div id="block-list" style="display:flex; flex-direction:column; gap:0.5rem;">
                @foreach($blocks as $index => $block)
                    <div
                        class="flex items-center gap-3 px-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer hover:border-primary-400 transition-colors"
                        wire:click="editBlock({{ $index }})"
                        data-index="{{ $index }}"
                    >
                        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 flex-shrink-0">
                            <x-filament::icon icon="heroicon-o-rectangle-stack" class="w-4 h-4" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 capitalize">{{ $block['type'] }}</p>
                            <p class="text-xs text-gray-400 truncate">
                                {{ $block['data']['heading'] ?? $block['data']['title'] ?? $block['data']['text'] ?? '—' }}
                            </p>
                        </div>
                        <div class="flex items-center gap-1 flex-shrink-0" onclick="event.stopPropagation()">
                            <div class="cursor-grab text-gray-300 dark:text-gray-600 hover:text-gray-500 px-1 drag-handle">
                                <x-filament::icon icon="heroicon-o-bars-2" class="w-4 h-4" />
                            </div>
                            <button
                                type="button"
                                wire:click.stop="removeBlock({{ $index }})"
                                class="p-1 text-gray-300 dark:text-gray-600 hover:text-red-500 transition-colors"
                            >
                                <x-filament::icon icon="heroicon-o-trash" class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    @else
        {{-- DETAIL VIEW --}}
        <div class="flex items-center gap-3 mb-6">
            <button
                type="button"
                wire:click="backToList()"
                class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
            >
                <x-filament::icon icon="heroicon-o-arrow-left" class="w-4 h-4" />
                {{ __('Back') }}
            </button>
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 capitalize">
                {{ $blocks[$editingIndex]['type'] ?? '' }}
            </h3>
        </div>

        <p class="text-sm text-gray-400">{{ __('Fields coming soon.') }}</p>
    @endif
</div>