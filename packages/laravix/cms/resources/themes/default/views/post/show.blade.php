@extends('themes.default::layouts.app')

@section('content')
    @php
        use Laravix\Cms\Enums\ImageVariant;
        $extraFields = $content->fields->whereNotIn('key', $systemFieldKeys);
        $ogImageId = (int) ($content->fields->firstWhere('key', 'og_image')?->value ?? 0);
        $heroMedia = $ogImageId ? ($mediaMap[$ogImageId] ?? null) : null;
        $hasBuilderContent = $content->grapesjs_html || ! empty($content->blocks);
    @endphp

    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
        <article class="max-w-3xl mx-auto">
            @if ($content->taxonomies->isNotEmpty())
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach ($content->taxonomies as $taxonomy)
                        <span class="inline-block text-xs font-medium text-gray-500 bg-gray-100 rounded-full px-2.5 py-1">{{ $taxonomy->name }}</span>
                    @endforeach
                </div>
            @endif

            <h1 class="text-4xl font-bold text-gray-900 mb-4 leading-tight">{{ $content->title }}</h1>

            <div class="flex items-center gap-2 text-sm text-gray-400 mb-8">
                @if ($content->published_at)
                    <time datetime="{{ $content->published_at->toIso8601String() }}">{{ $content->published_at->format('j. n. Y') }}</time>
                @endif
                @if ($content->author)
                    <span>&middot;</span>
                    <span>{{ $content->author->name }}</span>
                @endif
            </div>

            @if ($heroMedia)
                <img src="{{ $heroMedia->variantUrl(ImageVariant::LARGE) }}" alt="{{ $content->title }}"
                     class="w-full rounded-xl mb-10 object-cover max-h-[28rem]">
            @endif

            @if ($hasBuilderContent)
                @include('laravix::cms.builder-content')
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
        </article>
    </div>
@endsection