<!DOCTYPE html>
<html lang="{{ $currentLocale ?? $settings->get('locale', 'en') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @php use Laravix\Cms\Enums\ImageVariant; @endphp
    @if($faviconMedia)
        <link rel="icon" href="{{ $faviconMedia->variantUrl(ImageVariant::FAVICON) }}">
    @endif

    <title>{{ $seo['title'] }} – {{ $settings->get('site_name', $site->name) }}</title>

    @if($seo['description'])
        <meta name="description" content="{{ $seo['description'] }}">
    @endif
    @if($seo['noindex'])
        <meta name="robots" content="noindex, nofollow">
    @endif
    <link rel="canonical" href="{{ $seo['canonical'] }}">
    @if(($alternates ?? collect())->count() > 1)
        @foreach ($alternates as $altLocale => $altUrl)
            <link rel="alternate" hreflang="{{ $altLocale }}" href="{{ $altUrl }}">
        @endforeach
        <link rel="alternate" hreflang="x-default" href="{{ $alternates->get($defaultLocale, $seo['canonical']) }}">
    @endif

    <meta property="og:title" content="{{ $seo['title'] }}">
    <meta property="og:type" content="{{ $content->type === 'post' ? 'article' : 'website' }}">
    <meta property="og:url" content="{{ $seo['canonical'] }}">
    <meta property="og:site_name" content="{{ $settings->get('site_name', $site->name) }}">
    @if($seo['description'])
        <meta property="og:description" content="{{ $seo['description'] }}">
    @endif
    @if($seo['og_image_url'])
        <meta property="og:image" content="{{ $seo['og_image_url'] }}">
    @endif
    <meta name="twitter:card" content="{{ $seo['og_image_url'] ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $seo['title'] }}">
    @if($seo['description'])
        <meta name="twitter:description" content="{{ $seo['description'] }}">
    @endif
    @if($seo['og_image_url'])
        <meta name="twitter:image" content="{{ $seo['og_image_url'] }}">
    @endif
    @if($settings->get('twitter_url'))
        <meta name="twitter:site" content="{{ $settings->get('twitter_url') }}">
    @endif
    @if($settings->get('google_site_verification'))
        <meta name="google-site-verification" content="{{ $settings->get('google_site_verification') }}">
    @endif

    @php
        $sameAs = array_values(array_filter([
            $settings->get('twitter_url'),
            $settings->get('linkedin_url'),
            $settings->get('facebook_url'),
            $settings->get('instagram_url'),
            $settings->get('github_url'),
        ]));
        $jsonLd = [
            '@context' => 'https://schema.org',
            '@graph' => [
                array_filter(['@type' => 'Organization', 'name' => $settings->get('site_name', $site->name), 'url' => url('/'), 'sameAs' => $sameAs ?: null]),
                array_filter(['@type' => $content->type === 'post' ? 'Article' : 'WebPage', 'headline' => $seo['title'], 'url' => $seo['canonical'], 'description' => $seo['description'] ?: null, 'image' => $seo['og_image_url'] ?: null, 'datePublished' => $content->published_at?->toIso8601String(), 'dateModified' => $content->updated_at->toIso8601String()]),
            ],
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>

    <link rel="stylesheet" href="{{ \Laravix\Cms\Laravix::asset('app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    @php
        $hd = collect($navDesign['header'] ?? []);
        $fd = collect($navDesign['footer'] ?? []);

        $googleFontMap = [
            'Inter'              => 'Inter:wght@300;400;500;600;700',
            'Roboto'             => 'Roboto:wght@300;400;500;700',
            'Open Sans'          => 'Open+Sans:wght@300;400;500;600;700',
            'Lato'               => 'Lato:wght@300;400;700',
            'Montserrat'         => 'Montserrat:wght@300;400;500;600;700',
            'Poppins'            => 'Poppins:wght@300;400;500;600;700',
            'Nunito'             => 'Nunito:wght@300;400;500;600;700',
            'Raleway'            => 'Raleway:wght@300;400;500;600;700',
            'Ubuntu'             => 'Ubuntu:wght@300;400;500;700',
            'Rubik'              => 'Rubik:wght@300;400;500;600;700',
            'Work Sans'          => 'Work+Sans:wght@300;400;500;600;700',
            'DM Sans'            => 'DM+Sans:wght@300;400;500;600;700',
            'Noto Sans'          => 'Noto+Sans:wght@300;400;500;600;700',
            'Source Sans 3'      => 'Source+Sans+3:wght@300;400;500;600;700',
            'Manrope'            => 'Manrope:wght@300;400;500;600;700',
            'Outfit'             => 'Outfit:wght@300;400;500;600;700',
            'Plus Jakarta Sans'  => 'Plus+Jakarta+Sans:wght@300;400;500;600;700',
            'Playfair Display'   => 'Playfair+Display:wght@400;500;600;700',
            'Merriweather'       => 'Merriweather:wght@300;400;700',
            'Lora'               => 'Lora:wght@400;500;600;700',
            'PT Serif'           => 'PT+Serif:wght@400;700',
            'Libre Baskerville'  => 'Libre+Baskerville:wght@400;700',
            'EB Garamond'        => 'EB+Garamond:wght@400;500;600;700',
            'Cormorant Garamond' => 'Cormorant+Garamond:wght@300;400;500;600;700',
            'Crimson Text'       => 'Crimson+Text:wght@400;600;700',
        ];

        $fontsToLoad = collect([$hd->get('font_family'), $fd->get('font_family')])
            ->filter()
            ->flatMap(fn ($f) => collect($googleFontMap)->filter(fn ($_, $n) => str_starts_with($f, $n))->values())
            ->unique();

        $hIsSticky       = $hd->get('sticky') === null ? true : (bool) $hd->get('sticky');
        $hHeight         = (int) ($hd->get('height') ?: 64);
        $hLogoHeight     = (int) ($hd->get('logo_height') ?: 32);
        $hLinksGap       = (int) ($hd->get('links_gap') ?: 24);
        $hLinksAlign     = $hd->get('links_align', 'flex-end');
        $hBg             = $hd->get('bg_color', '#ffffff');
        $hText           = $hd->get('text_color', '#4b5563');
        $hHover          = $hd->get('hover_color');
        $hActive         = $hd->get('active_color');
        $hBorderColor    = $hd->get('border_color', '#e5e7eb');
        $hBorderWidth    = $hd->get('border_width', '1px');
        $hDropdownBg     = $hd->get('dropdown_bg', '#ffffff');
        $hDropdownText   = $hd->get('dropdown_text', '#374151');
        $hDropdownHoverBg = $hd->get('dropdown_hover_bg', '#f9fafb');
        $hFont           = $hd->get('font_family');
        $hSize           = $hd->get('font_size');
        $hWeight         = $hd->get('font_weight');

        // Background opacity → rgba conversion
        $hBgOpacity  = max(0, min(100, (int) ($hd->get('bg_opacity', 100))));
        $hBgAlpha    = round($hBgOpacity / 100, 2);
        $hBgHex      = ltrim($hBg, '#');
        if (strlen($hBgHex) === 3) {
            $hBgHex = $hBgHex[0].$hBgHex[0].$hBgHex[1].$hBgHex[1].$hBgHex[2].$hBgHex[2];
        }
        $hBgRgba = sprintf(
            'rgba(%d,%d,%d,%s)',
            hexdec(substr($hBgHex, 0, 2)),
            hexdec(substr($hBgHex, 2, 2)),
            hexdec(substr($hBgHex, 4, 2)),
            $hBgAlpha
        );
        $hIsTranslucent = $hBgAlpha < 1.0;

        $shadowMap = [
            'shadow_sm' => '0 1px 3px 0 rgba(0,0,0,.1),0 1px 2px -1px rgba(0,0,0,.1)',
            'shadow_md' => '0 4px 6px -1px rgba(0,0,0,.1),0 2px 4px -2px rgba(0,0,0,.1)',
            'shadow_lg' => '0 10px 15px -3px rgba(0,0,0,.1),0 4px 6px -4px rgba(0,0,0,.1)',
        ];
        $hShadow = $shadowMap[$hd->get('shadow', '')] ?? null;

        $hHeaderStyle = collect([
            'background-color:' . $hBgRgba,
            $hIsTranslucent ? 'backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px)' : null,
            $hBorderWidth !== '0px' ? 'border-bottom:' . $hBorderWidth . ' solid ' . $hBorderColor : 'border-bottom:none',
            $hShadow ? 'box-shadow:' . $hShadow : null,
            $hFont ? 'font-family:' . $hFont : null,
        ])->filter()->implode(';');

        $hLinkFontStyle = collect([
            $hFont   ? 'font-family:' . $hFont   : null,
            $hSize   ? 'font-size:' . $hSize . 'px'  : null,
            $hWeight ? 'font-weight:' . $hWeight : null,
        ])->filter()->implode(';');

        $fIsTransparent  = false;
        $fBg             = $fd->get('bg_color', '#f9fafb');
        $fText           = $fd->get('text_color', '#6b7280');
        $fHover          = $fd->get('hover_color');
        $fBorderColor    = $fd->get('border_color', '#e5e7eb');
        $fFont           = $fd->get('font_family');
        $fSize           = $fd->get('font_size');
        $fWeight         = $fd->get('font_weight');
        $fPaddingY       = (int) ($fd->get('padding_y') ?: 32);
        $fLayout         = $fd->get('layout', 'row');
        $fCopyright      = $fd->get('copyright_text');
        $fShowCopyright  = $fd->get('show_copyright') === null ? true : (bool) $fd->get('show_copyright');

        $fFooterStyle = collect([
            'background-color:' . $fBg,
            'border-top:1px solid ' . $fBorderColor,
            $fFont ? 'font-family:' . $fFont : null,
        ])->filter()->implode(';');

        $fLinkFontStyle = collect([
            $fFont   ? 'font-family:' . $fFont   : null,
            $fSize   ? 'font-size:' . $fSize . 'px'  : null,
            $fWeight ? 'font-weight:' . $fWeight : null,
        ])->filter()->implode(';');

        $hIconPos = $hd->get('icon_position', '');
        $fIconPos = $fd->get('icon_position', '');

        $currentPath = request()->getPathInfo();
        $navUrl = fn ($u) => ($u && ! preg_match('~^(https?://|/|mailto:|tel:)~', $u)) ? 'https://'.$u : ($u ?? '');
    @endphp

    @if($fontsToLoad->isNotEmpty())
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family={{ $fontsToLoad->implode('&family=') }}&display=swap" rel="stylesheet">
    @endif

    <style>
        @if($hHover)header .nav-link:hover { color: {{ $hHover }} !important; }@endif
        @if($fHover)footer .nav-link:hover { color: {{ $fHover }} !important; }@endif
        @if($hDropdownHoverBg !== '#f9fafb')header .nav-dropdown-item:hover { background-color: {{ $hDropdownHoverBg }} !important; }@endif
    </style>

    @stack('head')
</head>

<body class="bg-white text-gray-900 antialiased flex flex-col min-h-screen">

    <header
        class="{{ $hIsSticky ? 'sticky top-0 z-[999]' : '' }}"
        style="{{ $hHeaderStyle }}"
    >
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            @php
                $isCentered = $hLinksAlign === 'center';
                $isLeft     = $hLinksAlign === 'flex-start';
            @endphp
            <div class="flex items-center {{ $isCentered || $isLeft ? '' : 'justify-between' }} relative" style="height:{{ $hHeight }}px">

                <a href="/" class="flex items-center hover:opacity-80 transition-opacity shrink-0">
                    @if($logoMedia)
                        <img src="{{ $logoMedia->variantUrl(ImageVariant::FULL) }}"
                             alt="{{ $settings->get('site_name', $site->name) }}"
                             style="height:{{ $hLogoHeight }}px;width:auto">
                    @else
                        <span class="text-xl font-bold" style="color:{{ $hText }}">
                            {{ $settings->get('site_name', $site->name) }}
                        </span>
                    @endif
                </a>

                <nav class="hidden md:flex items-center {{ $isCentered ? 'absolute left-1/2 -translate-x-1/2' : ($isLeft ? 'ml-8' : '') }}"
                     style="gap:{{ $hLinksGap }}px">
                    @foreach ($navigations['header'] ?? [] as $item)
                        @php
                            $urlPath  = parse_url($item['url'] ?? '', PHP_URL_PATH) ?? '';
                            $isActive = $urlPath && ($currentPath === $urlPath || ($urlPath !== '/' && str_starts_with($currentPath, rtrim($urlPath, '/'))));
                            $linkColor = ($isActive && $hActive) ? $hActive : $hText;
                            $hIconSvg = ($hIconPos && ! empty($item['icon'])) ? \Laravix\Cms\Support\NavigationIconRegistry::renderSvg($item['icon']) : '';
                            $linkStyle = collect([$hLinkFontStyle, 'color:' . $linkColor, $hIconSvg ? 'display:inline-flex;align-items:center;gap:5px' : null])->filter()->implode(';');
                            $hLinkText = $hIconPos === 'only' && $hIconSvg ? '' : e($item['label']);
                            $hLinkHtml = match($hIconPos) {
                                'before' => $hIconSvg . '<span>' . $hLinkText . '</span>',
                                'after'  => '<span>' . $hLinkText . '</span>' . $hIconSvg,
                                'only'   => $hIconSvg ?: '<span>' . $hLinkText . '</span>',
                                default  => '<span>' . $hLinkText . '</span>',
                            };
                        @endphp
                        @if (!empty($item['children']))
                            <div class="relative group">
                                <a href="{{ $navUrl($item['url']) }}" target="{{ $item['target'] ?? '_self' }}"
                                   class="nav-link font-medium transition-colors"
                                   style="{{ $linkStyle }}">
                                    {!! $hLinkHtml !!}
                                </a>
                                <div class="absolute left-0 top-full mt-1 w-52 rounded-md shadow-lg hidden group-hover:block z-50 overflow-hidden"
                                     style="background-color:{{ $hDropdownBg }};border:1px solid {{ $hBorderColor }}">
                                    @foreach ($item['children'] as $child)
                                        @php
                                            $cIconSvg = ($hIconPos && ! empty($child['icon'])) ? \Laravix\Cms\Support\NavigationIconRegistry::renderSvg($child['icon']) : '';
                                            $cLinkText = $hIconPos === 'only' && $cIconSvg ? '' : e($child['label']);
                                            $cLinkHtml = match($hIconPos) {
                                                'before' => $cIconSvg . '<span>' . $cLinkText . '</span>',
                                                'after'  => '<span>' . $cLinkText . '</span>' . $cIconSvg,
                                                'only'   => $cIconSvg ?: '<span>' . $cLinkText . '</span>',
                                                default  => '<span>' . $cLinkText . '</span>',
                                            };
                                        @endphp
                                        <a href="{{ $navUrl($child['url']) }}" target="{{ $child['target'] ?? '_self' }}"
                                           class="nav-dropdown-item flex items-center gap-2 px-4 py-2 text-sm transition-colors"
                                           style="color:{{ $hDropdownText }}">
                                            {!! $cLinkHtml !!}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{ $navUrl($item['url']) }}" target="{{ $item['target'] ?? '_self' }}"
                               class="nav-link font-medium transition-colors"
                               style="{{ $linkStyle }}">
                                {!! $hLinkHtml !!}
                            </a>
                        @endif
                    @endforeach
                </nav>

                <button id="mobile-menu-toggle"
                        class="md:hidden p-2 rounded-md transition-colors {{ $isCentered || $isLeft ? 'ml-auto' : '' }}"
                        style="color:{{ $hText }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <nav id="mobile-menu" class="hidden md:hidden pb-4 flex flex-col gap-2">
                @foreach ($navigations['header'] ?? [] as $item)
                    @php
                        $urlPath  = parse_url($item['url'] ?? '', PHP_URL_PATH) ?? '';
                        $isActive = $urlPath && ($currentPath === $urlPath || ($urlPath !== '/' && str_starts_with($currentPath, rtrim($urlPath, '/'))));
                        $linkColor = ($isActive && $hActive) ? $hActive : $hText;
                        $mIconSvg = ($hIconPos && ! empty($item['icon'])) ? \Laravix\Cms\Support\NavigationIconRegistry::renderSvg($item['icon']) : '';
                        $mLinkText = $hIconPos === 'only' && $mIconSvg ? '' : e($item['label']);
                        $mLinkHtml = match($hIconPos) {
                            'before' => $mIconSvg . '<span>' . $mLinkText . '</span>',
                            'after'  => '<span>' . $mLinkText . '</span>' . $mIconSvg,
                            'only'   => $mIconSvg ?: '<span>' . $mLinkText . '</span>',
                            default  => '<span>' . $mLinkText . '</span>',
                        };
                    @endphp
                    <a href="{{ $navUrl($item['url']) }}" target="{{ $item['target'] ?? '_self' }}"
                       class="nav-link flex items-center gap-2 py-1.5 border-b border-gray-100"
                       style="{{ collect([$hLinkFontStyle, 'color:' . $linkColor])->filter()->implode(';') }}">
                        {!! $mLinkHtml !!}
                    </a>
                    @foreach ($item['children'] ?? [] as $child)
                        <a href="{{ $navUrl($child['url']) }}" target="{{ $child['target'] ?? '_self' }}"
                           class="nav-link flex items-center gap-2 py-1 pl-4 text-sm"
                           style="{{ collect([$hLinkFontStyle, 'color:' . $hText, 'opacity:.75'])->filter()->implode(';') }}">
                            {{ $child['label'] }}
                        </a>
                    @endforeach
                @endforeach
            </nav>
        </div>
    </header>

    @php
        $mainStyle = collect([
            $appearance->get('color') ? 'background-color:' . $appearance->get('color') : null,
            $appearance->get('text_color') ? 'color:' . $appearance->get('text_color') : null,
            $bgMedia ? 'background-image:url(' . $bgMedia->variantUrl(ImageVariant::LARGE) . ');background-size:cover;background-position:center' : null,
        ])->filter()->implode(';');
    @endphp
    <main class="flex-1 {{ $appearance->get('custom_css_class') }}"
          @if($mainStyle) style="{{ $mainStyle }}" @endif>
        @yield('content')
    </main>

    <footer class="mt-auto" style="{{ $fFooterStyle }}">
        <div class="max-w-6xl mx-auto px-4 sm:px-6"
             style="padding-top:{{ $fPaddingY }}px;padding-bottom:{{ $fPaddingY }}px">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">

                @if($fShowCopyright)
                <p class="text-sm" style="color:{{ $fText }}{{ $fSize ? ';font-size:' . $fSize . 'px' : '' }}">
                    &copy; {{ date('Y') }} {{ $fCopyright ?: $site->name }}
                </p>
                @endif

                <nav class="{{ $fLayout === 'stacked' ? 'flex flex-col items-center gap-2' : 'flex flex-wrap justify-center gap-5' }}">
                    @foreach ($navigations['footer'] ?? [] as $item)
                        @php
                            $fIconSvg = ($fIconPos && ! empty($item['icon'])) ? \Laravix\Cms\Support\NavigationIconRegistry::renderSvg($item['icon']) : '';
                            $fLinkText = $fIconPos === 'only' && $fIconSvg ? '' : e($item['label']);
                            $fLinkHtml = match($fIconPos) {
                                'before' => $fIconSvg . '<span>' . $fLinkText . '</span>',
                                'after'  => '<span>' . $fLinkText . '</span>' . $fIconSvg,
                                'only'   => $fIconSvg ?: '<span>' . $fLinkText . '</span>',
                                default  => '<span>' . $fLinkText . '</span>',
                            };
                        @endphp
                        <a href="{{ $navUrl($item['url']) }}" target="{{ $item['target'] ?? '_self' }}"
                           class="nav-link flex items-center gap-2 transition-colors"
                           style="{{ collect([$fLinkFontStyle, 'color:' . $fText])->filter()->implode(';') }}">
                            {!! $fLinkHtml !!}
                        </a>
                    @endforeach
                </nav>

            </div>
        </div>
    </footer>

    <script>
        document.getElementById('mobile-menu-toggle')?.addEventListener('click', function () {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
    @stack('scripts')
</body>
</html>
