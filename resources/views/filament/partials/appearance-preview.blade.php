@if(empty($token))
@else
<div class="rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm mb-2">
    <div class="bg-gray-100 dark:bg-gray-800 px-4 py-2.5 flex items-center gap-3 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-1.5">
            <div class="w-3 h-3 rounded-full bg-red-400"></div>
            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
            <div class="w-3 h-3 rounded-full bg-green-400"></div>
        </div>
        <div class="flex-1 bg-white dark:bg-gray-700 rounded-md px-3 py-1 text-xs text-gray-400 dark:text-gray-500 border border-gray-200 dark:border-gray-600">
            {{ __('Appearance') }} — {{ __('Preview') }}
        </div>
    </div>
    <iframe
        id="appearance-preview-iframe"
        src="{{ route('appearance.preview', $token) }}"
        class="w-full border-0 block"
        style="height: 300px;"
    ></iframe>
</div>

<script>
    (function () {
        let timer = null;

        document.addEventListener('livewire:initialized', function () {
            Livewire.on('appearance-preview-updated', function () {
                clearTimeout(timer);
                timer = setTimeout(function () {
                    const iframe = document.getElementById('appearance-preview-iframe');
                    if (iframe) iframe.contentWindow.location.reload();
                }, 300);
            });
        });
    })();
</script>
@endif
