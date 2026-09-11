<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $site->name }} – {{ __('laravix::placeholder.title') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; margin: 0; min-height: 100vh; display: flex; flex-direction: column; background: #fff; color: #0f172a; -webkit-font-smoothing: antialiased; overflow-x: hidden; }
        main { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 24px; text-align: center; }
        .mark { width: 72px; height: auto; margin-bottom: 32px; }
        h1 { font-size: clamp(2rem, 5vw, 3rem); font-weight: 700; letter-spacing: -.03em; line-height: 1.1; margin: 0 0 16px; }
        h1 span { background: linear-gradient(135deg, #ff0465 0%, #ff6602 100%); -webkit-background-clip: text; background-clip: text; color: transparent; }
        p { margin: 0; color: #475569; font-size: 1.125rem; line-height: 1.6; max-width: 34rem; }
        .hint { margin-top: 40px; width: 100%; max-width: 34rem; text-align: left; padding: 20px 24px; border: 1px solid #f1f5f9; border-radius: 20px; background: linear-gradient(to top, #fff, #f8fafc); }
        .hint strong { display: block; font-size: .875rem; color: #0f172a; margin-bottom: 12px; line-height: 1.5; }
        .hint a { display: inline-block; padding: 10px 18px; border-radius: 9999px; background: linear-gradient(135deg, #ff0465 0%, #ff6602 100%); color: #fff; font-size: .875rem; font-weight: 700; text-decoration: none; }
        .hint a:hover { opacity: .9; }
        footer { display: flex; justify-content: center; align-items: center; gap: 8px; padding: 28px 24px; font-size: .8125rem; color: #64748b; }
        footer a { color: #0f172a; font-weight: 600; text-decoration: none; }
        footer a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <main>
        <img class="mark" src="{{ asset('logo-small.svg') }}" alt="" aria-hidden="true">
        <h1><span>{{ $site->name }}</span></h1>
        <p>{{ __('laravix::placeholder.body') }}</p>

        @if ($showHint)
            <div class="hint">
                <strong>{{ __('laravix::placeholder.hints.'.$reason->value) }}</strong>
                @unless ($reason === \Laravix\Cms\Enums\PlaceholderReason::Headless)
                    <a href="{{ url('/admin/'.$site->id.'/contents') }}">{{ __('laravix::placeholder.open_admin') }}</a>
                @endunless
            </div>
        @endif
    </main>

    <footer>
        {{ __('laravix::placeholder.powered_by') }} <a href="https://laravix.com" rel="noopener">Laravix</a>
    </footer>
</body>
</html>