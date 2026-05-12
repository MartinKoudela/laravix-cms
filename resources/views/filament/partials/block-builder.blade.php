@if(!$record?->id)
    <div style="display:flex; align-items:center; justify-content:center; padding:3rem; color:#9ca3af; font-size:0.875rem;">
        {{ __('content.messages.save_first_for_builder') }}
    </div>
@else
    <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; padding:4rem 1rem; gap:1.5rem; text-align:center;">
        <svg style="width:3rem;height:3rem;color:#d1d5db;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
        </svg>

        @if($record->grapesjs_data)
            <p style="font-size:0.875rem;color:#6b7280;margin:0;">{{ __('content.messages.builder_has_content') }}</p>
        @else
            <p style="font-size:0.875rem;color:#6b7280;margin:0;">{{ __('content.messages.builder_no_content') }}</p>
        @endif

        <a
            href="{{ route('builder.edit', [$record->site_id, $record->id]) }}"
            target="_blank"
            style="display:inline-flex;align-items:center;gap:0.5rem;padding:0.625rem 1.25rem;background:#2563eb;color:#fff;font-size:0.875rem;font-weight:600;border-radius:0.5rem;text-decoration:none;transition:background .15s;"
            onmouseenter="this.style.background='#1d4ed8'"
            onmouseleave="this.style.background='#2563eb'"
        >
            <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            {{ __('content.actions.open_builder') }}
        </a>
    </div>
@endif