@if($record?->id)
    @php $builderUrl = route('builder.edit', [$record->site_id, $record->id]); @endphp
    <div
        x-data
        x-init="
            const panel = $el.closest('.fi-sc-tabs-tab');
            if (!panel) return;
            const url = @js($builderUrl);
            new MutationObserver(function(_, obs) {
                if (panel.classList.contains('fi-active')) {
                    obs.disconnect();
                    window.location.href = url;
                }
            }).observe(panel, { attributes: true, attributeFilter: ['class'] });
        "
    ></div>
@endif