@extends('themes.default::layouts.app')

@section('content')
    @php
        use App\Enums\ImageVariant;
        $hasBuilderContent = $content->grapesjs_html || ! empty($content->blocks);
    @endphp

    @if ($hasBuilderContent)
        @include('cms.builder-content')
    @endif

    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-10">{{ $content->title }}</h1>

        @if ($archivePosts && $archivePosts->isNotEmpty())
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($archivePosts as $post)
                    @php
                        $postOgImageId = (int) ($post->fields->firstWhere('key', 'og_image')?->value ?? 0);
                        $postMedia = $postOgImageId ? ($mediaMap[$postOgImageId] ?? null) : null;
                    @endphp
                    <a href="{{ $post->is_homepage ? '/' : '/' . $post->slug }}"
                       class="group block bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition overflow-hidden">
                        @if ($postMedia)
                            <div class="aspect-video overflow-hidden bg-gray-100">
                                <img src="{{ $postMedia->variantUrl(ImageVariant::MEDIUM) }}" alt="{{ $post->title }}"
                                     class="w-full h-full object-cover">
                            </div>
                        @endif
                        <div class="p-6">
                            @if ($post->taxonomies->isNotEmpty())
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @foreach ($post->taxonomies as $taxonomy)
                                        <span class="inline-block text-xs font-medium text-gray-500 bg-gray-100 rounded-full px-2.5 py-1">{{ $taxonomy->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                            <h2 class="text-lg font-semibold text-gray-900 group-hover:text-gray-600 transition-colors mb-2 leading-snug">{{ $post->title }}</h2>
                            <div class="flex items-center gap-2 text-sm text-gray-400">
                                @if ($post->published_at)
                                    <time datetime="{{ $post->published_at->toIso8601String() }}">{{ $post->published_at->format('j. n. Y') }}</time>
                                @endif
                                @if ($post->author)
                                    <span>&middot;</span>
                                    <span>{{ $post->author->name }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">Žádné příspěvky zatím nejsou publikovány.</p>
        @endif
    </div>
@endsection