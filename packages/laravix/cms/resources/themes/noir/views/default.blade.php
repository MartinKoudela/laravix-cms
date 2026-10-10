@extends('themes.noir::layouts.app')

@section('content')
    <div class="noir-shell noir-page">
        <header class="noir-page__header">
            <h1 class="noir-page__title">{{ $content->title }}</h1>
        </header>

        @include('themes.noir::partials.fields', ['fields' => $content->fields->whereNotIn('key', $systemFieldKeys)])
    </div>
@endsection
