@extends('themes.default::layouts.app')

@section('content')
    @php
        use App\Enums\ImageVariant;
        $fields = $content->fields->keyBy('key');
        $body = $fields->get('body')?->value;
        $excerpt = $fields->get('excerpt')?->value;
        $heroImageId = $fields->get('hero_image')?->value;
        $heroMedia = $heroImageId ? ($mediaMap[$heroImageId] ?? null) : null;
        $extraFields = $content->fields->whereNotIn('key', $systemFieldKeys);
    @endphp

    @if ($heroMedia)
        <img src="{{ $heroMedia->variantUrl(ImageVariant::LARGE) }}" alt="{{ $heroMedia->name }}" style="width:100%;display:block;">
    @endif

    <div>
        <article>
            @if ($content->taxonomies->isNotEmpty())
                <div>
                    @foreach ($content->taxonomies as $taxonomy)
                        <span>{{ $taxonomy->name }}</span>
                    @endforeach
                </div>
            @endif

            <h1>{{ $content->title }}</h1>

            <div>
                @if ($content->published_at)
                    <time datetime="{{ $content->published_at->toIso8601String() }}">
                        {{ $content->published_at->format('j. n. Y') }}
                    </time>
                @endif
                @if ($content->author)
                    <span>{{ $content->author->name }}</span>
                @endif
            </div>

            @if ($excerpt)
                <p>{{ $excerpt }}</p>
            @endif

            @if ($body)
                <div>{!! $body !!}</div>
            @endif

            @if ($extraFields->isNotEmpty())
                <div>
                    @foreach ($extraFields as $field)
                        <div>
                            <dt>{{ $field->key }}</dt>
                            <dd>{{ $field->value }}</dd>
                        </div>
                    @endforeach
                </div>
            @endif
        </article>

        @if ($recentPosts->isNotEmpty())
            <aside>
                <h2>Recent posts</h2>
                <ul>
                    @foreach ($recentPosts as $post)
                        <li>
                            <a href="/{{ $post->slug }}">
                                <p>{{ $post->title }}</p>
                                @if ($post->published_at)
                                    <p>{{ $post->published_at->format('j. n. Y') }}</p>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </aside>
        @endif
    </div>
@endsection