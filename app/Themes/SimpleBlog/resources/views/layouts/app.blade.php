<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Blog' }}</title>
</head>
<body>
    <x-SimpleBlog::header />

    <main class="container">
        {{ $slot }}
    </main>

    <x-SimpleBlog::footer />
</body>
</html>
