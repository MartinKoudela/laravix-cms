<div>
    <style>
        .block-card { transition: border-color 0.15s, box-shadow 0.15s; }
        .block-card:hover { border-color: var(--color-primary-400, #60a5fa) !important; box-shadow: 0 1px 4px 0 rgba(0,0,0,0.06); }
        .block-card .drag-handle { opacity: 0; transition: opacity 0.15s; cursor: grab; }
        .block-card:hover .drag-handle { opacity: 1; }
        .sortable-ghost { opacity: 0.35; border: 2px dashed var(--color-primary-400, #60a5fa) !important; background: transparent !important; }
        .sortable-chosen { box-shadow: 0 4px 16px 0 rgba(0,0,0,0.12); }
        .sortable-drag { opacity: 1; }
        .image-thumb { cursor: pointer; border: 2px solid transparent; border-radius: 0.5rem; overflow: hidden; transition: border-color 0.15s; aspect-ratio: 1; }
        .image-thumb:hover { border-color: var(--color-primary-400, #60a5fa); }
        .image-thumb.selected { border-color: var(--color-primary-500, #2563eb); }
        .block-type-btn { display: flex; align-items: center; gap: 0.625rem; width: 100%; padding: 0.625rem 0.875rem; font-size: 0.8125rem; color: #374151; background: transparent; border: none; cursor: pointer; border-radius: 0.5rem; text-align: left; transition: background 0.1s; }
        .block-type-btn:hover { background: #f3f4f6; }
    </style>
    @if($editingIndex === null)
        {{-- LIST VIEW --}}
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
            <h3 style="font-size:0.875rem; font-weight:600; color:#374151;">{{ __('laravix::blocks.actions.blocks') }}</h3>

            <div x-data="{ open: false }" style="position:relative;">
                <button
                    type="button"
                    @click="open = !open"
                    style="display:inline-flex; align-items:center; gap:0.375rem; padding:0.375rem 0.875rem; font-size:0.8125rem; font-weight:500; border-radius:0.5rem; background:var(--color-primary-600, #2563eb); color:#fff; border:none; cursor:pointer;"
                >
                    <svg style="width:0.875rem;height:0.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    {{ __('laravix::blocks.actions.add_block') }}
                    <svg style="width:0.75rem;height:0.75rem;transition:transform 0.15s;" :style="open ? 'transform:rotate(180deg)' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div
                    x-show="open"
                    x-cloak
                    @click.outside="open = false"
                    style="position:absolute; right:0; top:calc(100% + 0.375rem); width:13rem; background:#fff; border:1px solid #e5e7eb; border-radius:0.75rem; box-shadow:0 8px 24px rgba(0,0,0,0.12); z-index:50; padding:0.375rem; overflow:hidden;"
                >
                    @forelse($blockTypes as $bt)
                        <button
                            type="button"
                            class="block-type-btn"
                            wire:click="addBlock('{{ $bt['key'] }}')"
                            @click="open = false"
                        >
                            @if($bt['icon'])
                                <x-filament::icon :icon="$bt['icon']" style="width:1rem;height:1rem;color:#9ca3af;flex-shrink:0;" />
                            @else
                                <svg style="width:1rem;height:1rem;color:#9ca3af;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            @endif
                            {{ __($bt['label']) }}
                        </button>
                    @empty
                        <p style="padding:0.75rem; font-size:0.8125rem; color:#9ca3af;">{{ __('laravix::blocks.messages.no_types') }}</p>
                    @endforelse
                </div>
            </div>
        </div>

        @if(empty($blocks))
            <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; padding:4rem 1rem; color:#9ca3af;">
                <svg style="width:2.5rem;height:2.5rem;margin-bottom:0.75rem;opacity:0.4;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <p style="font-size:0.875rem;">{{ __('laravix::blocks.messages.no_blocks') }}</p>
            </div>
        @else
            <div
                id="block-list"
                style="display:flex; flex-direction:column; gap:0.375rem;"
                x-init="
                    new window.Sortable($el, {
                        handle: '.drag-handle',
                        animation: 150,
                        ghostClass: 'sortable-ghost',
                        chosenClass: 'sortable-chosen',
                        dragClass: 'sortable-drag',
                        onEnd: function(evt) {
                            const items = $el.querySelectorAll('[data-index]');
                            const order = Array.from(items).map(el => parseInt(el.dataset.index));
                            $wire.reorderBlocks(order);
                        }
                    });
                "
            >
                @foreach($blocks as $index => $block)
                    <div
                        class="block-card"
                        style="display:flex; align-items:center; gap:0.75rem; padding:0.75rem 1rem; background:#fff; border:1.5px solid #e5e7eb; border-radius:0.75rem; cursor:pointer;"
                        wire:click="editBlock({{ $index }})"
                        data-index="{{ $index }}"
                    >
                        <div style="display:flex; align-items:center; justify-content:center; width:2.25rem; height:2.25rem; border-radius:0.5rem; background:#f3f4f6; color:#6b7280; flex-shrink:0;">
                            @php $def = \Laravix\Cms\Support\BlockRegistry::all()[$block['type']] ?? null; @endphp
                            @if($def?->icon)
                                <x-filament::icon :icon="$def->icon" style="width:1rem;height:1rem;" />
                            @else
                                <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            @endif
                        </div>
                        <div style="flex:1; min-width:0;">
                            <p style="font-size:0.8125rem; font-weight:600; color:#111827; text-transform:capitalize; margin:0;">
                                {{ $def ? __($def->label) : $block['type'] }}
                            </p>
                            <p style="font-size:0.75rem; color:#9ca3af; margin:0.125rem 0 0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                {{ $block['data']['heading'] ?? $block['data']['title'] ?? $block['data']['text'] ?? '—' }}
                            </p>
                        </div>
                        <div style="display:flex; align-items:center; gap:0.25rem; flex-shrink:0;" onclick="event.stopPropagation()">
                            <div class="drag-handle" style="padding:0.25rem; color:#d1d5db;">
                                <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                            </div>
                            <button
                                type="button"
                                wire:click.stop="removeBlock({{ $index }})"
                                style="padding:0.25rem; color:#d1d5db; background:transparent; border:none; cursor:pointer; border-radius:0.375rem; transition:color 0.15s;"
                                onmouseenter="this.style.color='#ef4444'"
                                onmouseleave="this.style.color='#d1d5db'"
                            >
                                <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    @else
        {{-- DETAIL VIEW --}}
        <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1.5rem;">
            <button
                type="button"
                wire:click="backToList()"
                style="display:inline-flex; align-items:center; gap:0.375rem; font-size:0.875rem; color:#6b7280; background:transparent; border:none; cursor:pointer; padding:0;"
            >
                <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                {{ __('laravix::blocks.actions.back') }}
            </button>
            <span style="font-size:0.875rem; font-weight:600; color:#374151; text-transform:capitalize;">
                @php $def = \Laravix\Cms\Support\BlockRegistry::all()[$blocks[$editingIndex]['type'] ?? ''] ?? null; @endphp
                {{ $def ? __($def->label) : ($blocks[$editingIndex]['type'] ?? '') }}
            </span>
        </div>

        @forelse($blockFields as $field)
            <div style="margin-bottom:1.25rem;">
                <label style="display:block; font-size:0.75rem; font-weight:500; color:#6b7280; margin-bottom:0.375rem;">
                    {{ $field['label'] }}
                </label>

                @if($field['type'] === 'image')
                    @php $currentId = $blocks[$editingIndex]['data'][$field['key']] ?? null; @endphp
                    @if($currentId && ($currentMedia = collect($mediaItems)->firstWhere('id', (int) $currentId)))
                        <div style="display:flex; align-items:center; gap:0.75rem; padding:0.625rem; border:1.5px solid var(--color-primary-400, #60a5fa); border-radius:0.625rem; margin-bottom:0.5rem; background:#f0f7ff;">
                            <img src="{{ $currentMedia['url'] }}" alt="{{ $currentMedia['name'] }}" style="width:3rem;height:3rem;object-fit:cover;border-radius:0.375rem;flex-shrink:0;">
                            <div style="flex:1;min-width:0;">
                                <p style="font-size:0.8125rem;font-weight:500;color:#111827;margin:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $currentMedia['name'] }}</p>
                            </div>
                            <button type="button"
                                wire:click="$set('blocks.{{ $editingIndex }}.data.{{ $field['key'] }}', null)"
                                style="font-size:0.75rem;color:#9ca3af;background:transparent;border:none;cursor:pointer;"
                            >{{ __('laravix::blocks.actions.remove') }}</button>
                        </div>
                    @endif

                    @if(empty($mediaItems))
                        <p style="font-size:0.8125rem;color:#9ca3af;padding:0.75rem;background:#f9fafb;border-radius:0.5rem;text-align:center;">{{ __('laravix::media.messages.no_media') }}</p>
                    @else
                        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:0.375rem;max-height:14rem;overflow-y:auto;padding:0.25rem;">
                            @foreach($mediaItems as $media)
                                <button
                                    type="button"
                                    class="image-thumb {{ (int)($blocks[$editingIndex]['data'][$field['key']] ?? 0) === $media['id'] ? 'selected' : '' }}"
                                    wire:click="$set('blocks.{{ $editingIndex }}.data.{{ $field['key'] }}', {{ $media['id'] }})"
                                    title="{{ $media['name'] }}"
                                    style="background:transparent;border:2px solid {{ (int)($blocks[$editingIndex]['data'][$field['key']] ?? 0) === $media['id'] ? 'var(--color-primary-600,#2563eb)' : 'transparent' }};padding:0;border-radius:0.5rem;overflow:hidden;aspect-ratio:1;cursor:pointer;"
                                >
                                    <img src="{{ $media['url'] }}" alt="{{ $media['name'] }}" style="width:100%;height:100%;object-fit:cover;display:block;">
                                </button>
                            @endforeach
                        </div>
                    @endif

                @elseif($field['type'] === 'repeater')
                    @php $items = $blocks[$editingIndex]['data'][$field['key']] ?? []; @endphp
                    <div style="display:flex; flex-direction:column; gap:0.5rem;">
                        @forelse($items as $itemIdx => $item)
                            <div style="border:1px solid #e5e7eb; border-radius:0.625rem; padding:0.75rem; position:relative; background:#fafafa;">
                                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.625rem;">
                                    <span style="font-size:0.75rem; font-weight:500; color:#9ca3af;">#{{ $itemIdx + 1 }}</span>
                                    <button
                                        type="button"
                                        wire:click="removeRepeaterItem('{{ $field['key'] }}', {{ $itemIdx }})"
                                        style="font-size:0.75rem; color:#d1d5db; background:transparent; border:none; cursor:pointer; padding:0;"
                                        onmouseenter="this.style.color='#ef4444'"
                                        onmouseleave="this.style.color='#d1d5db'"
                                    >
                                        <svg style="width:0.875rem;height:0.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                                @foreach($field['fields'] as $subField)
                                    <div style="margin-bottom:0.5rem;">
                                        <label style="display:block; font-size:0.7rem; font-weight:500; color:#9ca3af; margin-bottom:0.25rem;">{{ $subField['label'] }}</label>
                                        @if($subField['type'] === 'image')
                                            @php $subCurrentId = $blocks[$editingIndex]['data'][$field['key']][$itemIdx][$subField['key']] ?? null; @endphp
                                            @if($subCurrentId && ($subCurrentMedia = collect($mediaItems)->firstWhere('id', (int) $subCurrentId)))
                                                <div style="display:flex; align-items:center; gap:0.5rem; padding:0.375rem; border:1.5px solid var(--color-primary-400,#60a5fa); border-radius:0.5rem; margin-bottom:0.375rem; background:#f0f7ff;">
                                                    <img src="{{ $subCurrentMedia['url'] }}" alt="{{ $subCurrentMedia['name'] }}" style="width:2.5rem;height:2.5rem;object-fit:cover;border-radius:0.25rem;flex-shrink:0;">
                                                    <span style="flex:1;font-size:0.75rem;color:#111827;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $subCurrentMedia['name'] }}</span>
                                                    <button type="button"
                                                        wire:click="$set('blocks.{{ $editingIndex }}.data.{{ $field['key'] }}.{{ $itemIdx }}.{{ $subField['key'] }}', null)"
                                                        style="font-size:0.7rem;color:#9ca3af;background:transparent;border:none;cursor:pointer;">✕</button>
                                                </div>
                                            @endif
                                            @if(empty($mediaItems))
                                                <p style="font-size:0.75rem;color:#9ca3af;">{{ __('laravix::media.messages.no_media') }}</p>
                                            @else
                                                <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:0.25rem;max-height:8rem;overflow-y:auto;">
                                                    @foreach($mediaItems as $media)
                                                        <button type="button"
                                                            wire:click="$set('blocks.{{ $editingIndex }}.data.{{ $field['key'] }}.{{ $itemIdx }}.{{ $subField['key'] }}', {{ $media['id'] }})"
                                                            title="{{ $media['name'] }}"
                                                            style="background:transparent;border:2px solid {{ (int)($blocks[$editingIndex]['data'][$field['key']][$itemIdx][$subField['key']] ?? 0) === $media['id'] ? 'var(--color-primary-600,#2563eb)' : 'transparent' }};padding:0;border-radius:0.375rem;overflow:hidden;aspect-ratio:1;cursor:pointer;"
                                                        >
                                                            <img src="{{ $media['url'] }}" alt="{{ $media['name'] }}" style="width:100%;height:100%;object-fit:cover;display:block;">
                                                        </button>
                                                    @endforeach
                                                </div>
                                            @endif

                                        @elseif($subField['type'] === 'select')
                                            <select
                                                x-on:change="$wire.updateRepeaterField('{{ $field['key'] }}', {{ $itemIdx }}, '{{ $subField['key'] }}', $event.target.value)"
                                                style="width:100%; padding:0.375rem 0.625rem; border:1px solid #d1d5db; border-radius:0.375rem; font-size:0.8125rem; background:#fff; color:#111827;"
                                            >
                                                <option value="">—</option>
                                                @foreach($subField['options'] as $optVal => $optLabel)
                                                    <option value="{{ $optVal }}" {{ ($blocks[$editingIndex]['data'][$field['key']][$itemIdx][$subField['key']] ?? '') == $optVal ? 'selected' : '' }}>{{ $optLabel }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input
                                                type="text"
                                                value="{{ $blocks[$editingIndex]['data'][$field['key']][$itemIdx][$subField['key']] ?? '' }}"
                                                x-on:blur="$wire.updateRepeaterField('{{ $field['key'] }}', {{ $itemIdx }}, '{{ $subField['key'] }}', $event.target.value)"
                                                style="width:100%; padding:0.375rem 0.625rem; border:1px solid #d1d5db; border-radius:0.375rem; font-size:0.8125rem; color:#111827; box-sizing:border-box;"
                                            >
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @empty
                            <p style="font-size:0.8125rem; color:#9ca3af; padding:0.625rem 0;">{{ __('laravix::blocks.messages.no_items') }}</p>
                        @endforelse
                        <button
                            type="button"
                            wire:click="addRepeaterItem('{{ $field['key'] }}')"
                            style="display:inline-flex; align-items:center; gap:0.375rem; padding:0.375rem 0.75rem; font-size:0.8125rem; font-weight:500; border:1.5px dashed #d1d5db; border-radius:0.5rem; background:transparent; color:#6b7280; cursor:pointer; width:100%; justify-content:center; transition:border-color 0.15s, color 0.15s;"
                            onmouseenter="this.style.borderColor='var(--color-primary-400,#60a5fa)';this.style.color='var(--color-primary-600,#2563eb)'"
                            onmouseleave="this.style.borderColor='#d1d5db';this.style.color='#6b7280'"
                        >
                            <svg style="width:0.875rem;height:0.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            {{ __('laravix::blocks.actions.add') }} {{ $field['label'] }}
                        </button>
                    </div>

                @elseif($field['type'] === 'select')
                    <select
                        wire:model.live="blocks.{{ $editingIndex }}.data.{{ $field['key'] }}"
                        style="width:100%; padding:0.5rem 0.75rem; border:1px solid #d1d5db; border-radius:0.5rem; font-size:0.875rem; background:#fff; color:#111827;"
                    >
                        <option value="">—</option>
                        @foreach($field['options'] as $value => $optLabel)
                            <option value="{{ $value }}">{{ $optLabel }}</option>
                        @endforeach
                    </select>

                @elseif($field['type'] === 'textarea' || $field['type'] === 'richtext')
                    <textarea
                        wire:model.blur="blocks.{{ $editingIndex }}.data.{{ $field['key'] }}"
                        rows="{{ $field['type'] === 'richtext' ? 6 : 3 }}"
                        style="width:100%; padding:0.5rem 0.75rem; border:1px solid #d1d5db; border-radius:0.5rem; font-size:0.875rem; color:#111827; resize:vertical; box-sizing:border-box;"
                    ></textarea>

                @else
                    <input
                        type="text"
                        wire:model.blur="blocks.{{ $editingIndex }}.data.{{ $field['key'] }}"
                        style="width:100%; padding:0.5rem 0.75rem; border:1px solid #d1d5db; border-radius:0.5rem; font-size:0.875rem; color:#111827; box-sizing:border-box;"
                    >
                @endif
            </div>
        @empty
            <p style="font-size:0.875rem; color:#9ca3af;">{{ __('laravix::blocks.messages.no_fields') }}</p>
        @endforelse

        <details style="margin-top:1.5rem;">
            <summary style="font-size:0.75rem; font-weight:500; color:#9ca3af; cursor:pointer; user-select:none; list-style:none; display:flex; align-items:center; gap:0.375rem;">
                <svg style="width:0.875rem;height:0.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                {{ __('laravix::blocks.settings.title') }}
            </summary>
            <div style="margin-top:1rem; display:flex; flex-direction:column; gap:1rem; padding-top:1rem; border-top:1px solid #f3f4f6;">
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:500; color:#6b7280; margin-bottom:0.375rem;">{{ __('laravix::blocks.settings.css_class') }}</label>
                    <input type="text" wire:model.blur="blocks.{{ $editingIndex }}.data.css_class"
                        style="width:100%; padding:0.5rem 0.75rem; border:1px solid #d1d5db; border-radius:0.5rem; font-size:0.875rem; color:#111827; box-sizing:border-box;">
                </div>
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:500; color:#6b7280; margin-bottom:0.375rem;">{{ __('laravix::blocks.settings.padding') }}</label>
                    <select wire:model.live="blocks.{{ $editingIndex }}.data.padding"
                        style="width:100%; padding:0.5rem 0.75rem; border:1px solid #d1d5db; border-radius:0.5rem; font-size:0.875rem; background:#fff; color:#111827;">
                        <option value="">—</option>
                        <option value="none">{{ __('laravix::blocks.padding.none') }}</option>
                        <option value="sm">{{ __('laravix::blocks.padding.small') }}</option>
                        <option value="md">{{ __('laravix::blocks.padding.medium') }}</option>
                        <option value="lg">{{ __('laravix::blocks.padding.large') }}</option>
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:500; color:#6b7280; margin-bottom:0.375rem;">{{ __('laravix::blocks.settings.background_color') }}</label>
                    <input type="color" wire:model.live="blocks.{{ $editingIndex }}.data.background_color"
                        style="width:100%; height:40px; padding:2px 4px; border:1px solid #d1d5db; border-radius:0.5rem; cursor:pointer;">
                </div>
            </div>
        </details>
    @endif
</div>
