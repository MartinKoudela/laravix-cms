<!DOCTYPE html>
<html lang="{{ $settings->get('locale', 'en') }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @php use App\Enums\ImageVariant; @endphp
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

    {{-- Open Graph --}}
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

    {{-- Twitter Card --}}
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

    {{-- Search Console verification --}}
    @if($settings->get('google_site_verification'))
        <meta name="google-site-verification" content="{{ $settings->get('google_site_verification') }}">
    @endif

    {{-- JSON-LD --}}
    @php
        $sameAs = array_values(array_filter([
            $settings->get('twitter_url'),
            $settings->get('linkedin_url'),
            $settings->get('facebook_url'),
            $settings->get('instagram_url'),
            $settings->get('github_url'),
        ]));

        $organization = array_filter([
            '@type' => 'Organization',
            'name'  => $settings->get('site_name', $site->name),
            'url'   => url('/'),
            'sameAs' => $sameAs ?: null,
        ]);

        $webpage = array_filter([
            '@type'         => $content->type === 'post' ? 'Article' : 'WebPage',
            'headline'      => $seo['title'],
            'url'           => $seo['canonical'],
            'description'   => $seo['description'] ?: null,
            'image'         => $seo['og_image_url'] ?: null,
            'datePublished' => $content->published_at?->toIso8601String(),
            'dateModified'  => $content->updated_at->toIso8601String(),
        ]);

        $jsonLd = [
            '@context' => 'https://schema.org',
            '@graph'   => [$organization, $webpage],
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>

    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
    @stack('head')
</head>
<body class="h-full bg-white text-gray-900 antialiased flex flex-col min-h-screen">

    {{-- Navigation --}}
    <header class="border-b border-gray-200 bg-white sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16">
                <a href="/" class="flex items-center hover:opacity-80 transition-opacity">
                    @if($logoMedia)
                        <img src="{{ $logoMedia->variantUrl(ImageVariant::FULL) }}" alt="{{ $settings->get('site_name', $site->name) }}" class="h-8 w-auto">
                    @else
                        <span class="text-xl font-bold text-gray-900">{{ $settings->get('site_name', $site->name) }}</span>
                    @endif
                </a>

                {{-- Desktop nav --}}
                <nav class="hidden md:flex items-center gap-6">
                    @foreach ($navigations['header'] ?? [] as $item)
                        @if (!empty($item['children']))
                            <div class="relative group">
                                <a href="{{ $item['url'] }}" target="{{ $item['target'] ?? '_self' }}"
                                   class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                                    {{ $item['label'] }}
                                </a>
                                <div class="absolute left-0 top-full mt-1 w-48 bg-white border border-gray-200 rounded-md shadow-lg hidden group-hover:block z-50">
                                    @foreach ($item['children'] as $child)
                                        <a href="{{ $child['url'] }}" target="{{ $child['target'] ?? '_self' }}"
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                            {{ $child['label'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{ $item['url'] }}" target="{{ $item['target'] ?? '_self' }}"
                               class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                                {{ $item['label'] }}
                            </a>
                        @endif
                    @endforeach
                </nav>

                {{-- Mobile menu button --}}
                <button id="mobile-menu-toggle" class="md:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            {{-- Mobile nav --}}
            <nav id="mobile-menu" class="hidden md:hidden pb-4 flex flex-col gap-3">
                @foreach ($navigations['header'] ?? [] as $item)
                    <a href="{{ $item['url'] }}" target="{{ $item['target'] ?? '_self' }}"
                       class="text-sm font-medium text-gray-700 hover:text-gray-900 py-1">
                        {{ $item['label'] }}
                    </a>
                    @foreach ($item['children'] ?? [] as $child)
                        <a href="{{ $child['url'] }}" target="{{ $child['target'] ?? '_self' }}"
                           class="text-sm text-gray-500 hover:text-gray-900 py-1 pl-4">
                            {{ $child['label'] }}
                        </a>
                    @endforeach
                @endforeach
            </nav>
        </div>
    </header>

    {{-- Page content --}}
    @php
        $mainStyle = collect([
            $appearance->get('color') ? 'background-color:'.$appearance->get('color') : null,
            $appearance->get('text_color') ? 'color:'.$appearance->get('text_color') : null,
            $bgMedia ? 'background-image:url('.$bgMedia->variantUrl(ImageVariant::LARGE).');background-size:cover;background-position:center' : null,
        ])->filter()->implode(';');

        $layoutClass = match($appearance->get('layout')) {
            'boxed'         => 'max-w-4xl mx-auto',
            'sidebar-left'  => 'max-w-6xl mx-auto grid grid-cols-[240px_1fr] gap-8',
            'sidebar-right' => 'max-w-6xl mx-auto grid grid-cols-[1fr_240px] gap-8',
            default         => 'w-full',
        };
    @endphp
    <main class="flex-1 {{ $appearance->get('custom_css_class') }}"
          @if($mainStyle) style="{{ $mainStyle }}" @endif>
        <div class="{{ $layoutClass }}">
            @yield('content')
        </div>
    </main>

    {{-- Footer --}}
    <footer class="border-t border-gray-200 bg-gray-50 mt-auto">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-gray-500">&copy; {{ date('Y') }} {{ $site->name }}</p>
                <nav class="flex flex-wrap justify-center gap-4">
                    @foreach ($navigations['footer'] ?? [] as $item)
                        <a href="{{ $item['url'] }}" target="{{ $item['target'] ?? '_self' }}"
                           class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('mobile-menu-toggle')?.addEventListener('click', function () {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>
    @stack('scripts')
</body>
</html>