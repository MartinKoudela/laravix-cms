@extends('themes.noir::layouts.app')

@section('content')
    @php
        use Laravix\Cms\Enums\ImageVariant;

        $hasBuilderContent = $content->grapesjs_html || ! empty($content->blocks);
    @endphp

    @if ($hasBuilderContent)
        @include('laravix::cms.builder-content')
    @else
        <div class="noir-shell noir-archive">
            <header class="noir-page__header">
                <h1 class="noir-page__title">{{ $content->title }}</h1>
            </header>

            @if ($archivePosts && $archivePosts->isNotEmpty())
                <ol class="noir-archive__list">
                    @foreach ($archivePosts as $post)
                        @php
                            $postImageId = (int) ($post->fields->firstWhere('key', 'og_image')?->value ?? 0);
                            $postMedia = $postImageId ? ($mediaMap[$postImageId] ?? null) : null;
                        @endphp
                        <li>
                            <a href="{{ $post->path($defaultLocale ?? $settings->get('locale', 'en')) }}" class="noir-archive__item">
                                <span class="noir-archive__index">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>

                                <span class="noir-archive__text">
                                    <span class="noir-archive__title">{{ $post->title }}</span>
                                    <span class="noir-archive__meta">
                                        @if ($post->published_at)
                                            <time datetime="{{ $post->published_at->toIso8601String() }}">{{ $post->published_at->translatedFormat('j. F Y') }}</time>
                                        @endif
                                        @foreach ($post->taxonomies as $taxonomy)
                                            <span>{{ $taxonomy->name }}</span>
                                        @endforeach
                                    </span>
                                </span>

                                @if ($postMedia)
                                    <img src="{{ $postMedia->variantUrl(ImageVariant::MEDIUM) }}" alt="" class="noir-archive__thumb" loading="lazy">
                                @endif

                                <svg class="noir-archive__arrow" viewBox="0 0 24 24" aria-hidden="true"><path d="M7 17 17 7M9 7h8v8" fill="none" stroke="currentColor" stroke-width="1.5"/></svg>
                            </a>
                        </li>
                    @endforeach
                </ol>
            @else
                <p class="noir-empty">{{ __('laravix::theme.no_posts') }}</p>
            @endif
        </div>
    @endif
@endsection
