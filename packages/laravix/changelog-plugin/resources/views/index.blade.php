<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('changelog::changelog.page_title') }} – {{ $site->name }}</title>
    <style>
        body { font-family: ui-sans-serif, system-ui, sans-serif; max-width: 720px; margin: 0 auto; padding: 48px 24px; color: #18181b; }
        h1 { font-size: 2rem; margin-bottom: 2rem; }
        .release { margin-bottom: 2.5rem; padding-bottom: 2rem; border-bottom: 1px solid #e4e4e7; }
        .release-header { display: flex; align-items: baseline; gap: 12px; margin-bottom: .75rem; }
        .version { font-size: 1.25rem; font-weight: 700; }
        .date { color: #71717a; font-size: .875rem; }
        .title { font-size: 1rem; color: #3f3f46; margin-bottom: .75rem; }
        ul { padding-left: 0; list-style: none; }
        li { display: flex; gap: 10px; padding: 4px 0; align-items: baseline; }
        .type { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; padding: 2px 8px; border-radius: 9999px; flex-shrink: 0; }
        .type-added { background: #dcfce7; color: #166534; }
        .type-changed { background: #dbeafe; color: #1e40af; }
        .type-fixed { background: #fef9c3; color: #854d0e; }
        .type-removed { background: #fee2e2; color: #991b1b; }
        .type-security { background: #f3e8ff; color: #6b21a8; }
    </style>
</head>
<body>
    <h1>{{ __('changelog::changelog.page_title') }}</h1>

    @forelse ($releases as $release)
        <div class="release">
            <div class="release-header">
                <span class="version">v{{ $release->version }}</span>
                <span class="date">{{ $release->released_at->format('j. n. Y') }}</span>
            </div>
            @if ($release->localizedTitle())
                <p class="title">{{ $release->localizedTitle() }}</p>
            @endif
            <ul>
                @foreach ($release->items as $item)
                    <li>
                        <span class="type type-{{ $item->type }}">{{ __('changelog::changelog.types.'.$item->type) }}</span>
                        <span>{{ $item->localizedText() }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @empty
        <p>{{ __('changelog::changelog.empty') }}</p>
    @endforelse
</body>
</html>
