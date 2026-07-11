<div class="rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm mb-6">
    <div class="bg-gray-100 dark:bg-gray-800 px-4 py-2.5 flex items-center gap-3 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-1.5">
            <div class="w-3 h-3 rounded-full bg-red-400"></div>
            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
            <div class="w-3 h-3 rounded-full bg-green-400"></div>
        </div>
        <div class="flex-1 bg-white dark:bg-gray-700 rounded-md px-3 py-1 text-xs text-gray-400 dark:text-gray-500 border border-gray-200 dark:border-gray-600">
            {{ $site?->domain ?? 'example.com' }} — {{ $label }}
        </div>
    </div>
    <iframe
        data-nav-preview
        data-nav-part="{{ $part ?? 'header' }}"
        src="{{ route('nav.preview', $previewToken) }}?part={{ $part ?? 'header' }}"
        class="w-full border-0 block"
        style="height: 80px;"
        scrolling="no"
    ></iframe>
</div>