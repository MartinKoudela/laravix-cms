@extends('themes.default::layouts.app')

@section('content')
    @php
        $extraFields = $content->fields->whereNotIn('key', $systemFieldKeys);
        $hasBuilderContent = $content->grapesjs_html || ! empty($content->blocks);
    @endphp

    @if ($hasBuilderContent)
        @include('laravix::cms.builder-content')
    @else
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
            <div class="max-w-3xl">
                <h1 class="text-4xl font-bold text-gray-900 mb-6">{{ $content->title }}</h1>

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
                    <div class="flex flex-wrap gap-2 mt-6">
                        @foreach ($content->taxonomies as $taxonomy)
                            <span class="inline-block text-xs font-medium text-gray-500 bg-gray-100 rounded-full px-2.5 py-1">{{ $taxonomy->name }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif
@endsection