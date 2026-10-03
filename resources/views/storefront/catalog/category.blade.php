@extends('layouts.storefront')

@section('title', "{$category->name} — Shiv Aaradhana Private Limited")
@section('meta_description', "Explore {$category->name} exported directly from Gujarat, India. Full product specifications, HS codes and FOB/CIF quotations.")

@section('content')

<!-- Category Banner -->
<section class="bg-[#091433] text-white py-12 border-b border-stone-800 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <nav class="text-xs text-stone-400 mb-2 flex items-center gap-1.5">
            <a href="{{ route('home') }}" class="hover:text-white">Home</a>
            <span>/</span>
            <a href="{{ route('catalog.index') }}" class="hover:text-white">Catalog</a>
            <span>/</span>
            <span class="text-[#EBD6B4]">{{ $category->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center mt-4">
            <div class="lg:col-span-8 space-y-3">
                <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded bg-[#394F3D] text-[11px] font-bold text-[#EBD6B4] uppercase tracking-wider">
                    Commodity Category &bull; {{ $products->total() }} Products
                </div>
                <h1 class="text-3xl sm:text-5xl font-heading font-bold text-white tracking-tight">
                    {{ $category->name }}
                </h1>
                <p class="text-xs sm:text-sm text-stone-300 leading-relaxed max-w-2xl">
                    {{ $category->description }}
                </p>
            </div>
            
            <div class="lg:col-span-4 flex justify-end">
                <button @click="triggerQuote('', '')" class="px-6 py-3 rounded-lg bg-[#9C451B] hover:bg-[#b85322] text-white font-semibold text-xs shadow-lg transition-colors">
                    Request Category Quotation &rarr;
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Sub Product Types Navigation -->
@if($productTypes->count() > 0)
<section class="bg-[#FAF5ED] py-4 border-b border-stone-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-2 overflow-x-auto text-xs py-1">
            <span class="text-stone-500 font-bold uppercase tracking-wider text-[11px] whitespace-nowrap">Product Lines:</span>
            <a href="{{ route('catalog.category', $category->slug) }}" class="px-3 py-1.5 rounded-lg font-bold transition-colors whitespace-nowrap {{ empty($criteria['type']) ? 'bg-[#091433] text-[#EBD6B4]' : 'bg-white text-stone-700 hover:bg-stone-200' }}">
                All {{ $category->name }}
            </a>
            @foreach($productTypes as $pt)
            <a href="{{ route('catalog.product_type', [$category->slug, $pt->slug]) }}" class="px-3 py-1.5 rounded-lg font-semibold bg-white text-stone-700 hover:bg-stone-200 transition-colors whitespace-nowrap">
                {{ $pt->name }}
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Category Products Grid -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex items-center justify-between mb-8 pb-4 border-b border-stone-200">
            <span class="text-xs text-stone-500">Showing <strong>{{ $products->total() }}</strong> verified export commodities</span>
            <a href="{{ route('catalog.index') }}" class="text-xs text-[#9C451B] font-bold hover:underline">
                &larr; View All Catalog Categories
            </a>
        </div>

        @if($products->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($products as $product)
            <div class="group bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="h-56 overflow-hidden relative bg-stone-100">
                        <img src="{{ $product->primary_image ?? '/images/products/sesame-seeds.jpg' }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute top-3 left-3 flex gap-1">
                            @if($product->hs_code)
                            <span class="text-[10px] font-medium px-2 py-0.5 rounded bg-black/70 text-[#EBD6B4] backdrop-blur-sm">
                                HS: {{ $product->hs_code }}
                            </span>
                            @endif
                        </div>
                    </div>

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
                        <div class="pt-2 flex flex-wrap gap-2 text-[11px] text-stone-700">
                            <span class="px-2 py-1 bg-stone-100 rounded">
                                <strong>Origin:</strong> {{ $product->origin }}
                            </span>
                            @if($product->minimum_order_qty)
                            <span class="px-2 py-1 bg-stone-100 rounded">
                                <strong>MOQ:</strong> {{ $product->minimum_order_qty }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="p-6 pt-0 flex items-center justify-between border-t border-stone-100 mt-4">
                    <a href="{{ route('catalog.product', $product->slug) }}" class="text-xs font-bold text-[#091433] hover:text-[#9C451B] transition-colors">
                        Spec Sheet &rarr;
                    </a>
                    <button @click="triggerQuote('{{ $product->id }}', '{{ addslashes($product->name) }}')" class="px-3.5 py-1.5 rounded-lg bg-[#091433] hover:bg-[#9C451B] text-white text-xs font-semibold shadow transition-colors">
                        Request Quote
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-12">
            {{ $products->links() }}
        </div>
        @else
        <div class="text-center py-16">
            <p class="text-stone-500 text-sm">No products currently active in this category.</p>
        </div>
        @endif

    </div>
</section>

@endsection
