@extends('themes.default::layouts.app')

@section('content')
    @php
        $fields = $content->fields->keyBy('key');
        $body = $fields->get('body')?->value;
        $heroImageId = $fields->get('hero_image')?->value;
        $heroMedia = $heroImageId ? ($mediaMap[$heroImageId] ?? null) : null;
        $extraFields = $content->fields->whereNotIn('key', ['body', 'excerpt', 'hero_image']);
    @endphp

    @if ($heroMedia)
        <div class="w-full h-64 overflow-hidden bg-gray-100">
            <img src="{{ $heroMedia->url }}" alt="{{ $heroMedia->name }}" class="w-full h-full object-cover">
        </div>
    @endif

    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
        <div class="max-w-3xl">
            <h1 class="text-4xl font-bold text-gray-900 mb-6">{{ $content->title }}</h1>

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
        </div>
    </div>
@endsection