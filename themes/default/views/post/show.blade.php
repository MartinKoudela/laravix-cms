@extends('themes.default::layouts.app')

@section('content')
    @php
        $fields = $content->fields->keyBy('key');
        $body = $fields->get('body')?->value;
        $excerpt = $fields->get('excerpt')?->value;
        $heroImageId = $fields->get('hero_image')?->value;
        $heroMedia = $heroImageId ? ($mediaMap[$heroImageId] ?? null) : null;
        $extraFields = $content->fields->whereNotIn('key', ['body', 'excerpt', 'hero_image']);
    @endphp

    @if ($heroMedia)
        <div class="w-full h-64 sm:h-80 md:h-[480px] overflow-hidden bg-gray-100">
            <img src="{{ $heroMedia->url }}" alt="{{ $heroMedia->name }}"
                 class="w-full h-full object-cover">
        </div>
    @endif

    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
        <div class="flex gap-12 items-start">

            {{-- Main column --}}
            <article class="flex-1 min-w-0">

                {{-- Taxonomies / categories --}}
                @if ($content->taxonomies->isNotEmpty())
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach ($content->taxonomies as $taxonomy)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                {{ $taxonomy->name }}
                            </span>
                        @endforeach
                    </div>
                @endif

                {{-- Title --}}
                <h1 class="text-4xl font-bold text-gray-900 mb-4 leading-tight">
                    {{ $content->title }}
                </h1>

                {{-- Meta: date + author --}}
                <div class="flex items-center gap-4 text-sm text-gray-500 mb-8">
                    @if ($content->published_at)
                        <time datetime="{{ $content->published_at->toIso8601String() }}">
                            {{ $content->published_at->format('j. n. Y') }}
                        </time>
                    @endif
                    @if ($content->author)
                        <span class="flex items-center gap-1.5">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-200 text-xs font-semibold text-gray-600">
                                {{ mb_substr($content->author->name, 0, 1) }}
                            </span>
                            {{ $content->author->name }}
                        </span>
                    @endif
                </div>

                {{-- Excerpt --}}
                @if ($excerpt)
                    <p class="text-xl text-gray-500 mb-8 leading-relaxed font-medium">{{ $excerpt }}</p>
                @endif

                {{-- Body --}}
                @if ($body)
                    <div class="prose prose-gray max-w-none text-gray-700 leading-relaxed space-y-4">
                        {!! $body !!}
                    </div>
                @endif

                {{-- Extra fields --}}
                @if ($extraFields->isNotEmpty())
                    <div class="mt-10 pt-8 border-t border-gray-200 space-y-4">
                        @foreach ($extraFields as $field)
                            <div>
                                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">{{ $field->key }}</dt>
                                <dd class="text-gray-700">{{ $field->value }}</dd>
                            </div>
                        @endforeach
                    </div>
                @endif

            </article>

            {{-- Sidebar --}}
            @if ($recentPosts->isNotEmpty())
                <aside class="hidden lg:block w-72 flex-shrink-0">
                    <div class="sticky top-24">
                        <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Recent posts</h2>
                        <ul class="space-y-4">
                            @foreach ($recentPosts as $post)
                                <li>
                                    <a href="/{{ $post->slug }}"
                                       class="group block {{ $post->id === $content->id ? 'pointer-events-none' : '' }}">
                                        <p class="text-sm font-medium text-gray-800 group-hover:text-blue-600 transition-colors leading-snug {{ $post->id === $content->id ? 'text-blue-600' : '' }}">
                                            {{ $post->title }}
                                        </p>
                                        @if ($post->published_at)
                                            <p class="text-xs text-gray-400 mt-0.5">
                                                {{ $post->published_at->format('j. n. Y') }}
                                            </p>
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