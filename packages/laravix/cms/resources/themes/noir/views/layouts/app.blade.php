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
    @if ($themeStylesheet = \Laravix\Cms\Laravix::themeAsset('app.css', $site->theme ?? 'default'))
        <link rel="stylesheet" href="{{ $themeStylesheet }}">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    @include('themes.noir::partials.nav-design')

    @stack('head')
</head>

<body id="top" class="noir">
    <a href="#main" class="noir-skip">{{ __('laravix::theme.skip_to_content') }}</a>

    @include('themes.noir::partials.header')

    @php
        $mainStyle = collect([
            $appearance->get('color') ? 'background-color:' . $appearance->get('color') : null,
            $appearance->get('text_color') ? 'color:' . $appearance->get('text_color') : null,
            $bgMedia ? 'background-image:url(' . $bgMedia->variantUrl(ImageVariant::LARGE) . ');background-size:cover;background-position:center' : null,
        ])->filter()->implode(';');
    @endphp
    <main id="main" class="noir-main {{ $appearance->get('custom_css_class') }}"
          @if($mainStyle) style="{{ $mainStyle }}" @endif>
        @yield('content')
    </main>

    @include('themes.noir::partials.footer')

    <script>
        (function () {
            var header = document.querySelector('[data-noir-header]');
            var toggle = document.querySelector('[data-noir-menu-toggle]');
            var menu = document.getElementById('noir-mobile-menu');

            if (header) {
                var onScroll = function () { header.classList.toggle('is-scrolled', window.scrollY > 8); };
                onScroll();
                window.addEventListener('scroll', onScroll, { passive: true });
            }

            if (toggle && menu) {
                var setOpen = function (open) {
                    menu.hidden = !open;
                    toggle.setAttribute('aria-expanded', String(open));
                    toggle.setAttribute('aria-label', open ? toggle.dataset.labelClose : toggle.dataset.labelOpen);
                    document.body.classList.toggle('noir-menu-open', open);
                };
                toggle.addEventListener('click', function () { setOpen(menu.hidden); });
                document.addEventListener('keydown', function (event) { if (event.key === 'Escape') { setOpen(false); } });
                window.matchMedia('(min-width: 768px)').addEventListener('change', function () { setOpen(false); });
            }
        })();
    </script>
    @stack('scripts')
</body>
</html>
