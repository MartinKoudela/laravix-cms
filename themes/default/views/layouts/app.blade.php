<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content->title ?? $site->name }} – {{ $site->name }}</title>
    <meta name="description" content="{{ optional($content->fields->firstWhere('key', 'meta_description'))->value ?? optional($content->fields->firstWhere('key', 'excerpt'))->value ?? $site->name }}">
    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
    @stack('head')
</head>
<body class="h-full bg-white text-gray-900 antialiased flex flex-col min-h-screen">

    {{-- Navigation --}}
    <header class="border-b border-gray-200 bg-white sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16">
                <a href="/" class="text-xl font-bold text-gray-900 hover:text-gray-700 transition-colors">
                    {{ $site->name }}
                </a>

                {{-- Desktop nav --}}
                <nav class="hidden md:flex items-center gap-6">
                    @foreach ($navPages as $page)
                        <a href="{{ $page->is_homepage ? '/' : '/'.$page->slug }}"
                           class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors {{ (isset($content) && $content->id === $page->id) ? 'text-gray-900 font-semibold' : '' }}">
                            {{ $page->title }}
                        </a>
                    @endforeach
                    @if ($recentPosts->isNotEmpty())
                        <a href="/blog"
                           class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                            Blog
                        </a>
                    @endif
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
                @foreach ($navPages as $page)
                    <a href="{{ $page->is_homepage ? '/' : '/'.$page->slug }}"
                       class="text-sm font-medium text-gray-700 hover:text-gray-900 py-1">
                        {{ $page->title }}
                    </a>
                @endforeach
                @if ($recentPosts->isNotEmpty())
                    <a href="/blog" class="text-sm font-medium text-gray-700 hover:text-gray-900 py-1">Blog</a>
                @endif
            </nav>
        </div>
    </header>

    {{-- Page content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t border-gray-200 bg-gray-50 mt-auto">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-gray-500">&copy; {{ date('Y') }} {{ $site->name }}</p>
                <nav class="flex flex-wrap justify-center gap-4">
                    @foreach ($navPages as $page)
                        <a href="{{ $page->is_homepage ? '/' : '/'.$page->slug }}"
                           class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                            {{ $page->title }}
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