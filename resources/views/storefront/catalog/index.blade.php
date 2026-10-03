@extends('layouts.storefront')

@section('title', 'Export Product Catalog — Shiv Aaradhana Private Limited')
@section('meta_description', 'Explore our comprehensive international B2B catalog of agricultural commodities, spices, cotton textiles, and dehydrated foods.')

@section('content')

<!-- Header Banner -->
<section class="bg-[#091433] text-white py-12 border-b border-stone-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <nav class="text-xs text-stone-400 mb-2 flex items-center gap-1.5">
                    <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                    <span>/</span>
                    <span class="text-[#EBD6B4]">Product Catalog</span>
                </nav>
                <h1 class="text-3xl sm:text-4xl font-heading font-bold text-white tracking-tight">
                    International Export Catalog
                </h1>
                <p class="text-xs sm:text-sm text-stone-300 mt-1 max-w-2xl">
                    Discover verified Indian agro commodities, sortex-grade spices, and industrial textiles ready for international sea freight.
                </p>
            </div>

            <!-- Search Bar Form -->
            <form action="{{ route('catalog.index') }}" method="GET" class="w-full md:w-80">
                <div class="relative">
                    <input type="text" name="q" value="{{ $criteria['q'] ?? '' }}" placeholder="Search products, HS code..." class="w-full pl-10 pr-4 py-2.5 rounded-lg bg-white/10 border border-white/20 text-white placeholder-stone-400 text-sm focus:outline-none focus:ring-2 focus:ring-[#9C451B]">
                    <svg class="w-4 h-4 text-stone-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    @if(!empty($criteria['category']))
                        <input type="hidden" name="category" value="{{ $criteria['category'] }}">
                    @endif
                    @if(!empty($criteria['type']))
                        <input type="hidden" name="type" value="{{ $criteria['type'] }}">
                    @endif
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Main Filter & Grid Section -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Filter Tabs / Pills -->
        <div class="flex flex-wrap items-center justify-between gap-4 pb-8 border-b border-stone-200">
            <!-- Category Pills -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('catalog.index', array_filter(['q' => $criteria['q']])) }}" class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-colors {{ empty($criteria['category']) ? 'bg-[#091433] text-[#EBD6B4]' : 'bg-stone-100 text-stone-700 hover:bg-stone-200' }}">
                    All Commodities ({{ $products->total() }})
                </a>
                @foreach($categories as $cat)
                <a href="{{ route('catalog.index', array_merge($criteria, ['category' => $cat->slug, 'type' => null])) }}" class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-colors {{ ($criteria['category'] ?? '') === $cat->slug ? 'bg-[#091433] text-[#EBD6B4]' : 'bg-stone-100 text-stone-700 hover:bg-stone-200' }}">
                    {{ $cat->name }} ({{ $cat->products_count }})
                </a>
                @endforeach
            </div>

            <!-- Sort Controls -->
            <form action="{{ route('catalog.index') }}" method="GET" class="flex items-center gap-2">
                @if(!empty($criteria['q'])) <input type="hidden" name="q" value="{{ $criteria['q'] }}"> @endif
                @if(!empty($criteria['category'])) <input type="hidden" name="category" value="{{ $criteria['category'] }}"> @endif
                @if(!empty($criteria['type'])) <input type="hidden" name="type" value="{{ $criteria['type'] }}"> @endif

                <label for="sortSelect" class="text-xs text-stone-500 font-medium">Sort by:</label>
                <select id="sortSelect" name="sort" onchange="this.form.submit()" class="text-xs px-3 py-1.5 rounded-lg border border-stone-300 bg-white text-stone-700 focus:outline-none focus:ring-1 focus:ring-[#9C451B]">
                    <option value="newest" {{ ($criteria['sort'] ?? '') === 'newest' ? 'selected' : '' }}>Newest Additions</option>
                    <option value="name_asc" {{ ($criteria['sort'] ?? '') === 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                    <option value="name_desc" {{ ($criteria['sort'] ?? '') === 'name_desc' ? 'selected' : '' }}>Name: Z to A</option>
                </select>
            </form>
        </div>

        <!-- Sub-Product Type Pills if Category is Selected -->
        @if($selectedCategory && $productTypes->count() > 0)
        <div class="py-4 flex items-center gap-2 overflow-x-auto text-xs">
            <span class="text-stone-400 font-medium">Filter Line:</span>
            <a href="{{ route('catalog.index', ['category' => $selectedCategory->slug]) }}" class="px-3 py-1 rounded-md border {{ empty($criteria['type']) ? 'border-[#9C451B] bg-[#9C451B]/10 text-[#9C451B] font-bold' : 'border-stone-200 text-stone-600' }}">
                All {{ $selectedCategory->name }}
            </a>
            @foreach($productTypes as $pt)
            <a href="{{ route('catalog.index', ['category' => $selectedCategory->slug, 'type' => $pt->slug]) }}" class="px-3 py-1 rounded-md border {{ ($criteria['type'] ?? '') === $pt->slug ? 'border-[#9C451B] bg-[#9C451B]/10 text-[#9C451B] font-bold' : 'border-stone-200 text-stone-600' }}">
                {{ $pt->name }}
            </a>
            @endforeach
        </div>
        @endif

        <!-- Active Filter Indicator -->
        @if(!empty($criteria['q']) || !empty($criteria['category']) || !empty($criteria['type']))
        <div class="mt-4 p-3 rounded-lg bg-stone-50 border border-stone-200 flex items-center justify-between text-xs text-stone-600">
            <div>
                Showing results for: 
                @if(!empty($criteria['q'])) <span class="font-bold text-stone-900">"{{ $criteria['q'] }}"</span> @endif
                @if(!empty($criteria['category'])) <span class="ml-1 px-2 py-0.5 rounded bg-stone-200 font-semibold">{{ $criteria['category'] }}</span> @endif
                @if(!empty($criteria['type'])) <span class="ml-1 px-2 py-0.5 rounded bg-stone-200 font-semibold">{{ $criteria['type'] }}</span> @endif
            </div>
            <a href="{{ route('catalog.index') }}" class="text-[#9C451B] font-bold hover:underline">
                Clear Filters &times;
            </a>
        </div>
        @endif

        <!-- Product Cards Grid -->
        @if($products->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">
            @foreach($products as $product)
            <div class="group bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                <div>
                    <!-- Image -->
                    <div class="h-56 overflow-hidden relative bg-stone-100">
                        <img src="{{ $product->primary_image ?? '/images/products/sesame-seeds.jpg' }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        
                        <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-[#091433] text-[#EBD6B4]">
                                {{ $product->category->name }}
                            </span>
                            @if($product->hs_code)
                                <span class="text-[10px] font-medium px-2 py-0.5 rounded bg-black/70 text-white backdrop-blur-sm">
                                    HS: {{ $product->hs_code }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="p-6 space-y-3">
                        <span class="text-[11px] font-semibold text-[#394F3D] block">
                            {{ $product->productType?->name }}
                        </span>

                        <h3 class="font-heading font-bold text-lg text-[#091433] group-hover:text-[#9C451B] transition-colors line-clamp-1">
                            <a href="{{ route('catalog.product', $product->slug) }}">
                                {{ $product->name }}
                            </a>
                        </h3>

                        <p class="text-xs text-stone-600 line-clamp-3 leading-relaxed">
                            {{ $product->short_description }}
                        </p>

                        <div class="pt-2 flex flex-wrap gap-2 text-[11px]">
                            <span class="px-2 py-1 bg-stone-100 rounded text-stone-700">
                                <strong>Origin:</strong> {{ $product->origin }}
                            </span>
                            @if($product->minimum_order_qty)
                            <span class="px-2 py-1 bg-stone-100 rounded text-stone-700">
                                <strong>MOQ:</strong> {{ $product->minimum_order_qty }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Links -->
                <div class="p-6 pt-0 flex items-center justify-between border-t border-stone-100 mt-4">
                    <a href="{{ route('catalog.product', $product->slug) }}" class="text-xs font-bold text-[#091433] hover:text-[#9C451B] transition-colors">
                        Full Specifications &rarr;
                    </a>
                    <button @click="triggerQuote('{{ $product->id }}', '{{ addslashes($product->name) }}')" class="px-3.5 py-1.5 rounded-lg bg-[#9C451B] hover:bg-[#b85322] text-white text-xs font-semibold shadow transition-colors">
                        Request Quote
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $products->links() }}
        </div>

        @else
        <!-- Empty State -->
        <div class="text-center py-20 px-4 space-y-4">
            <div class="w-16 h-16 rounded-full bg-stone-100 text-stone-400 mx-auto flex items-center justify-center text-2xl font-bold">
                ?
            </div>
            <h3 class="text-xl font-heading font-bold text-stone-800">No Matching Export Products Found</h3>
            <p class="text-xs sm:text-sm text-stone-500 max-w-md mx-auto">
                We could not find products matching your query. Please adjust your keywords or browse all categories.
            </p>
            <div class="pt-2">
                <a href="{{ route('catalog.index') }}" class="px-5 py-2.5 rounded-lg bg-[#091433] text-[#EBD6B4] text-xs font-semibold hover:bg-[#394F3D] transition-colors inline-block">
                    Reset Catalog Filters
                </a>
            </div>
        </div>
        @endif

    </div>
</section>

@endsection
