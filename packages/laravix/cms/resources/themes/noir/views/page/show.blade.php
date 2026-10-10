@extends('themes.noir::layouts.app')

@section('content')
    @php
        $hasBuilderContent = $content->grapesjs_html || ! empty($content->blocks);
    @endphp

    @if ($hasBuilderContent)
        @include('laravix::cms.builder-content')
    @else
        <div class="noir-shell noir-page">
            <header class="noir-page__header">
                @include('themes.noir::partials.tags', ['taxonomies' => $content->taxonomies])
                <h1 class="noir-page__title">{{ $content->title }}</h1>
            </header>

            @include('themes.noir::partials.fields', ['fields' => $content->fields->whereNotIn('key', $systemFieldKeys)])
        </div>
    @endif
@endsection
