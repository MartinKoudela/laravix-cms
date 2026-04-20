@extends('themes.blog::layouts.app')

@section('content')
    @php
        $fields = $content->fields->keyBy('key');
        $body = $fields->get('body')?->value;
        $excerpt = $fields->get('excerpt')?->value;
        $heroImageId = $fields->get('hero_image')?->value;
        $heroMedia = $heroImageId ? ($mediaMap[$heroImageId] ?? null) : null;
    @endphp

    {{-- Hero image --}}
    @if ($heroMedia)
        <div class="w-full h-72 sm:h-96 md:h-[520px] overflow-hidden bg-gray-100">
            <img src="{{ $heroMedia->url }}" alt="{{ $heroMedia->name }}" class="w-full h-full object-cover">
        </div>
    @endif

    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-14">
        <div class="flex gap-16 items-start">

            {{-- Article --}}
            <article class="flex-1 min-w-0">

                {{-- Categories --}}
                @if ($content->taxonomies->isNotEmpty())
                    <div class="flex flex-wrap gap-2 mb-5">
                        @foreach ($content->taxonomies as $taxonomy)
                            <span class="text-xs font-semibold uppercase tracking-widest text-indigo-600">
                                {{ $taxonomy->name }}
                            </span>
                        @endforeach
                    </div>
                @endif

                {{-- Title --}}
                <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 leading-tight mb-5" style="font-family: 'Lora', serif;">
                    {{ $content->title }}
                </h1>

                {{-- Excerpt --}}
                @if ($excerpt)
                    <p class="text-xl text-gray-500 leading-relaxed mb-7">{{ $excerpt }}</p>
                @endif

                {{-- Meta --}}
                <div class="flex items-center gap-4 pb-8 mb-8 border-b border-gray-100 text-sm text-gray-400">
                    @if ($content->author)
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-gray-900 text-white text-xs font-semibold">
                                {{ mb_substr($content->author->name, 0, 1) }}
                            </span>
                            <span class="font-medium text-gray-700">{{ $content->author->name }}</span>
                        </div>
                    @endif
                    @if ($content->published_at)
                        <span>&middot;</span>
                        <time datetime="{{ $content->published_at->toIso8601String() }}">
                            {{ $content->published_at->format('F j, Y') }}
                        </time>
                    @endif
                </div>

                {{-- Body --}}
                @if ($body)
                    <div class="prose prose-lg prose-gray max-w-none text-gray-700 leading-relaxed">
                        {!! $body !!}
                    </div>
                @endif

                {{-- Tags --}}
                @if ($content->taxonomies->isNotEmpty())
                    <div class="mt-10 pt-8 border-t border-gray-100 flex flex-wrap gap-2">
                        @foreach ($content->taxonomies as $taxonomy)
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                {{ $taxonomy->name }}
                            </span>
                        @endforeach
                    </div>
                @endif

            </article>

            {{-- Sidebar --}}
            @if ($recentPosts->isNotEmpty())
                <aside class="hidden lg:block w-64 flex-shrink-0">
                    <div class="sticky top-24">
                        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-5">Recent posts</p>
                        <ul class="space-y-5">
                            @foreach ($recentPosts->take(6) as $post)
                                <li>
                                    <a href="/{{ $post->slug }}" class="group block {{ $post->id === $content->id ? 'pointer-events-none' : '' }}">
                                        <p class="text-sm font-medium text-gray-800 group-hover:text-indigo-600 transition-colors leading-snug {{ $post->id === $content->id ? 'text-indigo-600' : '' }}" style="font-family: 'Lora', serif;">
                                            {{ $post->title }}
                                        </p>
                                        @if ($post->published_at)
                                            <p class="text-xs text-gray-400 mt-1">{{ $post->published_at->format('M j, Y') }}</p>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </aside>
            @endif

        </div>
    </div>
@endsection