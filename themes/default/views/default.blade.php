@extends('themes.default::layouts.app')

@section('content')
    <h1>{{ $content->title }}</h1>

    @foreach ($content->fields as $field)
        <div>
            <strong>{{ $field->key }}:</strong>
            {{ $field->value }}
        </div>
    @endforeach
@endsection