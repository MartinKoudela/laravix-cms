<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('code') – @yield('title')</title>
    <style>
        body { font-family: ui-sans-serif, system-ui, sans-serif; margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #fafafa; color: #18181b; }
        .box { text-align: center; padding: 24px; }
        .code { font-size: 5rem; font-weight: 800; margin: 0; background: linear-gradient(90deg, #e91e63 0%, #ff6b35 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .title { font-size: 1.25rem; font-weight: 600; margin: .5rem 0 1.5rem; }
        a { display: inline-block; padding: 10px 22px; border-radius: 9999px; background: #18181b; color: #fff; text-decoration: none; font-size: .9rem; font-weight: 600; }
    </style>
</head>
<body>
    <div class="box">
        <p class="code">@yield('code')</p>
        <p class="title">@yield('title')</p>
        <a href="/">{{ __('common.back_home') }}</a>
    </div>
</body>
</html>
