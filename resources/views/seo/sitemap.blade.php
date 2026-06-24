<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ route('home') }}</loc>
        <priority>1.0</priority>
        <changefreq>weekly</changefreq>
    </url>
    <url>
        <loc>{{ route('catalog') }}</loc>
        <priority>0.9</priority>
        <changefreq>daily</changefreq>
    </url>
    <url>
        <loc>{{ route('primerka') }}</loc>
        <priority>0.8</priority>
        <changefreq>weekly</changefreq>
    </url>
    <url>
        <loc>{{ route('works') }}</loc>
        <priority>0.8</priority>
        <changefreq>weekly</changefreq>
    </url>
    <url>
        <loc>{{ route('services') }}</loc>
        <priority>0.7</priority>
        <changefreq>monthly</changefreq>
    </url>
    <url>
        <loc>{{ route('contacts') }}</loc>
        <priority>0.6</priority>
        <changefreq>monthly</changefreq>
    </url>

    @foreach($images as $image)
        <url>
            <loc>{{ route('catalog.show', [$image->category->slug, $image->id]) }}</loc>
            <priority>0.5</priority>
        </url>
    @endforeach

    @foreach($works as $work)
        <url>
            <loc>{{ route('works') }}/{{ $work->id }}</loc>
            <priority>0.5</priority>
        </url>
    @endforeach

    @foreach($services as $service)
        <url>
            <loc>{{ route('services') }}/{{ $service->id }}</loc>
            <priority>0.5</priority>
        </url>
    @endforeach
</urlset>
