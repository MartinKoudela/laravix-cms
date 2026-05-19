<x-filament-panels::page>

    <div x-data="{ tab: 'items' }">

        <div class="flex gap-1 mb-6 border-b border-gray-200 dark:border-gray-700">
            <button
                type="button"
                @click="tab = 'items'"
                :class="tab === 'items'
                    ? 'border-b-2 border-primary-500 text-primary-600 dark:text-primary-400'
                    : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'"
                class="px-4 py-2.5 text-sm font-medium transition-colors -mb-px"
            >
                {{ __('navigation.tabs.items') }}
            </button>
            <button
                type="button"
                @click="tab = 'design'"
                :class="tab === 'design'
                    ? 'border-b-2 border-primary-500 text-primary-600 dark:text-primary-400'
                    : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'"
                class="px-4 py-2.5 text-sm font-medium transition-colors -mb-px"
            >
                {{ __('navigation.tabs.design') }}
            </button>
        </div>

        <div x-show="tab === 'items'" x-cloak>

            @include('filament.resources.navigation.partials.preview-frame', [
                'label' => __('navigation.labels.header'),
                'part'  => 'header',
            ])

            {{ $this->headerForm }}

            <div class="mt-8">
                @include('filament.resources.navigation.partials.preview-frame', [
                    'label' => __('navigation.labels.footer'),
                    'part'  => 'footer',
                ])
            </div>

            {{ $this->footerForm }}

        </div>

        <div x-show="tab === 'design'" x-cloak>

            @include('filament.resources.navigation.partials.preview-frame', [
                'label' => __('navigation.labels.header'),
                'part'  => 'header',
            ])

            {{ $this->headerDesignForm }}

            <div class="mt-8">
                @include('filament.resources.navigation.partials.preview-frame', [
                    'label' => __('navigation.labels.footer'),
                    'part'  => 'footer',
                ])
            </div>

            {{ $this->footerDesignForm }}

        </div>

    </div>

    <script>
        let previewTimer = null;

        function refreshAllPreviews() {
            document.querySelectorAll('[data-nav-preview]').forEach(function (iframe) {
                var src = iframe.getAttribute('src');
                iframe.setAttribute('src', src);
            });
        }

        window.addEventListener('message', function (event) {
            if (!event.data || event.data.type !== 'nav-preview-height') { return; }
            document.querySelectorAll('[data-nav-preview]').forEach(function (iframe) {
                if (iframe.contentWindow === event.source) {
                    iframe.style.height = event.data.height + 'px';
                }
            });
        });

        document.addEventListener('livewire:initialized', function () {
            Livewire.on('nav-preview-updated', function () {
                clearTimeout(previewTimer);
                previewTimer = setTimeout(refreshAllPreviews, 300);
            });
        });
    </script>

</x-filament-panels::page>
