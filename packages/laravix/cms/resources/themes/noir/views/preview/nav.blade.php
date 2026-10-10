<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ \Laravix\Cms\Laravix::asset('app.css') }}">
    @if ($themeStylesheet = \Laravix\Cms\Laravix::themeAsset('app.css', 'noir'))
        <link rel="stylesheet" href="{{ $themeStylesheet }}">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    @include('themes.noir::partials.nav-design')

    <style>
        html, body { margin: 0; padding: 0; overflow: hidden; }
    </style>
</head>
<body class="noir">

@if (request()->query('part', 'header') === 'header')
    @include('themes.noir::partials.header', ['preview' => true])
@else
    @include('themes.noir::partials.footer')
@endif

<script>
    function sendHeight() {
        var el = document.body.firstElementChild;
        var h = el ? Math.ceil(el.getBoundingClientRect().height) : document.body.scrollHeight;
        window.parent.postMessage({ type: 'nav-preview-height', height: h }, '*');
    }
    window.addEventListener('load', sendHeight);
    if (document.fonts) { document.fonts.ready.then(sendHeight); }
    setTimeout(sendHeight, 400);
</script>

</body>
</html>
