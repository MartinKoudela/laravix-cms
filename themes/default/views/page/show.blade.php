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
        @push('head')
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
            <style>
                .gjs-content *, .gjs-content *::before, .gjs-content *::after { box-sizing: border-box; }
                .gjs-content img, .gjs-content video, .gjs-content iframe { max-width: 100%; }
            </style>
        @endpush
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
            <script>
                document.querySelectorAll('.swiper:not(.swiper-initialized)').forEach(function(el) {
                    var breakpoints;
                    try { breakpoints = el.dataset.breakpoints ? JSON.parse(el.dataset.breakpoints) : undefined; } catch(e) {}
                    new Swiper(el, {
                        loop: el.dataset.loop === 'true',
                        centeredSlides: el.dataset.centered === 'true',
                        slidesPerView: el.dataset.perView === 'auto' ? 'auto' : (parseFloat(el.dataset.perView) || 1),
                        spaceBetween: parseInt(el.dataset.gap) || 0,
                        autoplay: el.dataset.autoplay ? { delay: parseInt(el.dataset.autoplay), disableOnInteraction: false } : false,
                        pagination: el.querySelector('.swiper-pagination') ? { el: el.querySelector('.swiper-pagination'), clickable: true } : false,
                        navigation: el.querySelector('.swiper-button-next') ? { nextEl: el.querySelector('.swiper-button-next'), prevEl: el.querySelector('.swiper-button-prev') } : false,
                        breakpoints: breakpoints,
                    });
                });
            </script>
        @endpush
        <div class="gjs-content" style="isolation:isolate">{!! $content->grapesjs_html !!}</div>

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
