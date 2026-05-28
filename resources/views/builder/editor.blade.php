<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Builder — {{ $content->title }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html, body { margin: 0; height: 100%; overflow: hidden; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #1a1a2e; }

        #builder-layout { display: flex; flex-direction: column; height: 100vh; }

        #topbar { display: flex; align-items: center; justify-content: space-between; padding: 0 16px; height: 52px; background: #18181b; border-bottom: 1px solid #27272a; flex-shrink: 0; gap: 12px; }
        #topbar-left { display: flex; align-items: center; gap: 12px; }
        #topbar-right { display: flex; align-items: center; gap: 8px; }
        .tb-back { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 6px; font-size: 13px; color: #a1a1aa; background: transparent; border: 1px solid #3f3f46; cursor: pointer; text-decoration: none; transition: color .15s, border-color .15s; }
        .tb-back:hover { color: #fff; border-color: #71717a; }
        .tb-title { font-size: 13px; font-weight: 600; color: #e4e4e7; max-width: 260px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        #save-status { font-size: 12px; color: #71717a; min-width: 80px; text-align: right; }
        #btn-save { display: inline-flex; align-items: center; gap: 6px; padding: 7px 16px; background: #2563eb; color: #fff; font-size: 13px; font-weight: 600; border: none; border-radius: 6px; cursor: pointer; transition: background .15s; }
        #btn-save:hover { background: #1d4ed8; }
        #btn-save:disabled { opacity: .6; cursor: default; }

        #builder-main { display: flex; flex: 1; overflow: hidden; }

        #gjs { flex: 1; height: 100%; }

        .gjs-top-sidebar, .gjs-pn-commands { display: none !important; }
    </style>
</head>
<body>
<div id="builder-layout">

    <div id="topbar">
        <div id="topbar-left">
            <a href="{{ $backUrl }}" class="tb-back">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                {{ __('builder.back') }}
            </a>
            <span class="tb-title">{{ $content->title }}</span>
        </div>
        <div id="topbar-right">
            <span id="save-status"></span>
            <button id="btn-save">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                {{ __('builder.save') }}
            </button>
        </div>
    </div>

    <div id="builder-main">
        <div
            id="gjs"
            data-project-data="{{ $content->grapesjs_data ?? '' }}"
            data-save-url="{{ route('builder.save', [$site, $content]) }}"
            data-upload-url="{{ route('builder.upload', $site) }}"
            data-csrf="{{ csrf_token() }}"
            data-canvas-css="{{ Vite::asset('resources/css/app.css') }}"
            data-media-items="{{ json_encode($mediaItems) }}"
            data-gjs-blocks="{{ json_encode($gjsBlocks) }}"
            data-trans="{{ json_encode(__('builder')) }}"
        ></div>
    </div>

</div>

@vite('resources/js/builder.js')
</body>
</html>
