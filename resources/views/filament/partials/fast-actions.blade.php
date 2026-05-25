@php
    $tenant = filament()->getTenant();
    $user = auth()->user();

    if (! $tenant) {
        return;
    }

    $all = \App\Support\FastActions::all($tenant->id);
    $selected = $user?->fast_actions;
    $actions = $selected !== null
        ? array_intersect_key($all, array_flip($selected))
        : $all;
@endphp

@if (count($actions))
    <div class="pointer-events-none absolute inset-x-0 top-0 flex h-full items-center justify-center">
        <div class="pointer-events-auto flex items-center gap-0.5">
            @foreach ($actions as $key => $action)
                <a
                    href="{{ $action['url'] }}"
                    x-tooltip.html="@js($action['label'])"
                    class="group flex h-9 w-9 items-center justify-center rounded-lg text-gray-400 transition duration-150 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-500 dark:hover:bg-white/5 dark:hover:text-gray-200"
                >
                    <x-filament::icon :icon="$action['icon']" class="h-5 w-5 transition duration-150 group-hover:scale-110" />
                </a>
            @endforeach
        </div>
    </div>
@endif