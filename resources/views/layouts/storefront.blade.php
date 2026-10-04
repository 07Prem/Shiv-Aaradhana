<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Shiv Aaradhana Private Limited — Premium International B2B Import-Export')</title>
    <meta name="description" content="@yield('meta_description', 'Shiv Aaradhana Private Limited is a premier international B2B exporter of agro products, spices, and textiles rooted in Gujarat, India with 5th-generation heritage.')">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Shiv Aaradhana Private Limited — International B2B Trade')">
    <meta property="og:description" content="@yield('meta_description', 'Export-grade Indian agricultural produce, spices, and textiles sourced directly from Gujarat.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if(!empty($branding['favicon']))
        <link rel="icon" href="{{ $branding['favicon'] }}">
    @else
        <link rel="icon" href="/favicon.ico">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-[#FCFCFA] text-stone-800 antialiased selection:bg-[#9C451B] selection:text-white" x-data="rfqManager()">

    <!-- Top Utility Bar -->
    <header class="bg-[#091433] text-stone-300 text-xs border-b border-stone-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 flex flex-wrap justify-between items-center gap-3">
            <div class="flex items-center gap-6">
                <span class="inline-flex items-center gap-1.5 text-[#EBD6B4] font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Rajkot, Gujarat, India
                </span>
                <span class="hidden md:inline text-stone-400">|</span>
                <span class="hidden md:inline-flex items-center gap-1.5 text-stone-300">
                    <svg class="w-3.5 h-3.5 text-[#EBD6B4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Export Desk: Mon – Sat 09:00 – 19:00 IST
                </span>
            </div>
            <div class="flex items-center gap-5">
                <a href="tel:+918487878721" class="inline-flex items-center gap-1.5 hover:text-[#EBD6B4] transition-colors font-medium">
                    <svg class="w-3.5 h-3.5 text-[#EBD6B4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    +91 84878 78721
                </a>
                <span class="text-stone-600">/</span>
                <a href="mailto:info.shivaaradhana@gmail.com" class="hover:text-[#EBD6B4] transition-colors truncate max-w-[200px] sm:max-w-none">
                    info.shivaaradhana@gmail.com
                </a>

            </div>
        </div>
    </header>

    <!-- Main Navigation Bar -->
    <nav class="sticky top-0 z-40 bg-[#091433]/95 backdrop-blur-md border-b border-[#EBD6B4]/15 shadow-sm" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand Logo / Identity -->
                <div class="flex items-center gap-3 group cursor-pointer" 
                     x-data="{ clicks: 0, timer: null }"
                     @click.prevent="clicks++; if(clicks === 1) { timer = setTimeout(() => { clicks = 0; window.location.href = '{{ route('home') }}'; }, 250); } else { clearTimeout(timer); clicks = 0; $dispatch('open-logo-viewer', '{{ $branding['light_logo'] ?? $branding['main_logo'] }}'); }">
                    @if(!empty($branding['light_logo']) || !empty($branding['main_logo']))
                        <img src="{{ $branding['light_logo'] ?? $branding['main_logo'] }}" 
                             alt="{{ $branding['company_name'] ?? 'Shiv Aaradhana Private Limited' }}" 
                             title="Double-tap to view full photo"
                             class="h-16 sm:h-20 w-auto max-w-[200px] object-contain transition-transform hover:scale-105 p-1">
                    @else
                        <div class="w-11 h-11 rounded-lg bg-gradient-to-br from-[#394F3D] to-[#091433] border border-[#EBD6B4]/40 flex items-center justify-center shadow-md shadow-black/20 group-hover:border-[#EBD6B4] transition-all">
                            <span class="font-heading font-black text-xl text-[#EBD6B4] tracking-tight">SA</span>
                        </div>
                        <div>
                            <span class="block font-heading text-lg sm:text-xl font-bold tracking-wide text-white group-hover:text-[#EBD6B4] transition-colors">
                                SHIV AARADHANA
                            </span>
                            <span class="block text-[10px] tracking-widest uppercase text-[#EBD6B4]/80 font-medium">
                                Private Limited &bull; India
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden md:flex items-center gap-8 text-sm font-medium">
                    <a href="{{ route('home') }}" class="text-white hover:text-[#EBD6B4] transition-colors {{ request()->routeIs('home') ? 'text-[#EBD6B4] font-semibold border-b-2 border-[#EBD6B4] pb-1' : '' }}">
                        Home
                    </a>
                    
                    <!-- Products Dropdown -->
                    <div class="relative group" x-data="{ open: false }" @mouseleave="open = false">
                        <button @mouseover="open = true" @click="open = !open" class="inline-flex items-center gap-1.5 text-white hover:text-[#EBD6B4] transition-colors py-2 {{ request()->is('products*') || request()->is('category*') ? 'text-[#EBD6B4] font-semibold border-b-2 border-[#EBD6B4] pb-1' : '' }}">
                            <span>Product Catalog</span>
                            <svg class="w-4 h-4 text-stone-400 group-hover:text-[#EBD6B4] transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-2"
                             class="absolute left-0 mt-1 w-72 bg-[#091433] border border-[#EBD6B4]/20 rounded-xl shadow-2xl p-3 space-y-1 z-50">
                            <a href="{{ route('catalog.index') }}" class="block px-3 py-2 text-xs font-bold text-[#EBD6B4] tracking-wider uppercase hover:bg-white/5 rounded-lg">
                                View Full B2B Catalog &rarr;
                            </a>
                            <div class="border-t border-stone-800 my-1"></div>
                            <a href="{{ route('catalog.category', 'agro-products') }}" class="block px-3 py-2 text-sm text-stone-200 hover:text-[#EBD6B4] hover:bg-white/5 rounded-lg transition-colors">
                                Agro Products & Oilseeds
                            </a>
                            <a href="{{ route('catalog.category', 'spices-and-food-products') }}" class="block px-3 py-2 text-sm text-stone-200 hover:text-[#EBD6B4] hover:bg-white/5 rounded-lg transition-colors">
                                Spices & Food Products
                            </a>
                            <a href="{{ route('catalog.category', 'textiles-and-fabrics') }}" class="block px-3 py-2 text-sm text-stone-200 hover:text-[#EBD6B4] hover:bg-white/5 rounded-lg transition-colors">
                                Textiles & Shankar-6 Cotton
                            </a>
                            <a href="{{ route('catalog.category', 'other-export-products') }}" class="block px-3 py-2 text-sm text-stone-200 hover:text-[#EBD6B4] hover:bg-white/5 rounded-lg transition-colors">
                                Dehydrated Foods & Botanicals
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('about') }}" class="text-white hover:text-[#EBD6B4] transition-colors {{ request()->routeIs('about') ? 'text-[#EBD6B4] font-semibold border-b-2 border-[#EBD6B4] pb-1' : '' }}">
                        Heritage & About
                    </a>

                    <a href="{{ route('contact') }}" class="text-white hover:text-[#EBD6B4] transition-colors {{ request()->routeIs('contact') ? 'text-[#EBD6B4] font-semibold border-b-2 border-[#EBD6B4] pb-1' : '' }}">
                        Contact & Office
                    </a>
                </div>

                <!-- Primary CTA Button & Search Trigger -->
                <div class="hidden sm:flex items-center gap-3">
                    <a href="{{ route('catalog.index') }}" class="p-2 text-stone-300 hover:text-[#EBD6B4] transition-colors" title="Search catalog">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </a>
                    <button @click="openModal()" class="relative inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-[#9C451B] hover:bg-[#b85322] text-white font-medium text-sm shadow-md hover:shadow-lg transition-all transform active:scale-95">
                        <span>Quotation Request</span>
                        <template x-if="count > 0">
                            <span x-text="count" class="px-1.5 py-0.5 text-[11px] font-bold bg-white text-[#9C451B] rounded-full leading-none"></span>
                        </template>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex items-center sm:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 text-stone-300 hover:text-white focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer Menu -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="sm:hidden bg-[#091433] border-b border-stone-800 px-4 pt-3 pb-6 space-y-3">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-white hover:bg-white/5">
                Home
            </a>
            <a href="{{ route('catalog.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-[#EBD6B4] hover:bg-white/5">
                Product Catalog (All Categories)
            </a>
            <a href="{{ route('about') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-white hover:bg-white/5">
                Heritage & About
            </a>
            <a href="{{ route('contact') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-white hover:bg-white/5">
                Contact & Office
            </a>
            <div class="pt-2">
                <button @click="openModal(); mobileMenuOpen = false" class="w-full text-center py-3 rounded-lg bg-[#9C451B] text-white font-medium text-sm flex items-center justify-center gap-2">
                    <span>Request a Formal Quote</span>
                    <template x-if="count > 0">
                        <span x-text="'(' + count + ' items)'" class="font-bold text-[#EBD6B4] text-xs"></span>
                    </template>
                </button>
            </div>
        </div>
    </nav>

    <!-- Global Flash Notification Messages -->
    @if(session('success_inquiry'))
    <div class="bg-emerald-900/90 text-white px-4 py-3 border-b border-emerald-600 shadow-sm" x-data="{ show: true }" x-show="show">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="p-1.5 rounded-full bg-emerald-700 text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </span>
                <p class="text-sm font-medium">{{ session('success_inquiry')['message'] }}</p>
            </div>
            <button @click="show = false" class="text-emerald-300 hover:text-white">&times;</button>
        </div>
    </div>
    @endif

    @if(session('success_quote'))
    <div class="bg-[#394F3D] text-white px-4 py-3 border-b border-[#EBD6B4]/30 shadow-sm" x-data="{ show: true }" x-show="show">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="p-1.5 rounded-full bg-[#091433] text-[#EBD6B4]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </span>
                <p class="text-sm font-medium">{{ session('success_quote')['message'] }}</p>
            </div>
            <button @click="show = false" class="text-stone-300 hover:text-white">&times;</button>
        </div>
    </div>
    @endif

    <!-- Main Content Area -->
    <main>
        @yield('content')
    </main>

    <!-- Floating Add-to-Quote Toast Notification -->
    <div x-show="toastMessage" 
         x-cloak 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="fixed bottom-6 right-6 z-50 bg-[#091433] text-white px-5 py-3.5 rounded-xl shadow-2xl border border-[#EBD6B4]/40 flex items-center gap-3">
        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
        <span x-text="toastMessage" class="text-xs font-semibold"></span>
        <button type="button" @click="openModal()" class="text-xs text-[#EBD6B4] font-bold hover:underline ml-2">View List &rarr;</button>
    </div>

    <!-- Global Interactive Request a Quote Modal -->
    <div x-show="open" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         role="dialog" 
         aria-modal="true">
        <div class="min-h-screen px-4 text-center flex items-center justify-center">
            <!-- Backdrop -->
            <div x-show="open" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="closeModal()" 
                 class="fixed inset-0 bg-black/75 backdrop-blur-sm transition-opacity"></div>

            <!-- Modal Panel -->
            <div x-show="open" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
                 class="relative inline-block w-full max-w-3xl p-6 sm:p-8 my-8 text-left bg-white rounded-2xl shadow-2xl overflow-hidden z-10 border border-stone-200 max-h-[90vh] overflow-y-auto">
                
                <div class="flex items-center justify-between pb-4 border-b border-stone-200">
                    <div>
                        <span class="text-xs uppercase tracking-wider font-bold text-[#9C451B]">International Trade Desk</span>
                        <h3 class="text-xl sm:text-2xl font-heading font-bold text-[#091433]">
                            Request an Official Quotation (RFQ)
                        </h3>
                    </div>
                    <button type="button" @click="closeModal()" class="text-stone-400 hover:text-stone-700 p-1.5 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Success Confirmation State -->
                <template x-if="successData">
                    <div class="py-10 text-center space-y-4">
                        <div class="w-16 h-16 mx-auto rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shadow-inner">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-mono font-bold" x-text="'Registered Ref #' + successData.ref"></span>
                        <h4 class="text-2xl font-heading font-bold text-[#091433]">Quotation Request Received</h4>
                        <p class="text-xs sm:text-sm text-stone-600 max-w-lg mx-auto leading-relaxed" x-text="successData.message"></p>
                        <div class="pt-4 flex justify-center gap-3">
                            <button type="button" @click="closeModal()" class="px-6 py-2.5 rounded-lg bg-[#091433] hover:bg-[#394F3D] text-white text-xs font-bold shadow transition-colors">
                                Return to Storefront
                            </button>
                        </div>
                    </div>
                </template>

                <!-- RFQ Form Content -->
                <div x-show="!successData">
                    <!-- Error Message Banner -->
                    <div x-show="errorMessage" x-cloak class="mt-4 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium flex items-center justify-between">
                        <span x-text="errorMessage"></span>
                        <button type="button" @click="errorMessage = ''" class="text-rose-500 hover:text-rose-800 font-bold ml-2">&times;</button>
                    </div>

                    <form action="{{ route('inquiry.quote') }}" method="POST" @submit.prevent="submitRfq($el)" class="mt-6 space-y-6">
                        @csrf
                        <!-- Honeypot -->
                        <input type="text" name="website_hp" value="" style="display:none !important;" tabindex="-1" autocomplete="off">

                        <!-- Products in RFQ Section -->
                        <div class="p-4 sm:p-5 rounded-xl bg-[#FAF5ED] border border-[#EBD6B4] space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#091433]">
                                        Requested Commodities / Products
                                    </h4>
                                    <span class="px-2 py-0.5 rounded-full bg-[#9C451B] text-white text-[10px] font-bold" x-text="count + ' Selected'"></span>
                                </div>
                                <button type="button" x-show="items.length > 0" @click="clearAll()" class="text-[11px] text-stone-500 hover:text-rose-600 underline transition-colors">
                                    Clear List
                                </button>
                            </div>

                            <!-- List of Added Commodities -->
                            <div x-show="items.length > 0" class="space-y-3">
                                <template x-for="(item, idx) in items" :key="item.product_id">
                                    <div class="p-3 bg-white rounded-lg border border-stone-200 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-stone-900 text-sm truncate" x-text="item.name"></span>
                                                <span class="text-[10px] text-stone-400 font-mono" x-show="item.hs_code" x-text="'HS: ' + item.hs_code"></span>
                                            </div>
                                            <div class="mt-1 grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                                                <div>
                                                    <label class="block text-[10px] text-stone-500 uppercase font-semibold">Target Volume *</label>
                                                    <input type="text" x-model="item.quantity" @change="updateQty(item.product_id, item.quantity)" placeholder="e.g. 1 x 20ft FCL (19 MT)" class="w-full px-2.5 py-1.5 rounded border border-stone-300 text-xs bg-stone-50/50">
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] text-stone-500 uppercase font-semibold">Quality / Notes</label>
                                                    <input type="text" x-model="item.notes" @change="updateNotes(item.product_id, item.notes)" placeholder="e.g. 99.5% Sortex, 25kg bags" class="w-full px-2.5 py-1.5 rounded border border-stone-300 text-xs bg-stone-50/50">
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" @click="removeItem(item.product_id)" class="self-end sm:self-center p-1.5 text-stone-400 hover:text-rose-600 rounded-lg hover:bg-stone-100 transition-colors" title="Remove product">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>

                            <!-- When no items are in list -->
                            <div x-show="items.length === 0" class="py-4 text-center text-xs text-stone-500">
                                <p class="mb-2">Your quotation list is empty. Select a commodity below to add:</p>
                            </div>

                            <!-- Add Another Product Dropdown -->
                            <div class="pt-2 border-t border-[#EBD6B4]/60 flex flex-col sm:flex-row items-center gap-2">
                                <span class="text-[11px] font-bold text-stone-600 whitespace-nowrap">+ Add Commodity:</span>
                                <select @change="if($event.target.value) { addProduct($event.target.value, $event.target.options[$event.target.selectedIndex].text, '1 x 20ft FCL', ''); $event.target.value = ''; }" class="flex-1 w-full px-3 py-1.5 rounded-lg border border-stone-300 text-xs bg-white focus:ring-2 focus:ring-[#9C451B]">
                                    <option value="">-- Choose Commodity to Add to Quote --</option>
                                    @foreach(\App\Models\Product::published()->orderBy('name')->get() as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->origin }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Buyer Contact Information -->
                        <div class="space-y-4">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-[#091433]">
                                Buyer & Consignment Details
                            </h4>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">
                                        Full Name <span class="text-rose-600">*</span>
                                    </label>
                                    <input type="text" name="full_name" required x-model="fullName" class="w-full px-3 py-2 rounded-lg border border-stone-300 focus:ring-2 focus:ring-[#9C451B] text-sm" placeholder="e.g. Johnathan Davis">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">
                                        Company / Importer Name
                                    </label>
                                    <input type="text" name="company_name" x-model="companyName" class="w-full px-3 py-2 rounded-lg border border-stone-300 focus:ring-2 focus:ring-[#9C451B] text-sm" placeholder="e.g. Continental Trading LLC">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">
                                        Business Email <span class="text-rose-600">*</span>
                                    </label>
                                    <input type="email" name="email" required x-model="email" class="w-full px-3 py-2 rounded-lg border border-stone-300 focus:ring-2 focus:ring-[#9C451B] text-sm" placeholder="buyer@domain.com">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">
                                        Phone / WhatsApp <span class="text-rose-600">*</span>
                                    </label>
                                    <input type="text" name="phone" required x-model="phone" class="w-full px-3 py-2 rounded-lg border border-stone-300 focus:ring-2 focus:ring-[#9C451B] text-sm" placeholder="+1 555 019 2831">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">
                                        Destination Country <span class="text-rose-600">*</span>
                                    </label>
                                    <input type="text" name="country" required x-model="country" class="w-full px-3 py-2 rounded-lg border border-stone-300 focus:ring-2 focus:ring-[#9C451B] text-sm" placeholder="e.g. United Arab Emirates">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">
                                        Discharge Port / Delivery Port
                                    </label>
                                    <input type="text" name="port_of_destination" x-model="port" class="w-full px-3 py-2 rounded-lg border border-stone-300 focus:ring-2 focus:ring-[#9C451B] text-sm" placeholder="e.g. Jebel Ali, Rotterdam, Chittagong">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">
                                        Packaging Preferences
                                    </label>
                                    <input type="text" name="packaging_requirements" x-model="packaging" class="w-full px-3 py-2 rounded-lg border border-stone-300 focus:ring-2 focus:ring-[#9C451B] text-sm" placeholder="e.g. 25kg PP bags, 1000kg Big Bags">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">
                                    Additional Requirements / Sampling Notes
                                </label>
                                <textarea name="message" rows="2" x-model="message" class="w-full px-3 py-2 rounded-lg border border-stone-300 focus:ring-2 focus:ring-[#9C451B] text-sm" placeholder="Specify CIF/FOB delivery terms, target shipment date, inspection requirements..."></textarea>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="pt-3 border-t border-stone-200 flex items-center justify-end gap-3">
                            <button type="button" @click="closeModal()" class="px-5 py-2.5 rounded-lg border border-stone-300 text-stone-700 hover:bg-stone-50 font-medium text-xs">
                                Cancel
                            </button>
                            <button type="submit" 
                                    :disabled="isSubmitting" 
                                    class="px-7 py-2.5 rounded-lg bg-[#091433] hover:bg-[#394F3D] text-white font-bold text-xs shadow-md transition-all flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span x-show="!isSubmitting">Submit Formal Quotation Request &rarr;</span>
                                <span x-show="isSubmitting" class="inline-flex items-center gap-2">
                                    <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Processing RFQ...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- International B2B Footer -->
    <footer class="bg-[#091433] text-stone-300 border-t border-stone-800 pt-16 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 pb-12 border-b border-stone-800/80">
                <!-- Col 1: Corporate Profile -->
                <div class="space-y-4">
                    @if(!empty($branding['light_logo']) || !empty($branding['main_logo']))
                        <div class="flex items-center gap-3">
                            <img src="{{ $branding['light_logo'] ?? $branding['main_logo'] }}" 
                                 alt="{{ $branding['company_name'] ?? 'Shiv Aaradhana Private Limited' }}" 
                                 @dblclick.prevent.stop="$dispatch('open-logo-viewer', $el.src)"
                                 title="Double-tap to view full photo"
                                 class="h-10 w-auto max-w-[180px] object-contain cursor-pointer transition-transform hover:scale-105">
                        </div>
                    @else
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-[#394F3D] border border-[#EBD6B4]/40 flex items-center justify-center font-heading font-bold text-[#EBD6B4]">
                                SA
                            </div>
                            <span class="font-heading text-lg font-bold text-white tracking-wide">
                                SHIV AARADHANA
                            </span>
                        </div>
                    @endif
                    <p class="text-xs text-stone-400 leading-relaxed">
                        Shiv Aaradhana Private Limited is a family-owned international import-export and sourcing enterprise rooted in Gujarat, India, carrying forward a fifth-generation agricultural legacy.
                    </p>
                    <div class="pt-2 text-xs text-[#EBD6B4]">
                        <span class="font-semibold block">Nearest Export Gateways:</span>
                        Mundra Port (240 km) &bull; Kandla Port (200 km) &bull; Pipavav Port (230 km)
                    </div>
                </div>

                <!-- Col 2: Product Categories -->
                <div>
                    <h4 class="text-xs uppercase tracking-widest font-bold text-[#EBD6B4] mb-4">Export Categories</h4>
                    <ul class="space-y-2.5 text-xs">
                        <li><a href="{{ route('catalog.category', 'agro-products') }}" class="hover:text-white transition-colors">Agro Products & Oilseeds</a></li>
                        <li><a href="{{ route('catalog.category', 'spices-and-food-products') }}" class="hover:text-white transition-colors">Spices & Food Commodities</a></li>
                        <li><a href="{{ route('catalog.category', 'textiles-and-fabrics') }}" class="hover:text-white transition-colors">Textiles & Shankar-6 Cotton</a></li>
                        <li><a href="{{ route('catalog.category', 'other-export-products') }}" class="hover:text-white transition-colors">Dehydrated Foods & Botanicals</a></li>
                        <li class="pt-2"><a href="{{ route('catalog.index') }}" class="text-[#EBD6B4] font-medium hover:underline">Browse Complete Catalog &rarr;</a></li>
                    </ul>
                </div>

                <!-- Col 3: Company & Governance -->
                <div>
                    <h4 class="text-xs uppercase tracking-widest font-bold text-[#EBD6B4] mb-4">Company & Compliance</h4>
                    <ul class="space-y-2.5 text-xs">
                        <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">Heritage & Philosophy</a></li>
                        <li><a href="{{ route('about') }}#process" class="hover:text-white transition-colors">4-Stage Sourcing Pipeline</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-white transition-colors">Rajkot Registered Office</a></li>
                        <li><a href="{{ route('legal.terms') }}" class="hover:text-white transition-colors">Terms of International Trade</a></li>
                        <li><a href="{{ route('legal.privacy') }}" class="hover:text-white transition-colors">Data Protection & Privacy Policy</a></li>
                    </ul>
                </div>

                <!-- Col 4: Verified Contact -->
                <div class="space-y-3">
                    <h4 class="text-xs uppercase tracking-widest font-bold text-[#EBD6B4] mb-4">Export Office</h4>
                    <p class="text-xs text-stone-300 leading-relaxed">
                        1st Floor, Sahkar Complex, Movaiya Circle, Rajkot-Jamnagar Highway, Taluka Paddhari, District Rajkot, Gujarat 360110, India.
                    </p>
                    <div class="space-y-1.5 pt-2 text-xs">
                        <div>
                            <span class="text-stone-400">Direct Phone:</span> 
                            <a href="tel:+918487878721" class="text-white hover:text-[#EBD6B4] font-medium ml-1">+91 84878 78721</a>
                        </div>
                        <div>
                            <span class="text-stone-400">Trade Email:</span> 
                            <a href="mailto:info.shivaaradhana@gmail.com" class="text-[#EBD6B4] hover:underline ml-1">info.shivaaradhana@gmail.com</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright & Legal -->
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-stone-400 gap-4">
                <p>&copy; {{ date('Y') }} Shiv Aaradhana Private Limited. All rights reserved.</p>
                <div class="flex items-center gap-6">
                    <a href="{{ route('legal.privacy') }}" class="hover:text-white transition-colors">Privacy</a>
                    <a href="{{ route('legal.terms') }}" class="hover:text-white transition-colors">Terms</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Fullscreen Logo Viewer Overlay -->
    <div x-data="{ 
            showLogo: false, 
            logoSrc: '', 
            timeout: null,
            openViewer(src) {
                this.logoSrc = src;
                this.showLogo = true;
                clearTimeout(this.timeout);
                this.timeout = setTimeout(() => { this.showLogo = false; }, 30000);
            },
            closeViewer() {
                this.showLogo = false;
                clearTimeout(this.timeout);
            }
         }" 
         @open-logo-viewer.window="openViewer($event.detail)"
         x-show="showLogo"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak
         @click="closeViewer()"
         class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 cursor-pointer backdrop-blur-md">
        
        <img :src="logoSrc" class="max-w-[90vw] max-h-[90vh] object-contain drop-shadow-2xl scale-110" alt="Full Logo View" @click.stop>
        <div class="absolute bottom-10 text-white/50 text-sm tracking-widest uppercase">Tap anywhere to close</div>
    </div>

    @stack('scripts')
</body>
</html>
