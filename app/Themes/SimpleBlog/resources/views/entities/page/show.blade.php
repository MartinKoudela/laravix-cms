<x-SimpleBlog::layouts.app>
    <x-slot:title>{{ $post->title }}</x-slot:title>

    <article class="post-detail">
        <header>
            <h1>{{ $post->title }}</h1>
            <small>Publikováno: {{ $post->created_at }}</small>
        </header>

        <div class="content">
            {!! $post->content !!}
        </div>
    </article>
</x-SimpleBlog::layouts.app>
