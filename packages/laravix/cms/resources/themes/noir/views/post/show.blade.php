@extends('themes.noir::layouts.app')

@section('content')
    @php
        use Laravix\Cms\Enums\ImageVariant;

        $ogImageId = (int) ($content->fields->firstWhere('key', 'og_image')?->value ?? 0);
        $heroMedia = $ogImageId ? ($mediaMap[$ogImageId] ?? null) : null;
        $hasBuilderContent = $content->grapesjs_html || ! empty($content->blocks);
        $readingMinutes = $grapesjsHtml ? max(1, (int) ceil(str_word_count(strip_tags($grapesjsHtml)) / 200)) : null;
    @endphp

    <article class="noir-article">
        <header class="noir-shell noir-article__header">
            <div class="noir-article__meta">
                @if ($content->published_at)
                    <time datetime="{{ $content->published_at->toIso8601String() }}">{{ $content->published_at->translatedFormat('j. F Y') }}</time>
                @endif
                @if ($content->author)
                    <span>{{ $content->author->name }}</span>
                @endif
                @if ($readingMinutes)
                    <span>{{ $readingMinutes }} min</span>
                @endif
            </div>

            <h1 class="noir-article__title">{{ $content->title }}</h1>

            @include('themes.noir::partials.tags', ['taxonomies' => $content->taxonomies])
        </header>

        @if ($heroMedia)
            <figure class="noir-shell noir-article__hero">
                <img src="{{ $heroMedia->variantUrl(ImageVariant::LARGE) }}" alt="{{ $content->title }}">
            </figure>
        @endif

        @if ($hasBuilderContent)
            <div class="noir-article__body">
                @include('laravix::cms.builder-content')
            </div>
        @endif

        <div class="noir-shell noir-article__footer">
            @include('themes.noir::partials.fields', ['fields' => $content->fields->whereNotIn('key', $systemFieldKeys)])
        </div>
    </article>
@endsection
