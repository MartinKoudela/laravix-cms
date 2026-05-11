@if(!$contentId)
    <div style="display:flex; align-items:center; justify-content:center; padding:3rem; color:#9ca3af; font-size:0.875rem;">
        {{ __('content.messages.save_first_for_builder') }}
    </div>
@else
<div id="block-builder-wrap" style="display:flex; height:calc(100vh - 200px); gap:0; overflow:hidden; width:100%;">
    <div id="block-editor-pane" style="width:40%; overflow-y:auto; padding:1rem;">
        @livewire('block-editor', ['contentId' => $contentId, 'previewToken' => $previewToken])
    </div>
    <div id="block-preview-pane" style="width:60%; position:relative; border-left:1px solid #e5e7eb;">
        <iframe
            id="block-preview-iframe"
            src="{{ $previewToken ? route('block.preview', $previewToken) : 'about:blank' }}"
            style="width:100%; height:100%; border:0;"
        ></iframe>
    </div>
</div>

<script>
    (function () {
        let timer = null;
        document.addEventListener('livewire:initialized', function () {
            Livewire.on('block-preview-updated', function () {
                clearTimeout(timer);
                timer = setTimeout(function () {
                    const iframe = document.getElementById('block-preview-iframe');
                    if (iframe) iframe.src = iframe.src;
                }, 500);
            });
        });
    })();
</script>
@endif
