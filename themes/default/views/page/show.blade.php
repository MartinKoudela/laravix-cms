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
        $hasBlocks = !empty($content->blocks);
    @endphp

    @if ($content->grapesjs_html)
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <style>
            .gjs-content *, .gjs-content *::before, .gjs-content *::after { box-sizing: border-box; }
            .gjs-content img, .gjs-content video, .gjs-content iframe { max-width: 100%; }
        </style>
        <div class="gjs-content">{!! $content->grapesjs_html !!}</div>

    @elseif ($hasBlocks)

        @foreach ($content->blocks as $block)
            @include("themes.default::blocks.{$block['type']}", array_merge($block['data'] ?? [], ['mediaMap' => $mediaMap]))
        @endforeach
    @else
        @if ($heroMedia)
            <img src="{{ $heroMedia->variantUrl(ImageVariant::LARGE) }}" alt="{{ $heroMedia->name }}" style="width:100%;display:block;">
        @endif

        <div>
            <h1>{{ $content->title }}</h1>

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

            @if ($content->taxonomies->isNotEmpty())
                <div>
                    @foreach ($content->taxonomies as $taxonomy)
                        <span>{{ $taxonomy->name }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
@endsection
