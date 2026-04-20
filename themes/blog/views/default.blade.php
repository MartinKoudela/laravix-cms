@extends('themes.blog::layouts.app')

@section('content')
    @php
        $fields = $content->fields->keyBy('key');
        $body = $fields->get('body')?->value;
        $heroImageId = $fields->get('hero_image')?->value;
        $heroMedia = $heroImageId ? ($mediaMap[$heroImageId] ?? null) : null;
        $featuredPost = $recentPosts->first();
        $otherPosts = $recentPosts->skip(1);
    @endphp

    {{-- Hero --}}
    @if ($heroMedia)
        <div class="w-full h-64 sm:h-80 overflow-hidden bg-gray-100">
            <img src="{{ $heroMedia->url }}" alt="{{ $heroMedia->name }}" class="w-full h-full object-cover">
        </div>
    @endif

    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-14">

        {{-- Page body --}}
        @if ($body)
            <div class="prose prose-lg prose-gray max-w-2xl text-gray-700 leading-relaxed mb-16">
                {!! $body !!}
            </div>
        @endif

        {{-- Blog section --}}
        @if ($recentPosts->isNotEmpty())
            <div class="mb-6 flex items-center justify-between">
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">Latest posts</p>
            </div>

            {{-- Featured post --}}
            @if ($featuredPost)
                @php
                    $featuredFields = $featuredPost->fields->keyBy('key');
                    $featuredHeroId = $featuredFields->get('hero_image')?->value;
                    $featuredHero = $featuredHeroId ? ($mediaMap[$featuredHeroId] ?? null) : null;
                    $featuredExcerpt = $featuredFields->get('excerpt')?->value;
                @endphp
                <a href="/{{ $featuredPost->slug }}" class="group block mb-12">
                    <div class="grid md:grid-cols-2 gap-8 items-center">
                        @if ($featuredHero)
                            <div class="overflow-hidden rounded-xl bg-gray-100 aspect-video">
                                <img src="{{ $featuredHero->url }}" alt="{{ $featuredPost->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </div>
                        @endif
                        <div class="{{ $featuredHero ? '' : 'md:col-span-2' }}">
                            @if ($featuredPost->taxonomies->isNotEmpty())
                                <div class="flex gap-2 mb-3">
                                    @foreach ($featuredPost->taxonomies->take(2) as $tax)
                                        <span class="text-xs font-semibold uppercase tracking-widest text-indigo-600">{{ $tax->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                            <h2 class="text-3xl font-bold text-gray-900 leading-tight group-hover:text-indigo-600 transition-colors mb-3" style="font-family: 'Lora', serif;">
                                {{ $featuredPost->title }}
                            </h2>
                            @if ($featuredExcerpt)
                                <p class="text-gray-500 leading-relaxed mb-4">{{ $featuredExcerpt }}</p>
                            @endif
                            <div class="flex items-center gap-3 text-sm text-gray-400">
                                @if ($featuredPost->author)
                                    <span class="font-medium text-gray-600">{{ $featuredPost->author->name }}</span>
                                    <span>&middot;</span>
                                @endif
                                @if ($featuredPost->published_at)
                                    <time>{{ $featuredPost->published_at->format('F j, Y') }}</time>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            @endif

            {{-- Other posts grid --}}
            @if ($otherPosts->isNotEmpty())
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8 pt-8 border-t border-gray-100">
                    @foreach ($otherPosts as $post)
                        @php
                            $postFields = $post->fields->keyBy('key');
                            $postHeroId = $postFields->get('hero_image')?->value;
                            $postHero = $postHeroId ? ($mediaMap[$postHeroId] ?? null) : null;
                            $postExcerpt = $postFields->get('excerpt')?->value;
                        @endphp
                        <a href="/{{ $post->slug }}" class="group block">
                            @if ($postHero)
                                <div class="overflow-hidden rounded-lg bg-gray-100 aspect-video mb-4">
                                    <img src="{{ $postHero->url }}" alt="{{ $post->title }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </div>
                            @endif
                            @if ($post->taxonomies->isNotEmpty())
                                <span class="text-xs font-semibold uppercase tracking-widest text-indigo-600 mb-2 block">
                                    {{ $post->taxonomies->first()->name }}
                                </span>
                            @endif
                            <h3 class="font-bold text-gray-900 leading-snug group-hover:text-indigo-600 transition-colors mb-2" style="font-family: 'Lora', serif;">
                                {{ $post->title }}
                            </h3>
                            @if ($postExcerpt)
                                <p class="text-sm text-gray-500 leading-relaxed mb-3 line-clamp-2">{{ $postExcerpt }}</p>
                            @endif
                            @if ($post->published_at)
                                <p class="text-xs text-gray-400">{{ $post->published_at->format('M j, Y') }}</p>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endif
        @endif

    </div>
@endsection