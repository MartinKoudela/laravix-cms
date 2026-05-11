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
        {!! $content->grapesjs_html !!}

    @elseif ($hasBlocks)

        @foreach ($content->blocks as $block)
            @include("themes.default::blocks.{$block['type']}", array_merge($block['data'] ?? [], ['mediaMap' => $mediaMap]))
        @endforeach
    @else
        @if ($heroMedia)
            <div class="w-full h-64 sm:h-80 md:h-96 overflow-hidden bg-gray-100">
                <img src="{{ $heroMedia->variantUrl(ImageVariant::LARGE) }}" alt="{{ $heroMedia->name }}"
                     class="w-full h-full object-cover">
            </div>
        @endif

        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
            <div class="max-w-3xl">

                <h1 class="text-4xl font-bold text-gray-900 mb-6 leading-tight">
                    {{ $content->title }}
                </h1>

                @if ($excerpt)
                    <p class="text-xl text-gray-500 mb-8 leading-relaxed">{{ $excerpt }}</p>
                @endif

                @if ($body)
                    <div class="prose prose-gray max-w-none text-gray-700 leading-relaxed space-y-4">
                        {!! $body !!}
                    </div>
                @endif

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

                @if ($content->taxonomies->isNotEmpty())
                    <div class="mt-8 flex flex-wrap gap-2">
                        @foreach ($content->taxonomies as $taxonomy)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700">
                                {{ $taxonomy->name }}
                            </span>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    @endif
@endsection
