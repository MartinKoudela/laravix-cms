@extends('themes.default::layouts.app')

@section('content')
    @php
        use App\Enums\ImageVariant;
        $fields = $content->fields->keyBy('key');
        $heroImageId = $fields->get('hero_image')?->value;
        $heroMedia = $heroImageId ? ($mediaMap[$heroImageId] ?? null) : null;
    @endphp

    @if ($heroMedia)
        <div class="w-full h-64 sm:h-80 overflow-hidden bg-gray-100">
            <img src="{{ $heroMedia->variantUrl(ImageVariant::LARGE) }}" alt="{{ $heroMedia->name }}"
                 class="w-full h-full object-cover">
        </div>
    @endif

    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-10">{{ $content->title }}</h1>

        @if ($archivePosts && $archivePosts->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($archivePosts as $post)
                    @php
                        $postFields = $post->fields->keyBy('key');
                        $postExcerpt = $postFields->get('excerpt')?->value;
                        $postHeroId = $postFields->get('hero_image')?->value;
                        $postHero = $postHeroId ? ($mediaMap[$postHeroId] ?? null) : null;
                    @endphp
                    <a href="{{ $post->is_homepage ? '/' : '/' . $post->slug }}"
                       class="group block rounded-xl overflow-hidden border border-gray-100 hover:shadow-md transition">
                        @if ($postHero)
                            <div class="h-48 overflow-hidden bg-gray-100">
                                <img src="{{ $postHero->variantUrl(ImageVariant::MEDIUM) }}" alt="{{ $postHero->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            </div>
                        @else
                            <div class="h-48 bg-gray-100"></div>
                        @endif
                        <div class="p-5">
                            @if ($post->published_at)
                                <p class="text-xs text-gray-400 mb-2">
                                    {{ $post->published_at->format('j. n. Y') }}
                                </p>
                            @endif
                            <h2 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition mb-2">
                                {{ $post->title }}
                            </h2>
                            @if ($postExcerpt)
                                <p class="text-sm text-gray-500 leading-relaxed line-clamp-3">{{ $postExcerpt }}</p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-gray-400">Žádné příspěvky zatím nejsou publikovány.</p>
        @endif
    </div>
@endsection
