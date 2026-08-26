<x-SimpleBlog::layouts.app>
    <x-slot:title>Blog - Výpis článků</x-slot:title>

    <h1>Nejnovější články</h1>

    <div class="posts-grid">
        @foreach($posts as $post)
            <article class="post-card">
                <h2>{{ $post->title }}</h2>
                <p>{{ $post->excerpt }}</p>
                <a href="/post/{{ $post->slug }}">Číst dále</a>
            </article>
        @endforeach
    </div>
</x-SimpleBlog::layouts.app>
