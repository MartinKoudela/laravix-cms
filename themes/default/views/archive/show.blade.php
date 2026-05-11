@extends('themes.default::layouts.app')

@section('content')
    @php
        use App\Enums\ImageVariant;
        $fields = $content->fields->keyBy('key');
        $heroImageId = $fields->get('hero_image')?->value;
        $heroMedia = $heroImageId ? ($mediaMap[$heroImageId] ?? null) : null;
    @endphp

    @if ($heroMedia)
        <img src="{{ $heroMedia->variantUrl(ImageVariant::LARGE) }}" alt="{{ $heroMedia->name }}" style="width:100%;display:block;">
    @endif

    <div>
        <h1>{{ $content->title }}</h1>

        @if ($archivePosts && $archivePosts->isNotEmpty())
            <div>
                @foreach ($archivePosts as $post)
                    @php
                        $postFields = $post->fields->keyBy('key');
                        $postExcerpt = $postFields->get('excerpt')?->value;
                        $postHeroId = $postFields->get('hero_image')?->value;
                        $postHero = $postHeroId ? ($mediaMap[$postHeroId] ?? null) : null;
                    @endphp
                    <a href="{{ $post->is_homepage ? '/' : '/' . $post->slug }}">
                        @if ($postHero)
                            <img src="{{ $postHero->variantUrl(ImageVariant::MEDIUM) }}" alt="{{ $postHero->name }}" style="width:100%;display:block;">
                        @endif
                        <div>
                            @if ($post->published_at)
                                <p>{{ $post->published_at->format('j. n. Y') }}</p>
                            @endif
                            <h2>{{ $post->title }}</h2>
                            @if ($postExcerpt)
                                <p>{{ $postExcerpt }}</p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p>Žádné příspěvky zatím nejsou publikovány.</p>
        @endif
    </div>
@endsection