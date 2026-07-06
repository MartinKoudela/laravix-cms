<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $doc->title }} – {{ __('docs::docs.page_title') }} – {{ $site->name }}</title>
    <style>
        body { font-family: ui-sans-serif, system-ui, sans-serif; margin: 0; color: #18181b; }
        .layout { display: flex; max-width: 1240px; margin: 0 auto; gap: 48px; padding: 48px 24px; }
        aside { width: 240px; flex-shrink: 0; }
        aside h2 { font-size: .75rem; text-transform: uppercase; letter-spacing: .05em; color: #a1a1aa; margin: 1.25rem 0 .5rem; }
        aside a { display: block; padding: 4px 0; font-size: .9rem; color: #52525b; text-decoration: none; }
        aside a:hover, aside a.active { color: #ff0465; }
        .tabs { display: flex; gap: 6px; margin-bottom: 1rem; }
        .tabs a { padding: 6px 12px; font-size: .8rem; font-weight: 600; border-radius: 9999px; background: #f4f4f5; color: #52525b; }
        .tabs a.active { background: #ff0465; color: #fff; }
        main { flex: 1; min-width: 0; }
        main h1 { font-size: 2rem; margin-top: 0; }
        .heading-anchor { margin-left: 8px; color: #d4d4d8; text-decoration: none; font-size: .8em; }
        .heading-anchor:hover { color: #ff0465; }
        .toc { width: 200px; flex-shrink: 0; font-size: .85rem; position: sticky; top: 24px; align-self: flex-start; }
        .toc h2 { font-size: .75rem; text-transform: uppercase; letter-spacing: .05em; color: #a1a1aa; margin: 0 0 .5rem; }
        .toc a { display: block; padding: 3px 0; color: #71717a; text-decoration: none; }
        .toc a:hover { color: #ff0465; }
        .toc .toc-h3 { padding-left: 12px; }
        .back { font-size: .875rem; color: #71717a; text-decoration: none; display: inline-block; margin-bottom: 1rem; }
        .pager { display: flex; justify-content: space-between; gap: 16px; margin-top: 3rem; padding-top: 1.5rem; border-top: 1px solid #e4e4e7; }
        .pager a { text-decoration: none; color: #18181b; font-size: .9rem; max-width: 45%; }
        .pager a:hover { color: #ff0465; }
        .pager .hint { display: block; font-size: .75rem; color: #a1a1aa; }
        .pager .next { margin-left: auto; text-align: right; }
        @media (max-width: 1024px) { .toc { display: none; } }
        @media (max-width: 768px) { .layout { flex-direction: column; } aside { width: auto; } }
    </style>
</head>
<body>
    <div class="layout">
        <aside>
            <a class="back" href="{{ $docsUrl }}">← {{ __('docs::docs.page_title') }}</a>
            @if ($sections->count() > 1)
                <div class="tabs">
                    @foreach ($sections as $s)
                        <a href="{{ $s['url'] }}" @class(['active' => $s['active']])>{{ $s['label'] }}</a>
                    @endforeach
                </div>
            @endif
            @foreach ($grouped as $group)
                <h2>{{ $group['label'] }}</h2>
                @foreach ($group['docs'] as $sibling)
                    <a href="{{ $docUrl($sibling) }}" @class(['active' => $sibling->id === $doc->id])>{{ $sibling->title }}</a>
                @endforeach
            @endforeach
        </aside>
        <main>
            <h1>{{ $doc->title }}</h1>
            {!! $contentHtml !!}
            @if ($prev || $next)
                <div class="pager">
                    @if ($prev)
                        <a href="{{ $docUrl($prev) }}">
                            <span class="hint">← {{ __('docs::docs.prev') }}</span>
                            {{ $prev->title }}
                        </a>
                    @endif
                    @if ($next)
                        <a class="next" href="{{ $docUrl($next) }}">
                            <span class="hint">{{ __('docs::docs.next') }} →</span>
                            {{ $next->title }}
                        </a>
                    @endif
                </div>
            @endif
        </main>
        @if ($toc->isNotEmpty())
            <nav class="toc">
                <h2>{{ __('docs::docs.on_this_page') }}</h2>
                @foreach ($toc as $item)
                    <a href="#{{ $item['id'] }}" @class(['toc-h3' => $item['level'] === 3])>{{ $item['text'] }}</a>
                @endforeach
            </nav>
        @endif
    </div>
</body>
</html>
