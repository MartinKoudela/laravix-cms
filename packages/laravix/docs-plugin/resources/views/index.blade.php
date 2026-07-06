<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('docs::docs.page_title') }} – {{ $site->name }}</title>
    <style>
        body { font-family: ui-sans-serif, system-ui, sans-serif; max-width: 860px; margin: 0 auto; padding: 48px 24px; color: #18181b; }
        h1 { font-size: 2rem; margin-bottom: 1rem; }
        .search { position: relative; margin-bottom: 2.5rem; }
        .search input { width: 100%; padding: 10px 14px; font-size: 1rem; border: 1px solid #d4d4d8; border-radius: 10px; }
        .search-results { position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid #e4e4e7; border-radius: 10px; margin-top: 4px; box-shadow: 0 10px 30px rgba(0,0,0,.08); z-index: 10; }
        .search-results a { display: block; padding: 10px 14px; text-decoration: none; color: inherit; }
        .search-results a:hover { background: #fafafa; }
        h2 { font-size: 1.3rem; margin: 2.5rem 0 .5rem; }
        h3 { font-size: .8rem; text-transform: uppercase; letter-spacing: .05em; color: #71717a; margin: 1.25rem 0 .5rem; }
        ul { list-style: none; padding: 0; }
        li a { display: block; padding: 8px 0; color: #18181b; text-decoration: none; border-bottom: 1px solid #f4f4f5; }
        li a:hover { color: #ff0465; }
    </style>
</head>
<body>
    <h1>{{ __('docs::docs.page_title') }}</h1>

    <div class="search">
        <input type="text" id="docs-search" placeholder="{{ __('docs::docs.search_placeholder') }}" autocomplete="off">
        <div class="search-results" id="docs-search-results" hidden></div>
    </div>

    @foreach ($sections as $section)
        <h2><a href="{{ $section['url'] }}" style="color:inherit;text-decoration:none">{{ $section['label'] }}</a></h2>
        @foreach ($section['groups'] as $group)
            <h3>{{ $group['label'] }}</h3>
            <ul>
                @foreach ($group['docs'] as $doc)
                    <li><a href="{{ $docUrl($doc) }}">{{ $doc->title }}</a></li>
                @endforeach
            </ul>
        @endforeach
    @endforeach

    @if ($ungrouped->isNotEmpty())
        <h2>{{ __('docs::docs.uncategorized') }}</h2>
        <ul>
            @foreach ($ungrouped as $doc)
                <li><a href="{{ $docUrl($doc) }}">{{ $doc->title }}</a></li>
            @endforeach
        </ul>
    @endif

    <script>
        const input = document.getElementById('docs-search');
        const results = document.getElementById('docs-search-results');
        let timer = null;

        input.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(async () => {
                const q = input.value.trim();
                if (q.length < 2) { results.hidden = true; return; }
                const res = await fetch(`{{ $docsUrl }}/search?q=${encodeURIComponent(q)}`);
                const json = await res.json();
                results.innerHTML = json.data.map(d => `<a href="${d.url}">${d.title}</a>`).join('');
                results.hidden = json.data.length === 0;
            }, 250);
        });

        document.addEventListener('click', (e) => {
            if (!e.target.closest('.search')) results.hidden = true;
        });
    </script>
</body>
</html>
