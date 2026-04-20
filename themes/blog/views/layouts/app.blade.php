 <!DOCTYPE html>
<html lang="{{ $settings->get('locale', 'en') }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @if($faviconMedia)
        <link rel="icon" href="{{ $faviconMedia->url }}">
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

    {{-- Google verification --}}
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;1,400&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, .font-serif { font-family: 'Lora', serif; }
    </style>
    @stack('head')
</head>
<body class="h-full bg-white text-gray-900 antialiased flex flex-col min-h-screen">

    {{-- Header --}}
    <header class="border-b border-gray-100 bg-white sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16">

                <a href="/" class="flex items-center hover:opacity-75 transition-opacity">
                    @if($logoMedia)
                        <img src="{{ $logoMedia->url }}" alt="{{ $settings->get('site_name', $site->name) }}" class="h-7 w-auto">
                    @else
                        <span class="text-lg font-semibold tracking-tight text-gray-900" style="font-family: 'Lora', serif;">
                            {{ $settings->get('site_name', $site->name) }}
                        </span>
                    @endif
                </a>

                {{-- Desktop nav --}}
                <nav class="hidden md:flex items-center gap-7">
                    @foreach ($navPages as $page)
                        <a href="{{ $page->is_homepage ? '/' : '/'.$page->slug }}"
                           class="text-sm text-gray-500 hover:text-gray-900 transition-colors {{ (isset($content) && $content->id === $page->id) ? 'text-gray-900 font-medium' : '' }}">
                            {{ $page->title }}
                        </a>
                    @endforeach
                </nav>

                {{-- Mobile toggle --}}
                <button id="mobile-menu-toggle" class="md:hidden p-2 text-gray-500 hover:text-gray-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            {{-- Mobile nav --}}
            <nav id="mobile-menu" class="hidden md:hidden pb-4 flex flex-col gap-3 border-t border-gray-100 pt-3">
                @foreach ($navPages as $page)
                    <a href="{{ $page->is_homepage ? '/' : '/'.$page->slug }}"
                       class="text-sm text-gray-600 hover:text-gray-900 py-1">
                        {{ $page->title }}
                    </a>
                @endforeach
            </nav>
        </div>
    </header>

    {{-- Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t border-gray-100 mt-auto">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-10">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                <div class="flex flex-col items-center sm:items-start gap-1">
                    <span class="text-sm font-medium text-gray-800" style="font-family: 'Lora', serif;">
                        {{ $settings->get('site_name', $site->name) }}
                    </span>
                    <p class="text-xs text-gray-400">&copy; {{ date('Y') }} All rights reserved.</p>
                </div>

                <div class="flex items-center gap-5">
                    @foreach ($navPages as $page)
                        <a href="{{ $page->is_homepage ? '/' : '/'.$page->slug }}"
                           class="text-xs text-gray-400 hover:text-gray-700 transition-colors">
                            {{ $page->title }}
                        </a>
                    @endforeach
                </div>

                {{-- Social links --}}
                @php
                    $socials = array_filter([
                        'twitter' => $settings->get('twitter_url'),
                        'linkedin' => $settings->get('linkedin_url'),
                        'github' => $settings->get('github_url'),
                    ]);
                @endphp
                @if($socials)
                    <div class="flex items-center gap-3">
                        @foreach($socials as $platform => $url)
                            <a href="{{ $url }}" target="_blank" rel="noopener" class="text-gray-400 hover:text-gray-700 transition-colors text-xs">
                                {{ ucfirst($platform) }}
                            </a>
                        @endforeach
                    </div>
                @endif
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
