@php
    $record = $getRecord();
    $available = ! ($record->site?->isHeadless() ?? true);
@endphp

<div
    class="lx-page-thumb"
    @if ($available)
        x-data="{
            fit() { $refs.frame.style.transform = 'scale(' + ($el.clientWidth / 1280) + ')' },
        }"
        x-init="fit(); new ResizeObserver(() => fit()).observe($el)"
    @endif
>
    @if ($available)
        <iframe
            x-ref="frame"
            src="{{ route('content.thumbnail', $record) }}"
            title="{{ $record->title }}"
            loading="lazy"
            tabindex="-1"
            aria-hidden="true"
        ></iframe>
    @else
        <div class="lx-page-thumb-empty">
            <x-filament::icon icon="heroicon-o-code-bracket" class="h-6 w-6" />
        </div>
    @endif
</div>
