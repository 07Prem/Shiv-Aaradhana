{!! '<'.'?'.'xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Static Primary Pages -->
    <url>
        <loc>{{ route('home') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('catalog.index') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ route('about') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('contact') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('legal.privacy') }}</loc>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>
    <url>
        <loc>{{ route('legal.terms') }}</loc>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>

    <!-- Dynamic Category Pages -->
    @foreach($categories as $cat)
    <url>
        <loc>{{ route('catalog.category', $cat->slug) }}</loc>
        <lastmod>{{ $cat->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

    <!-- Dynamic Product Type Pages -->
    @foreach($productTypes as $pt)
    <url>
        <loc>{{ route('catalog.product_type', [$pt->category->slug, $pt->slug]) }}</loc>
        <lastmod>{{ $pt->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    <!-- Dynamic Product Details Pages -->
    @foreach($products as $prod)
    <url>
        <loc>{{ route('catalog.product', $prod->slug) }}</loc>
        <lastmod>{{ $prod->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    @endforeach
</urlset>
