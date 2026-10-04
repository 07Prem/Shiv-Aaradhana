<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') — Shiv Aaradhana</title>
    @if(!empty($branding['favicon']))
        <link rel="icon" href="{{ $branding['favicon'] }}">
    @else
        <link rel="icon" href="/favicon.ico">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-100 text-stone-800 antialiased font-sans">
    <div class="min-h-screen flex flex-col md:flex-row">
        
        <!-- Sidebar Navigation -->
        <aside class="w-full md:w-64 bg-[#091433] text-stone-300 flex-shrink-0 flex flex-col justify-between border-r border-stone-800">
            <div>
                <!-- Brand Header -->
                <div class="p-6 border-b border-stone-800 flex items-center justify-between">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                        @if(!empty($branding['light_logo']) || !empty($branding['main_logo']))
                            <img src="{{ $branding['light_logo'] ?? $branding['main_logo'] }}" alt="Shiv Aaradhana" class="h-9 w-auto max-w-[140px] object-contain">
                        @else
                            <div class="w-9 h-9 rounded-lg bg-[#394F3D] border border-[#EBD6B4]/40 flex items-center justify-center font-heading font-bold text-[#EBD6B4]">
                                SA
                            </div>
                            <div>
                                <span class="font-heading font-bold text-white text-sm block">Shiv Aaradhana</span>
                                <span class="text-[10px] tracking-wider uppercase text-[#EBD6B4] block font-semibold">Admin Suite</span>
                            </div>
                        @endif
                    </a>
                </div>

                <!-- Navigation Links -->
                <nav class="p-4 space-y-1 text-xs font-medium">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-[#394F3D] text-white font-bold' : 'hover:bg-white/5 text-stone-300' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span>Dashboard</span>
                    </a>

                    <div class="pt-4 pb-1 px-3 text-[10px] uppercase tracking-widest text-stone-400 font-bold">Catalog Management</div>

                    <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.products*') ? 'bg-[#394F3D] text-white font-bold' : 'hover:bg-white/5 text-stone-300' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        <span>Export Products</span>
                    </a>

                    <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.categories*') ? 'bg-[#394F3D] text-white font-bold' : 'hover:bg-white/5 text-stone-300' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                        <span>Categories</span>
                    </a>

                    <a href="{{ route('admin.product-types.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.product-types*') ? 'bg-[#394F3D] text-white font-bold' : 'hover:bg-white/5 text-stone-300' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        <span>Product Lines</span>
                    </a>

                    <div class="pt-4 pb-1 px-3 text-[10px] uppercase tracking-widest text-stone-400 font-bold">Trade Inquiries</div>

                    <a href="{{ route('admin.inquiries.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.inquiries*') ? 'bg-[#394F3D] text-white font-bold' : 'hover:bg-white/5 text-stone-300' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span>Buyer Inquiries</span>
                        </div>
                        @php $newInqCount = \App\Models\Inquiry::where('status', 'new')->count(); @endphp
                        @if($newInqCount > 0)
                            <span class="px-2 py-0.5 rounded-full bg-[#9C451B] text-white text-[10px] font-bold">
                                {{ $newInqCount }}
                            </span>
                        @endif
                    </a>

                    <div class="pt-4 pb-1 px-3 text-[10px] uppercase tracking-widest text-stone-400 font-bold">Content & Governance</div>

                    <a href="{{ route('admin.branding.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.branding*') ? 'bg-[#394F3D] text-white font-bold' : 'hover:bg-white/5 text-stone-300' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                        <span>Branding &amp; Hero Settings</span>
                    </a>

                    <a href="{{ route('admin.cms.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.cms*') ? 'bg-[#394F3D] text-white font-bold' : 'hover:bg-white/5 text-stone-300' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        <span>CMS Story Sections</span>
                    </a>

                    @if(auth()->user()?->isSuperAdmin())
                    <a href="{{ route('admin.audit_logs.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.audit_logs*') ? 'bg-[#394F3D] text-white font-bold' : 'hover:bg-white/5 text-stone-300' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>Audit Trail</span>
                    </a>
                    @endif
                </nav>
            </div>

            <!-- User Status & Logout -->
            <div class="p-4 border-t border-stone-800">
                <div class="px-3 py-2 text-xs">
                    <span class="text-white font-bold block">{{ auth()->user()?->name }}</span>
                    <span class="text-[10px] text-[#EBD6B4] uppercase tracking-wider block">{{ auth()->user()?->role }}</span>
                </div>
                <div class="pt-2 flex items-center justify-between px-3 text-xs">
                    <a href="{{ route('home') }}" target="_blank" class="text-stone-400 hover:text-white inline-flex items-center gap-1">
                        <span>View Public Site</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </a>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-rose-400 hover:text-rose-300 font-semibold">
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Body -->
        <div class="flex-1 flex flex-col overflow-y-auto">
            
            <!-- Admin Topbar -->
            <header class="bg-white border-b border-stone-200 px-6 py-4 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-heading font-bold text-[#091433]">@yield('header', 'Dashboard')</h2>
                </div>
                <div class="flex items-center gap-4 text-xs">
                    <span class="px-2.5 py-1 rounded bg-stone-100 text-stone-600 font-mono">
                        {{ now()->format('d M Y, H:i') }} IST
                    </span>
                    <a href="{{ route('home') }}" target="_blank" class="px-3 py-1.5 rounded-lg border border-stone-300 text-stone-700 hover:bg-stone-50 font-medium">
                        Public Storefront &rarr;
                    </a>
                </div>
            </header>

            <!-- Flash alerts -->
            @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 m-6 mb-0 text-emerald-800 text-xs font-medium rounded-r-lg">
                {{ session('success') }}
            </div>
            @endif

            @if($errors->has('error'))
            <div class="bg-rose-50 border-l-4 border-rose-500 p-4 m-6 mb-0 text-rose-800 text-xs font-medium rounded-r-lg">
                {{ $errors->first('error') }}
            </div>
            @endif

            <!-- Content Area -->
            <main class="p-6 md:p-8 flex-1">
                @yield('content')
            </main>
        </div>

    </div>
</body>
</html>
