@extends('themes.blog::layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-14">

        <div class="mb-10">
            <h1 class="text-4xl font-bold text-gray-900 mb-3" style="font-family: 'Lora', serif;">
                {{ $content->title }}
            </h1>
            @php $excerpt = $content->fields->keyBy('key')->get('excerpt')?->value; @endphp
            @if ($excerpt)
                <p class="text-lg text-gray-500">{{ $excerpt }}</p>
            @endif
        </div>

        @if ($archivePosts->isEmpty())
            <p class="text-gray-400">No posts yet.</p>
        @else
            <div class="divide-y divide-gray-100">
                @foreach ($archivePosts as $post)
                    @php
                        $postFields = $post->fields->keyBy('key');
                        $postHeroId = $postFields->get('hero_image')?->value;
                        $postHero = $postHeroId ? ($mediaMap[$postHeroId] ?? null) : null;
                        $postExcerpt = $postFields->get('excerpt')?->value;
                    @endphp
                    <a href="/{{ $post->slug }}" class="group flex gap-6 py-8 items-start">

                        @if ($postHero)
                            <div class="hidden sm:block flex-shrink-0 w-40 h-28 overflow-hidden rounded-lg bg-gray-100">
                                <img src="{{ $postHero->url }}" alt="{{ $post->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            @if ($post->taxonomies->isNotEmpty())
                                <span class="text-xs font-semibold uppercase tracking-widest text-indigo-600 mb-2 block">
                                    {{ $post->taxonomies->first()->name }}
                                </span>
                            @endif
                            <h2 class="text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors leading-snug mb-2" style="font-family: 'Lora', serif;">
                                {{ $post->title }}
                            </h2>
                            @if ($postExcerpt)
                                <p class="text-gray-500 text-sm leading-relaxed mb-3 line-clamp-2">{{ $postExcerpt }}</p>
                            @endif
                            <div class="flex items-center gap-3 text-xs text-gray-400">
                                @if ($post->author)
                                    <span>{{ $post->author->name }}</span>
                                    <span>&middot;</span>
                                @endif
                                @if ($post->published_at)
                                    <time>{{ $post->published_at->format('F j, Y') }}</time>
                                @endif
                            </div>
                        </div>

                    </a>
                @endforeach
            </div>
        @endif

    </div>
@endsection