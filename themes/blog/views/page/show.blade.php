@extends('themes.blog::layouts.app')

@section('content')
    @php
        $fields = $content->fields->keyBy('key');
        $body = $fields->get('body')?->value;
        $excerpt = $fields->get('excerpt')?->value;
        $heroImageId = $fields->get('hero_image')?->value;
        $heroMedia = $heroImageId ? ($mediaMap[$heroImageId] ?? null) : null;
    @endphp

    @if ($heroMedia)
        <div class="w-full h-64 sm:h-80 overflow-hidden bg-gray-100">
            <img src="{{ $heroMedia->url }}" alt="{{ $heroMedia->name }}" class="w-full h-full object-cover">
        </div>
    @endif

    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-14">
        <div class="max-w-3xl">

            <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 leading-tight mb-6" style="font-family: 'Lora', serif;">
                {{ $content->title }}
            </h1>

            @if ($excerpt)
                <p class="text-xl text-gray-500 leading-relaxed mb-8">{{ $excerpt }}</p>
            @endif

            @if ($body)
                <div class="prose prose-lg prose-gray max-w-none text-gray-700 leading-relaxed">
                    {!! $body !!}
                </div>
            @endif

            @if ($content->taxonomies->isNotEmpty())
                <div class="mt-10 pt-8 border-t border-gray-100 flex flex-wrap gap-2">
                    @foreach ($content->taxonomies as $taxonomy)
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                            {{ $taxonomy->name }}
                        </span>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
@endsection