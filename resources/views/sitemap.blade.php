<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($contents as $content)
    <url>
        <loc>{{ url($content->is_homepage ? '/' : '/'.$content->slug) }}</loc>
        <lastmod>{{ $content->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>{{ $content->is_homepage ? '1.0' : '0.8' }}</priority>
    </url>
    @endforeach
</urlset>