<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content->title ?? $site->name }} – {{ $site->name }}</title>
</head>
<body>
    <header>
        <a href="/">{{ $site->name }}</a>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        &copy; {{ date('Y') }} {{ $site->name }}
    </footer>
</body>
</html>