@extends('layouts.storefront')

@section('title', 'Shiv Aaradhana Private Limited — From Indian Roots to Global Markets')
@section('meta_description', 'Discover agricultural produce, spices, textiles and export products sourced with care and presented for international B2B buyers from Gujarat, India.')

@section('content')

<!-- Hero Section -->
<section class="relative bg-[#091433] text-white pt-16 pb-24 md:pt-24 md:pb-32 overflow-hidden">
    <!-- Atmospheric subtle background glow -->
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-[#394F3D]/40 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-[#9C451B]/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left Hero Content -->
            <div class="lg:col-span-7 space-y-6 hero-animate">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#394F3D]/60 border border-[#EBD6B4]/30 text-xs text-[#EBD6B4] font-medium tracking-wide">
                    <span class="w-2 h-2 rounded-full bg-[#EBD6B4] animate-pulse"></span>
                    {{ $hero->payload['badge'] ?? '5th-Generation Agricultural Heritage — Rajkot, Gujarat' }}
                </div>

                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-heading font-extrabold text-white tracking-tight leading-[1.15]">
                    {{ $hero->title ?? 'From Indian Roots to Global Markets.' }}
                </h1>

                <p class="text-base sm:text-lg text-stone-300 font-light leading-relaxed max-w-2xl">
                    {{ $hero->content ?? 'Discover agricultural produce, spices, textiles and diverse export products sourced with care and presented for international buyers.' }}
                </p>

                <!-- Actions -->
                <div class="pt-4 flex flex-wrap items-center gap-4">
                    <a href="{{ route('catalog.index') }}" class="px-7 py-3.5 rounded-lg bg-[#9C451B] hover:bg-[#b85322] text-white font-medium text-sm shadow-xl shadow-black/30 transition-all transform hover:-translate-y-0.5">
                        {{ $hero->payload['primary_cta_text'] ?? 'Explore Our Products' }} &rarr;
                    </a>
                    <button @click="triggerQuote('', '')" class="px-7 py-3.5 rounded-lg bg-white/10 hover:bg-white/20 text-[#EBD6B4] border border-[#EBD6B4]/40 font-medium text-sm backdrop-blur-sm transition-all">
                        {{ $hero->payload['secondary_cta_text'] ?? 'Request a Quote' }}
                    </button>
                </div>

                <!-- Stats Grid -->
                <div class="pt-8 grid grid-cols-2 sm:grid-cols-4 gap-4 border-t border-stone-800">
                    <div>
                        <div class="text-xl sm:text-2xl font-heading font-bold text-[#EBD6B4]">5th Gen</div>
                        <div class="text-[11px] uppercase tracking-wider text-stone-400 mt-0.5">Farming Roots</div>
                    </div>
                    <div>
                        <div class="text-xl sm:text-2xl font-heading font-bold text-white">Rajkot</div>
                        <div class="text-[11px] uppercase tracking-wider text-stone-400 mt-0.5">Gujarat Hub</div>
                    </div>
                    <div>
                        <div class="text-xl sm:text-2xl font-heading font-bold text-white">4 Sectors</div>
                        <div class="text-[11px] uppercase tracking-wider text-stone-400 mt-0.5">Commodities</div>
                    </div>
                    <div>
                        <div class="text-xl sm:text-2xl font-heading font-bold text-[#EBD6B4]">3 Ports</div>
                        <div class="text-[11px] uppercase tracking-wider text-stone-400 mt-0.5">Mundra/Kandla/Pipavav</div>
                    </div>
                </div>
            </div>

            <!-- Right Hero Visual -->
            <div class="lg:col-span-5 relative">
                <div class="relative mx-auto rounded-2xl overflow-hidden shadow-2xl border border-[#EBD6B4]/20 bg-stone-900 group">
                    @php
                        $heroDesktop = !empty($hero->payload['hero_image_desktop']) 
                            ? $hero->payload['hero_image_desktop'] 
                            : '/images/categories/agro-products.jpg';
                        $heroMobile = !empty($hero->payload['hero_image_mobile']) 
                            ? $hero->payload['hero_image_mobile'] 
                            : $heroDesktop;
                        $heroAlt = !empty($hero->payload['hero_image_alt']) 
                            ? $hero->payload['hero_image_alt'] 
                            : 'Shiv Aaradhana Agricultural Heritage and Export Commodities';
                    @endphp
                    <picture>
                        @if(!empty($hero->payload['hero_image_mobile']))
                            <source media="(max-width: 640px)" srcset="{{ $heroMobile }}">
                        @endif
                        <img src="{{ $heroDesktop }}" 
                             alt="{{ $heroAlt }}" 
                             class="w-full h-96 sm:h-[460px] object-cover group-hover:scale-105 transition-transform duration-700"
                             loading="eager"
                             fetchpriority="high"
                             width="600"
                             height="460">
                    </picture>
                    <div class="absolute inset-0 bg-gradient-to-t from-[#091433] via-transparent to-transparent"></div>
                    
                    <div class="absolute bottom-6 left-6 right-6 p-4 rounded-xl bg-[#091433]/85 backdrop-blur-md border border-white/10 text-white">
                        <span class="text-[10px] uppercase font-bold tracking-widest text-[#EBD6B4] block">Verified Agricultural Origin</span>
                        <h4 class="font-heading font-bold text-base mt-0.5">Gujarat Fertile Agro Corridors</h4>
                        <p class="text-xs text-stone-300 mt-1">Direct farm-gate aggregation, Sortex optical grading, and rigorous laboratory purity verification.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Company Introduction & Heritage -->
<section class="py-20 bg-white border-b border-stone-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <div class="lg:col-span-5 space-y-4">
                <span class="text-xs uppercase tracking-widest font-bold text-[#9C451B]">
                    {{ $heritage->subtitle ?? 'Our Heritage & Story' }}
                </span>
                <h2 class="text-2xl sm:text-4xl font-heading font-bold text-[#091433] tracking-tight">
                    {{ $heritage->title ?? 'Rooted in Gujarat, Serving Global Industry' }}
                </h2>
                <div class="w-16 h-1 bg-[#9C451B] rounded"></div>
            </div>

            <div class="lg:col-span-7 space-y-4 text-stone-600 text-sm sm:text-base leading-relaxed">
                <p>
                    {{ $heritage->content ?? 'Shiv Aaradhana Private Limited is a family-owned business rooted in Gujarat, India, with a fifth-generation agricultural heritage. Its operations span agricultural produce, spices, food products and textiles, along with an uncompromising commitment to quality, responsible sourcing and international trade.' }}
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                    @if(!empty($heritage->payload['core_strengths']))
                        @foreach($heritage->payload['core_strengths'] as $strength)
                            <div class="flex items-start gap-2.5 text-xs sm:text-sm text-stone-800">
                                <span class="text-[#394F3D] font-bold mt-0.5">&#10003;</span>
                                <span>{{ $strength }}</span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Product Category Discovery -->
<section class="py-20 bg-[#FAF5ED]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-12 gap-4">
            <div>
                <span class="text-xs uppercase tracking-widest font-bold text-[#9C451B]">Explore Our Portfolios</span>
                <h2 class="text-2xl sm:text-4xl font-heading font-bold text-[#091433] tracking-tight mt-1">
                    Export Commodity Categories
                </h2>
            </div>
            <a href="{{ route('catalog.index') }}" class="text-xs sm:text-sm font-semibold text-[#091433] hover:text-[#9C451B] inline-flex items-center gap-1.5 transition-colors">
                <span>View All Products</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($categories as $category)
            <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-stone-200/80 flex flex-col justify-between">
                <div>
                    <div class="h-48 overflow-hidden relative bg-stone-200">
                        @if($category->image_path)
                            <img src="{{ $category->image_path }}" alt="{{ $category->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#091433] to-[#394F3D]">
                                <span class="font-heading font-bold text-[#EBD6B4] text-2xl tracking-widest uppercase opacity-80">No Image</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent pointer-events-none"></div>
                        <span class="absolute bottom-3 left-4 text-xs font-bold text-white px-2.5 py-1 rounded bg-[#091433]/80 backdrop-blur-sm">
                            {{ $category->products_count }} {{ Str::plural('Product', $category->products_count) }}
                        </span>
                    </div>

                    <div class="p-5">
                        <h3 class="font-heading font-bold text-lg text-[#091433] group-hover:text-[#9C451B] transition-colors">
                            {{ $category->name }}
                        </h3>
                        <p class="text-xs text-stone-600 line-clamp-3 mt-2 leading-relaxed">
                            {{ $category->description }}
                        </p>
                    </div>
                </div>

                <div class="p-5 pt-0 border-t border-stone-100 mt-2">
                    <a href="{{ route('catalog.category', $category->slug) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#091433] group-hover:text-[#9C451B] tracking-wide uppercase transition-colors">
                        <span>Explore Category</span>
                        <svg class="w-3.5 h-3.5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

<!-- Featured Products Grid -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-14 space-y-3">
            <span class="text-xs uppercase tracking-widest font-bold text-[#9C451B]">Curated Selection</span>
            <h2 class="text-2xl sm:text-4xl font-heading font-bold text-[#091433]">
                Featured Export Commodities
            </h2>
            <p class="text-sm text-stone-600 leading-relaxed">
                Representative high-demand products prepared with certified lab specifications, customized bulk packaging, and prompt container loading.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($featuredProducts as $product)
            <div class="group bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                <div>
                    <!-- Product Image -->
                    <div class="h-56 overflow-hidden relative bg-stone-50">
                        <img src="{{ $product->primary_image ?? '/images/products/sesame-seeds.jpg' }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        
                        <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-[#394F3D] text-white">
                                {{ $product->category->name }}
                            </span>
                            @if($product->hs_code)
                                <span class="text-[10px] font-medium px-2 py-0.5 rounded bg-black/70 text-[#EBD6B4] backdrop-blur-sm">
                                    HS: {{ $product->hs_code }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Product Body -->
                    <div class="p-6 space-y-3">
                        <h3 class="font-heading font-bold text-lg text-[#091433] group-hover:text-[#9C451B] transition-colors line-clamp-1">
                            <a href="{{ route('catalog.product', $product->slug) }}">
                                {{ $product->name }}
                            </a>
                        </h3>

                        <p class="text-xs text-stone-600 line-clamp-2 leading-relaxed">
                            {{ $product->short_description }}
                        </p>

                        <!-- Key specifications pills -->
                        <div class="pt-2 flex flex-wrap gap-2 text-[11px] text-stone-700">
                            <span class="px-2 py-1 bg-stone-100 rounded text-stone-800 font-medium">
                                Origin: {{ $product->origin }}
                            </span>
                            @if($product->minimum_order_qty)
                            <span class="px-2 py-1 bg-stone-100 rounded text-stone-800 font-medium">
                                MOQ: {{ $product->minimum_order_qty }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="p-6 pt-0 flex items-center justify-between border-t border-stone-100 mt-4">
                    <a href="{{ route('catalog.product', $product->slug) }}" class="text-xs font-bold text-[#091433] hover:text-[#9C451B] transition-colors">
                        View Spec Sheet &rarr;
                    </a>
                    <button @click="triggerQuote('{{ $product->id }}', '{{ addslashes($product->name) }}')" class="px-3.5 py-1.5 rounded-lg bg-[#091433] hover:bg-[#9C451B] text-white text-xs font-semibold shadow transition-colors">
                        Request Quote
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-lg bg-[#091433] hover:bg-[#394F3D] text-[#EBD6B4] font-medium text-sm transition-colors shadow-lg">
                <span>Explore All Export Commodities & Specifications</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>

    </div>
</section>

<!-- Sourcing and Export Trade Process -->
<section class="py-20 bg-[#091433] text-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
            <span class="text-xs uppercase tracking-widest font-bold text-[#EBD6B4]">
                {{ $process->subtitle ?? 'How We Work with International Buyers' }}
            </span>
            <h2 class="text-2xl sm:text-4xl font-heading font-bold text-white">
                {{ $process->title ?? 'Disciplined 4-Stage Sourcing & Export Process' }}
            </h2>
            <p class="text-sm text-stone-300 leading-relaxed">
                {{ $process->content ?? 'From initial farm harvest to port-side container stuffing, each consignment undergoes verified procedural checks.' }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @if(!empty($process->payload['steps']))
                @foreach($process->payload['steps'] as $step)
                <div class="p-6 rounded-2xl bg-white/5 border border-white/10 hover:border-[#EBD6B4]/40 transition-all duration-300 relative group">
                    <span class="text-4xl font-heading font-black text-[#EBD6B4]/30 group-hover:text-[#EBD6B4]/70 transition-colors block mb-4">
                        {{ $step['step'] }}
                    </span>
                    <h3 class="font-heading font-bold text-lg text-white mb-2">
                        {{ $step['title'] }}
                    </h3>
                    <p class="text-xs text-stone-300 leading-relaxed">
                        {{ $step['desc'] }}
                    </p>
                </div>
                @endforeach
            @endif
        </div>

    </div>
</section>

<!-- Quality Commitments & Verification -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <div class="lg:col-span-6 space-y-6">
                <span class="text-xs uppercase tracking-widest font-bold text-[#9C451B]">Quality & Standards</span>
                <h2 class="text-2xl sm:text-4xl font-heading font-bold text-[#091433] tracking-tight">
                    International Quality Benchmarks & Responsible Sourcing
                </h2>
                <p class="text-sm text-stone-600 leading-relaxed">
                    International buyers rely on Shiv Aaradhana for exact specification matching. We enforce systematic checks at every transformation stage:
                </p>

                <div class="space-y-4">
                    <div class="p-4 rounded-xl bg-stone-50 border border-stone-200">
                        <h4 class="font-bold text-sm text-[#091433]">Optical Sortex & Mechanical Cleaning</h4>
                        <p class="text-xs text-stone-600 mt-1">Eliminating foreign matter, discolored seeds, and weed contaminants up to 99.9% purity thresholds.</p>
                    </div>

                    <div class="p-4 rounded-xl bg-stone-50 border border-stone-200">
                        <h4 class="font-bold text-sm text-[#091433]">Laboratory Parameters & Certificate Support</h4>
                        <p class="text-xs text-stone-600 mt-1">Verified batch tests for moisture, essential oil percentage, aflatoxin bounds, and pesticide residues.</p>
                    </div>

                    <div class="p-4 rounded-xl bg-stone-50 border border-stone-200">
                        <h4 class="font-bold text-sm text-[#091433]">Port Logistics & Phytosanitary Clearance</h4>
                        <p class="text-xs text-stone-600 mt-1">Fumigation compliance, phytosanitary certificates, and efficient sea container dispatches through Mundra, Kandla, and Pipavav ports.</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-6">
                <div class="p-8 sm:p-10 rounded-3xl bg-gradient-to-br from-[#394F3D] to-[#091433] text-white shadow-xl space-y-6 border border-[#EBD6B4]/20">
                    <span class="text-xs font-bold uppercase tracking-widest text-[#EBD6B4] block">Verified Business Information</span>
                    <h3 class="text-2xl font-heading font-bold">Shiv Aaradhana Private Limited</h3>

                    <div class="space-y-3 text-xs text-stone-300">
                        <div>
                            <span class="text-[#EBD6B4] font-semibold block">Registered Head Office:</span>
                            1st Floor, Sahkar Complex, Movaiya Circle, Rajkot-Jamnagar Highway, Taluka Paddhari, District Rajkot, Gujarat 360110, India.
                        </div>
                        <div>
                            <span class="text-[#EBD6B4] font-semibold block">Direct Contact Desk:</span>
                            <a href="tel:+918487878721" class="text-white hover:underline">+91 84878 78721</a> &bull; 
                            <a href="mailto:info.shivaaradhana@gmail.com" class="text-white hover:underline">info.shivaaradhana@gmail.com</a>
                        </div>
                        <div>
                            <span class="text-[#EBD6B4] font-semibold block">Nearest Deep-Water Container Ports:</span>
                            Mundra (240 km) &bull; Kandla (200 km) &bull; Pipavav (230 km)
                        </div>
                    </div>

                    <div class="pt-4 border-t border-white/10 flex items-center justify-between">
                        <span class="text-xs text-[#EBD6B4]">Inquiries reviewed within 24 hours</span>
                        <a href="{{ route('contact') }}" class="px-4 py-2 rounded-lg bg-[#9C451B] hover:bg-[#b85322] text-white font-semibold text-xs transition-colors">
                            Contact Trade Desk &rarr;
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Bottom CTA Banner -->
<section class="py-16 bg-[#561118] text-white text-center">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <h2 class="text-2xl sm:text-4xl font-heading font-bold text-white tracking-tight">
            Partner with Shiv Aaradhana for Your Next Export Shipment
        </h2>
        <p class="text-sm sm:text-base text-stone-200 max-w-2xl mx-auto leading-relaxed">
            Whether seeking full container loads of Gujarat cumin, sesame seeds, cotton bales, or custom dehydrated flakes, our export desk is ready to provide transparent specs and competitive pricing.
        </p>
        <div class="pt-2 flex flex-wrap justify-center gap-4">
            <button @click="triggerQuote('', '')" class="px-8 py-3.5 rounded-lg bg-[#EBD6B4] hover:bg-white text-[#091433] font-bold text-sm shadow-xl transition-all">
                Request a Formal Quotation
            </button>
            <a href="{{ route('contact') }}" class="px-8 py-3.5 rounded-lg bg-transparent hover:bg-white/10 text-white border border-white/40 font-medium text-sm transition-all">
                Direct Contact Details
            </a>
        </div>
    </div>
</section>

@endsection
