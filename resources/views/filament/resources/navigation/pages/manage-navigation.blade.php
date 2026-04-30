<x-filament-panels::page>

    <div class="rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm mb-6">
        <div class="bg-gray-100 dark:bg-gray-800 px-4 py-2.5 flex items-center gap-3 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-1.5">
                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                <div class="w-3 h-3 rounded-full bg-green-400"></div>
            </div>
            <div class="flex-1 bg-white dark:bg-gray-700 rounded-md px-3 py-1 text-xs text-gray-400 dark:text-gray-500 border border-gray-200 dark:border-gray-600">
                {{ $site?->domain ?? 'example.com' }} — {{ __('Header Navigation') }}
            </div>
        </div>
        <iframe
            id="nav-preview-header-iframe"
            src="{{ route('nav.preview', $previewToken) }}"
            class="w-full border-0 block"
            style="height: 65px;"
        ></iframe>
    </div>

    {{ $this->headerForm }}

    <div class="rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm mb-6 mt-6">
        <div class="bg-gray-100 dark:bg-gray-800 px-4 py-2.5 flex items-center gap-3 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-1.5">
                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                <div class="w-3 h-3 rounded-full bg-green-400"></div>
            </div>
            <div class="flex-1 bg-white dark:bg-gray-700 rounded-md px-3 py-1 text-xs text-gray-400 dark:text-gray-500 border border-gray-200 dark:border-gray-600">
                {{ $site?->domain ?? 'example.com' }} — {{ __('Footer Navigation') }}
            </div>
        </div>
        <iframe
            id="nav-preview-footer-iframe"
            src="{{ route('nav.preview', $previewToken) }}"
            class="w-full border-0 block"
            style="height: 80px;"
        ></iframe>
    </div>

    {{ $this->footerForm }}

    <script>
        let previewTimer = null;

        function scrollFooterIframe() {
            const footer = document.getElementById('nav-preview-footer-iframe');
            if (footer && footer.contentWindow) {
                try {
                    footer.contentWindow.scrollTo(0, footer.contentWindow.document.body.scrollHeight);
                } catch (e) {}
            }
        }

        document.getElementById('nav-preview-footer-iframe')?.addEventListener('load', scrollFooterIframe);

        document.addEventListener('livewire:initialized', function () {
            Livewire.on('nav-preview-updated', function () {
                clearTimeout(previewTimer);
                previewTimer = setTimeout(function () {
                    const header = document.getElementById('nav-preview-header-iframe');
                    const footer = document.getElementById('nav-preview-footer-iframe');
                    if (header) header.src = header.src;
                    if (footer) footer.src = footer.src;
                }, 300);
            });
        });
    </script>

</x-filament-panels::page>
