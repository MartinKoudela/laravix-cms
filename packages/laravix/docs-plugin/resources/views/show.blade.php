<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $doc->title }} – {{ __('docs::docs.page_title') }} – {{ $site->name }}</title>
    <style>
        body { font-family: ui-sans-serif, system-ui, sans-serif; margin: 0; color: #18181b; }
        .layout { display: flex; max-width: 1100px; margin: 0 auto; gap: 48px; padding: 48px 24px; }
        aside { width: 240px; flex-shrink: 0; }
        aside h2 { font-size: .75rem; text-transform: uppercase; letter-spacing: .05em; color: #a1a1aa; margin: 1.25rem 0 .5rem; }
        aside a { display: block; padding: 4px 0; font-size: .9rem; color: #52525b; text-decoration: none; }
        aside a:hover, aside a.active { color: #ff0465; }
        main { flex: 1; min-width: 0; }
        main h1 { font-size: 2rem; margin-top: 0; }
        .back { font-size: .875rem; color: #71717a; text-decoration: none; display: inline-block; margin-bottom: 1rem; }
        @media (max-width: 768px) { .layout { flex-direction: column; } aside { width: auto; } }
    </style>
</head>
<body>
    <div class="layout">
        <aside>
            <a class="back" href="{{ $docsUrl }}">← {{ __('docs::docs.page_title') }}</a>
            @foreach ($grouped as $category => $docs)
                <h2>{{ $category }}</h2>
                @foreach ($docs as $sibling)
                    <a href="{{ $docsUrl }}/{{ $sibling->slug }}" @class(['active' => $sibling->id === $doc->id])>{{ $sibling->title }}</a>
                @endforeach
            @endforeach
        </aside>
        <main>
            <h1>{{ $doc->title }}</h1>
            {!! $grapesjsHtml ?? '' !!}
        </main>
    </div>
</body>
</html>
