@php
    use Laravix\Cms\Enums\ImageVariant;
    use Laravix\Cms\Support\NavigationIconRegistry;

    $headerDesign = collect($navDesign['header'] ?? []);
    $isSticky = ($preview ?? false) ? false : ($headerDesign->get('sticky') === null || (bool) $headerDesign->get('sticky'));
    $alignment = match ($headerDesign->get('links_align')) {
        'center' => 'center',
        'flex-start' => 'start',
        default => 'end',
    };
    $iconPosition = $headerDesign->get('icon_position') ?: '';
    $siteName = $settings->get('site_name', $site->name);
    $currentPath = ($preview ?? false) ? '/' : request()->getPathInfo();

    $href = fn (?string $url) => ($url && ! preg_match('~^(https?://|/|mailto:|tel:|#)~', $url)) ? 'https://'.$url : ($url ?? '');

    $isActive = function (array $item) use ($currentPath): bool {
        $path = parse_url($item['url'] ?? '', PHP_URL_PATH) ?? '';

        return $path !== '' && ($currentPath === $path || ($path !== '/' && str_starts_with($currentPath, rtrim($path, '/'))));
    };

    $label = function (array $item) use ($iconPosition): string {
        $icon = ($iconPosition && ! empty($item['icon'])) ? NavigationIconRegistry::renderSvg($item['icon']) : '';
        $text = '<span>'.e($item['label']).'</span>';

        return match ($iconPosition) {
            'before' => $icon.$text,
            'after' => $text.$icon,
            'only' => $icon ?: $text,
            default => $text,
        };
    };
@endphp

<header class="noir-header noir-header--{{ $alignment }} {{ $isSticky ? 'is-sticky' : '' }}" data-noir-header>
    <div class="noir-shell noir-header__bar">
        <a href="/" class="noir-header__brand" aria-label="{{ $siteName }}">
            @if ($logoMedia)
                <img src="{{ $logoMedia->variantUrl(ImageVariant::FULL) }}" alt="{{ $siteName }}" class="noir-header__logo">
            @else
                <span class="noir-header__wordmark">{{ $siteName }}</span><span class="noir-header__dot" aria-hidden="true"></span>
            @endif
        </a>

        <nav class="noir-header__nav" aria-label="{{ $siteName }}">
            @foreach ($navigations['header'] ?? [] as $item)
                @if (! empty($item['children']))
                    <div class="noir-header__item has-children">
                        <a href="{{ $href($item['url']) }}" target="{{ $item['target'] ?? '_self' }}"
                           class="noir-header__link nav-link {{ $isActive($item) ? 'is-active' : '' }}"
                           @if ($isActive($item)) aria-current="page" @endif>
                            {!! $label($item) !!}
                            <svg class="noir-header__chevron" viewBox="0 0 12 12" aria-hidden="true"><path d="M3 4.5 6 7.5 9 4.5" fill="none" stroke="currentColor" stroke-width="1.5"/></svg>
                        </a>
                        <div class="noir-header__dropdown">
                            @foreach ($item['children'] as $child)
                                <a href="{{ $href($child['url']) }}" target="{{ $child['target'] ?? '_self' }}"
                                   class="noir-header__dropdown-link nav-dropdown-item">
                                    {!! $label($child) !!}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ $href($item['url']) }}" target="{{ $item['target'] ?? '_self' }}"
                       class="noir-header__link nav-link {{ $isActive($item) ? 'is-active' : '' }}"
                       @if ($isActive($item)) aria-current="page" @endif>
                        {!! $label($item) !!}
                    </a>
                @endif
            @endforeach
        </nav>

        @if (! empty($navigations['header']))
            <button type="button" class="noir-header__toggle" data-noir-menu-toggle
                    aria-controls="noir-mobile-menu" aria-expanded="false"
                    aria-label="{{ __('laravix::theme.menu') }}"
                    data-label-open="{{ __('laravix::theme.menu') }}" data-label-close="{{ __('laravix::theme.close_menu') }}">
                <span class="noir-header__toggle-line"></span>
                <span class="noir-header__toggle-line"></span>
            </button>
        @endif
    </div>

    @if (! empty($navigations['header']) && ! ($preview ?? false))
        <nav id="noir-mobile-menu" class="noir-mobile" hidden>
            <div class="noir-shell">
                @foreach ($navigations['header'] as $index => $item)
                    <a href="{{ $href($item['url']) }}" target="{{ $item['target'] ?? '_self' }}"
                       class="noir-mobile__link {{ $isActive($item) ? 'is-active' : '' }}"
                       style="--noir-index: {{ $index }}">
                        <span class="noir-mobile__index">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        {{ $item['label'] }}
                    </a>
                    @foreach ($item['children'] ?? [] as $child)
                        <a href="{{ $href($child['url']) }}" target="{{ $child['target'] ?? '_self' }}" class="noir-mobile__sublink">
                            {{ $child['label'] }}
                        </a>
                    @endforeach
                @endforeach
            </div>
        </nav>
    @endif
</header>
