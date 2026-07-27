<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    {{-- Homepage --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
    </url>

    {{-- Layanan --}}
    <url>
        <loc>{{ url('/layanan') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
    </url>

    {{-- Detail Layanan --}}
    @foreach ($services as $service)
    <url>
        <loc>{{ url('/layanan/' . $service->slug) }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
        <lastmod>{{ $service->updated_at->toAtomString() }}</lastmod>
    </url>
    @endforeach

    {{-- Tentang Kami --}}
    <url>
        <loc>{{ url('/tentang-kami') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
    </url>

    {{-- Kontak --}}
    <url>
        <loc>{{ url('/kontak') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
    </url>

    {{-- Blog Index --}}
    <url>
        <loc>{{ url('/blog') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
    </url>

    {{-- Artikel Blog --}}
    @foreach ($posts as $post)
    <url>
        <loc>{{ url('/blog/' . $post->slug) }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
        <lastmod>{{ $post->updated_at->toAtomString() }}</lastmod>
    </url>
    @endforeach

</urlset>
