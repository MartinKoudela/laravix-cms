@php
    $tenant = filament()->getTenant();

    if (! $tenant) {
        return;
    }

    $actions = \App\Support\FastActions::all($tenant->id);
@endphp

@if (count($actions))
    <div class="pointer-events-none absolute inset-x-0 top-0 hidden h-full items-center justify-center md:flex">
        <div class="pointer-events-auto flex items-center gap-1 rounded-xl border border-black/[0.06] bg-black/[0.025] px-2 py-2 dark:border-white/[0.06] dark:bg-white/[0.03]">
            @foreach ($actions as $key => $action)
                <a
                    href="{{ $action['url'] }}"
                    x-tooltip.html="@js($action['label'])"
                    class="group flex h-8 w-8 items-center justify-center rounded-lg transition-all duration-150 hover:bg-[#ff0465]/10 dark:hover:bg-[#ff0465]/[0.12]"
                >
                    <x-filament::icon
                        :icon="$action['icon']"
                        class="h-[18px] w-[18px] text-black/30 transition-all duration-150 group-hover:text-[#ff0465] dark:text-white/30 dark:group-hover:text-[#ff6b9d]"
                    />
                </a>
            @endforeach
        </div>
    </div>
@endif