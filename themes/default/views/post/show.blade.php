@extends('themes.default::layouts.app')

@section('content')
    <article>
        <h1>{{ $content->title }}</h1>

        @if ($content->published_at)
            <time datetime="{{ $content->published_at->toIso8601String() }}">
                {{ $content->published_at->format('j. n. Y') }}
            </time>
        @endif

        @foreach ($content->fields as $field)
            <div>
                <strong>{{ $field->key }}:</strong>
                {{ $field->value }}
            </div>
        @endforeach
    </article>
@endsection