@extends('layouts.storefront')

@section('title', "{$product->name} — Specifications & Export Details — Shiv Aaradhana Private Limited")
@section('meta_description', "Export specifications, laboratory parameters, and bulk packaging options for {$product->name}. Exported from Gujarat, India.")

@section('content')

<!-- Breadcrumb Header -->
<div class="bg-stone-100 border-b border-stone-200 py-3 text-xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center flex-wrap gap-2 text-stone-600">
        <a href="{{ route('home') }}" class="hover:text-stone-900">Home</a>
        <span>/</span>
        <a href="{{ route('catalog.index') }}" class="hover:text-stone-900">Catalog</a>
        <span>/</span>
        <a href="{{ route('catalog.category', $product->category->slug) }}" class="hover:text-stone-900">{{ $product->category->name }}</a>
        <span>/</span>
        <a href="{{ route('catalog.product_type', [$product->category->slug, $product->productType->slug]) }}" class="hover:text-stone-900">{{ $product->productType->name }}</a>
        <span>/</span>
        <span class="text-stone-900 font-semibold">{{ $product->name }}</span>
    </div>
</div>

<!-- Product Deep Dive -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            <!-- Left: Gallery & Visuals -->
            <div class="lg:col-span-6 space-y-4" x-data="{ activeImage: '{{ $product->primary_image ?? '/images/products/sesame-seeds.jpg' }}' }">
                <div class="rounded-2xl overflow-hidden border border-stone-200 bg-stone-50 aspect-[4/3] shadow-sm relative">
                    <img :src="activeImage" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    
                    <div class="absolute top-4 left-4 flex gap-2">
                        <span class="text-xs font-bold uppercase tracking-wider px-2.5 py-1 rounded bg-[#091433] text-[#EBD6B4] shadow">
                            {{ $product->category->name }}
                        </span>
                        @if($product->hs_code)
                        <span class="text-xs font-semibold px-2.5 py-1 rounded bg-black/70 text-white backdrop-blur-sm shadow">
                            HS Code: {{ $product->hs_code }}
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Gallery Thumbnails (if multiple images) -->
                @if($product->images->count() > 1)
                <div class="grid grid-cols-4 gap-3">
                    @foreach($product->images as $img)
                    <button type="button" @click="activeImage = '{{ $img->file_path }}'" class="rounded-lg overflow-hidden border-2 aspect-square focus:outline-none transition-all" :class="activeImage === '{{ $img->file_path }}' ? 'border-[#9C451B]' : 'border-stone-200 opacity-70 hover:opacity-100'">
                        <img src="{{ $img->file_path }}" alt="{{ $img->alt_text }}" class="w-full h-full object-cover">
                    </button>
                    @endforeach
                </div>
                @endif

                <!-- Verified Origin & Gateway Callout -->
                <div class="p-5 rounded-xl bg-[#FAF5ED] border border-[#EBD6B4]/50 space-y-2 text-xs text-stone-700">
                    <div class="flex items-center gap-2 font-bold text-[#091433] text-sm">
                        <svg class="w-4 h-4 text-[#9C451B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        <span>Sourcing & Shipping Origin</span>
                    </div>
                    <p class="leading-relaxed">
                        Procured and processed in Gujarat agricultural belts. Dispatches handled via major western container ports: <strong>Mundra, Kandla, and Pipavav</strong>. Pre-shipment fumigation and phytosanitary certification issued for every consignment.
                    </p>
                </div>
            </div>

            <!-- Right: Product Overview & Key Commercials -->
            <div class="lg:col-span-6 space-y-6">
                <div>
                    <span class="text-xs uppercase font-bold tracking-widest text-[#394F3D] block mb-1">
                        {{ $product->productType->name }} &bull; Export Grade
                    </span>
                    <h1 class="text-2xl sm:text-4xl font-heading font-bold text-[#091433] tracking-tight">
                        {{ $product->name }}
                    </h1>
                </div>

                <div class="text-sm text-stone-600 leading-relaxed space-y-3">
                    <p>{{ $product->short_description }}</p>
                    <p class="text-xs sm:text-sm text-stone-500">{{ $product->description }}</p>
                </div>

                <!-- Key Commercial Matrix -->
                <div class="grid grid-cols-2 gap-3 py-4 border-y border-stone-200 text-xs">
                    <div class="p-3 bg-stone-50 rounded-lg">
                        <span class="text-stone-400 block uppercase font-bold tracking-wider text-[10px]">Harvest Season</span>
                        <span class="font-bold text-stone-800 text-sm mt-0.5 block">{{ $product->harvest_season ?? 'Current Crop Available' }}</span>
                    </div>
                    <div class="p-3 bg-stone-50 rounded-lg">
                        <span class="text-stone-400 block uppercase font-bold tracking-wider text-[10px]">Minimum Order Qty</span>
                        <span class="font-bold text-stone-800 text-sm mt-0.5 block">{{ $product->minimum_order_qty ?? '1 x 20ft FCL' }}</span>
                    </div>
                    <div class="p-3 bg-stone-50 rounded-lg">
                        <span class="text-stone-400 block uppercase font-bold tracking-wider text-[10px]">Monthly Capacity</span>
                        <span class="font-bold text-stone-800 text-sm mt-0.5 block">{{ $product->supply_capacity ?? 'Consistent Export Volume' }}</span>
                    </div>
                    <div class="p-3 bg-stone-50 rounded-lg">
                        <span class="text-stone-400 block uppercase font-bold tracking-wider text-[10px]">Geographical Origin</span>
                        <span class="font-bold text-stone-800 text-sm mt-0.5 block">{{ $product->origin }}</span>
                    </div>
                </div>

                @if($product->packaging_options)
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">Standard & Custom Packaging Options:</h4>
                    <p class="text-xs text-stone-600 bg-stone-50 p-3 rounded-lg border border-stone-200">
                        {{ $product->packaging_options }}
                    </p>
                </div>
                @endif

                <!-- CTA triggers -->
                <div class="pt-2 flex flex-wrap gap-4">
                    <button @click="triggerQuote('{{ $product->id }}', '{{ addslashes($product->name) }}')" class="flex-1 min-w-[200px] px-6 py-3.5 rounded-lg bg-[#9C451B] hover:bg-[#b85322] text-white font-bold text-sm shadow-md transition-colors text-center">
                        Request a Quote for this Product &rarr;
                    </button>
                    <a href="https://wa.me/918487878721?text=Hello%20Shiv%20Aaradhana,%20I%20am%20inquiring%20about%20{{ urlencode($product->name) }}" target="_blank" rel="noopener" class="px-5 py-3.5 rounded-lg bg-[#394F3D] hover:bg-[#4a664f] text-white font-medium text-sm transition-colors inline-flex items-center gap-2">
                        <span>WhatsApp Desk</span>
                    </a>
                </div>
            </div>

        </div>

        <!-- Technical Specifications Table (Only populated specifications rendered) -->
        <div class="mt-16 pt-12 border-t border-stone-200">
            <div class="max-w-4xl">
                <span class="text-xs uppercase tracking-widest font-bold text-[#9C451B]">Laboratory Benchmarks</span>
                <h3 class="text-2xl font-heading font-bold text-[#091433] mt-1 mb-6">
                    Technical & Quality Specifications
                </h3>

                @if($specifications->count() > 0)
                <div class="border border-stone-200 rounded-xl overflow-hidden shadow-sm">
                    <table class="w-full text-xs sm:text-sm text-left border-collapse">
                        <thead>
                            <tr class="bg-[#091433] text-white uppercase text-[11px] tracking-wider">
                                <th class="py-3 px-4 font-bold">Tested Parameter / Attribute</th>
                                <th class="py-3 px-4 font-bold">Export Benchmark Specification</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-200">
                            @foreach($specifications as $spec)
                            <tr class="hover:bg-stone-50 transition-colors">
                                <td class="py-3 px-4 font-semibold text-stone-700 bg-stone-50/50 w-1/3">
                                    {{ $spec->attributeDefinition->name ?? 'Attribute' }}
                                </td>
                                <td class="py-3 px-4 text-stone-900 font-medium">
                                    {{ $spec->formatted_value }}
                                </td>
                            </tr>
                            @endforeach
                            <tr class="hover:bg-stone-50 transition-colors">
                                <td class="py-3 px-4 font-semibold text-stone-700 bg-stone-50/50">Export Inspection</td>
                                <td class="py-3 px-4 text-stone-900 font-medium">Pre-shipment inspection (SGS / Bureau Veritas on buyer request)</td>
                            </tr>
                            <tr class="hover:bg-stone-50 transition-colors">
                                <td class="py-3 px-4 font-semibold text-stone-700 bg-stone-50/50">Phytosanitary Certification</td>
                                <td class="py-3 px-4 text-stone-900 font-medium">Official Government of India Plant Quarantine Certificate provided</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-xs text-stone-500 italic">Detailed specification sheet available upon formal inquiry.</p>
                @endif
            </div>
        </div>

        <!-- In-Page Quotation Form -->
        <div class="mt-16 p-8 sm:p-10 rounded-2xl bg-[#FAF5ED] border border-[#EBD6B4] max-w-4xl shadow-sm">
            <div class="mb-6 space-y-1">
                <span class="text-xs font-bold uppercase tracking-widest text-[#9C451B]">Instant Request</span>
                <h3 class="text-xl sm:text-2xl font-heading font-bold text-[#091433]">
                    Inquire Directly for {{ $product->name }}
                </h3>
                <p class="text-xs text-stone-600">
                    Fill in your shipment requirements below to receive a formal quotation from our Rajkot export desk.
                </p>
            </div>

            <form action="{{ route('inquiry.quote') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="text" name="website_hp" value="" style="display:none !important;" tabindex="-1" autocomplete="off">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Your Name *</label>
                        <input type="text" name="full_name" required class="w-full px-3 py-2.5 rounded-lg border border-stone-300 bg-white text-sm focus:ring-2 focus:ring-[#9C451B]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Company / Importer Name</label>
                        <input type="text" name="company_name" class="w-full px-3 py-2.5 rounded-lg border border-stone-300 bg-white text-sm focus:ring-2 focus:ring-[#9C451B]">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Email *</label>
                        <input type="email" name="email" required class="w-full px-3 py-2.5 rounded-lg border border-stone-300 bg-white text-sm focus:ring-2 focus:ring-[#9C451B]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Phone / WhatsApp *</label>
                        <input type="text" name="phone" required class="w-full px-3 py-2.5 rounded-lg border border-stone-300 bg-white text-sm focus:ring-2 focus:ring-[#9C451B]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Destination Country *</label>
                        <input type="text" name="country" required class="w-full px-3 py-2.5 rounded-lg border border-stone-300 bg-white text-sm focus:ring-2 focus:ring-[#9C451B]">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Target Quantity *</label>
                        <input type="text" name="target_quantity" required placeholder="e.g. 1 x 20ft FCL (19 MT)" class="w-full px-3 py-2.5 rounded-lg border border-stone-300 bg-white text-sm focus:ring-2 focus:ring-[#9C451B]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Preferred Discharge Port</label>
                        <input type="text" name="port_of_destination" placeholder="e.g. Jebel Ali, Rotterdam, Chittagong" class="w-full px-3 py-2.5 rounded-lg border border-stone-300 bg-white text-sm focus:ring-2 focus:ring-[#9C451B]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Message & Specific Requirements *</label>
                    <textarea name="message" rows="3" required placeholder="Specify packaging (e.g. 25kg PP bags), desired FOB/CIF basis, target delivery month..." class="w-full px-3 py-2.5 rounded-lg border border-stone-300 bg-white text-sm focus:ring-2 focus:ring-[#9C451B]"></textarea>
                </div>

                <button type="submit" class="px-8 py-3 rounded-lg bg-[#091433] hover:bg-[#394F3D] text-white font-bold text-sm shadow-md transition-colors">
                    Send Formal Quotation Request &rarr;
                </button>
            </form>
        </div>

        <!-- Intelligent Recommendations Section -->
        @if($recommendations->count() > 0)
        <div class="mt-20 pt-12 border-t border-stone-200">
            <div class="mb-8">
                <span class="text-xs uppercase tracking-widest font-bold text-[#9C451B]">Complementary & Related</span>
                <h3 class="text-2xl font-heading font-bold text-[#091433] mt-1">
                    Frequently Sourced With This Commodity
                </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($recommendations as $rec)
                <div class="group bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="h-44 overflow-hidden relative bg-stone-100">
                            <img src="{{ $rec->primary_image ?? '/images/products/sesame-seeds.jpg' }}" alt="{{ $rec->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            
                            @if(!empty($rec->recommendation_reason))
                            <span class="absolute bottom-2 left-2 text-[10px] font-bold px-2 py-0.5 rounded bg-[#091433]/90 text-[#EBD6B4] backdrop-blur-sm">
                                {{ $rec->recommendation_reason }}
                            </span>
                            @endif
                        </div>

                        <div class="p-4 space-y-2">
                            <h4 class="font-heading font-bold text-sm text-[#091433] group-hover:text-[#9C451B] transition-colors line-clamp-1">
                                <a href="{{ route('catalog.product', $rec->slug) }}">
                                    {{ $rec->name }}
                                </a>
                            </h4>
                            <p class="text-xs text-stone-500 line-clamp-2">
                                {{ $rec->short_description }}
                            </p>
                        </div>
                    </div>

                    <div class="p-4 pt-0 border-t border-stone-100 mt-2 flex items-center justify-between">
                        <a href="{{ route('catalog.product', $rec->slug) }}" class="text-xs font-bold text-[#091433] hover:text-[#9C451B]">
                            View Specs &rarr;
                        </a>
                        <button @click="triggerQuote('{{ $rec->id }}', '{{ addslashes($rec->name) }}')" class="text-xs px-2.5 py-1 rounded bg-stone-100 hover:bg-[#9C451B] hover:text-white font-semibold transition-colors">
                            Quote
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</section>

@endsection
