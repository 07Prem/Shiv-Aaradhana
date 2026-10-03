@extends('layouts.storefront')

@section('title', "{$productType->name} — {$category->name} — Shiv Aaradhana Private Limited")
@section('meta_description', "Export-grade {$productType->name} from Gujarat, India. Compliant with international B2B buyer specifications.")

@section('content')

<!-- Banner -->
<section class="bg-[#091433] text-white py-12 border-b border-stone-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-xs text-stone-400 mb-2 flex items-center gap-1.5">
            <a href="{{ route('home') }}" class="hover:text-white">Home</a>
            <span>/</span>
            <a href="{{ route('catalog.index') }}" class="hover:text-white">Catalog</a>
            <span>/</span>
            <a href="{{ route('catalog.category', $category->slug) }}" class="hover:text-white">{{ $category->name }}</a>
            <span>/</span>
            <span class="text-[#EBD6B4]">{{ $productType->name }}</span>
        </nav>

        <div class="mt-4 max-w-3xl space-y-2">
            <span class="text-xs uppercase tracking-widest font-bold text-[#EBD6B4] block">{{ $category->name }} Line</span>
            <h1 class="text-3xl sm:text-5xl font-heading font-bold text-white tracking-tight">
                {{ $productType->name }}
            </h1>
            <p class="text-xs sm:text-sm text-stone-300 leading-relaxed">
                {{ $productType->description }}
            </p>
        </div>
    </div>
</section>

<!-- Scoped Products -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8 pb-4 border-b border-stone-200">
            <span class="text-xs text-stone-500">Showing <strong>{{ $products->total() }}</strong> commodities in this line</span>
            <a href="{{ route('catalog.category', $category->slug) }}" class="text-xs text-[#9C451B] font-bold hover:underline">
                &larr; Back to all {{ $category->name }}
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($products as $product)
            <div class="group bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="h-56 overflow-hidden relative bg-stone-100">
                        <img src="{{ $product->primary_image ?? '/images/products/sesame-seeds.jpg' }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @if($product->hs_code)
                        <span class="absolute top-3 left-3 text-[10px] font-medium px-2 py-0.5 rounded bg-black/70 text-[#EBD6B4] backdrop-blur-sm">
                            HS: {{ $product->hs_code }}
                        </span>
                        @endif
                    </div>

                    <div class="p-6 space-y-3">
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
    </div>
</section>

@endsection
