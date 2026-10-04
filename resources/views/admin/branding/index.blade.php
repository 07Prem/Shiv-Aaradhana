@extends('layouts.admin')

@section('title', 'Branding & Website Settings')
@section('header', 'Branding & Storefront Visual Management')

@section('content')

<!-- Header Description -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h2 class="text-sm font-semibold text-stone-700">Centralized Storefront Branding &amp; Visual Presentation</h2>
        <p class="text-xs text-stone-500 mt-0.5">Manage the homepage hero imagery, corporate logos, and browser favicon across the platform.</p>
    </div>
    <a href="{{ route('home') }}" target="_blank" class="px-3.5 py-1.5 rounded-lg bg-stone-100 hover:bg-[#091433] hover:text-white text-stone-700 text-xs font-bold transition-colors inline-flex items-center gap-1.5 self-start sm:self-auto border border-stone-200">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
        <span>Preview Public Website</span>
    </a>
</div>

<!-- Tabs Navigation -->
<div class="border-b border-stone-200 mb-6 flex gap-4" x-data="{ tab: '{{ $activeTab }}' }">
    <button @click="tab = 'hero'" 
            :class="tab === 'hero' ? 'border-[#9C451B] text-[#9C451B] font-bold' : 'border-transparent text-stone-500 hover:text-stone-700'"
            class="pb-3 px-1 border-b-2 text-xs uppercase tracking-wider transition-colors inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        <span>Homepage Hero Visuals</span>
    </button>
    <button @click="tab = 'branding'" 
            :class="tab === 'branding' ? 'border-[#9C451B] text-[#9C451B] font-bold' : 'border-transparent text-stone-500 hover:text-stone-700'"
            class="pb-3 px-1 border-b-2 text-xs uppercase tracking-wider transition-colors inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
        <span>Company Logos &amp; Favicon</span>
    </button>
</div>

<!-- ========================================== -->
<!-- 1. HOMEPAGE HERO SECTION -->
<!-- ========================================== -->
<div x-show="tab === 'hero'" class="space-y-6">
    <form action="{{ route('admin.branding.hero.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left: Hero Image Management -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- Desktop Hero Image Card -->
                <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm space-y-4" x-data="{ previewUrl: null }">
                    <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                        <div>
                            <span class="text-xs uppercase font-bold tracking-wider text-[#9C451B] block">Primary Visual</span>
                            <h3 class="font-heading font-bold text-base text-[#091433]">Desktop Hero Image</h3>
                        </div>
                        <span class="text-[11px] text-stone-400">Rec: 1920 &times; 1080px (Max 8MB, JPG/PNG/WebP)</span>
                    </div>

                    <!-- Current Image Preview -->
                    <div class="relative rounded-xl overflow-hidden bg-stone-900 border border-stone-200">
                        <template x-if="previewUrl">
                            <img :src="previewUrl" alt="New Desktop Preview" class="w-full h-56 sm:h-64 object-cover">
                        </template>
                        <template x-if="!previewUrl">
                            <img src="{{ $heroData['desktop'] }}" alt="{{ $heroData['alt'] }}" class="w-full h-56 sm:h-64 object-cover">
                        </template>

                        <div class="absolute bottom-3 left-3 bg-[#091433]/85 text-white px-3 py-1 rounded-md text-[11px] backdrop-blur-sm border border-white/10">
                            @if($heroData['has_custom_desktop'])
                                <span class="text-emerald-400 font-semibold">&bull; Active Custom Image</span>
                            @else
                                <span class="text-amber-300 font-semibold">&bull; Default Asset Fallback</span>
                            @endif
                        </div>
                    </div>

                    <!-- Controls -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center pt-2">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">
                                Replace Desktop Image
                            </label>
                            <input type="file" 
                                   name="hero_image_desktop" 
                                   accept="image/jpeg,image/png,image/webp" 
                                   @change="previewUrl = URL.createObjectURL($event.target.files[0])"
                                   class="block w-full text-xs text-stone-500 file:mr-3 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-[#091433] file:text-white hover:file:bg-[#394F3D] cursor-pointer">
                        </div>

                        @if($heroData['has_custom_desktop'])
                        <div class="sm:text-right">
                            <label class="inline-flex items-center gap-2 cursor-pointer text-xs text-rose-600 hover:text-rose-800 font-medium">
                                <input type="checkbox" name="remove_desktop_image" value="1" class="rounded border-rose-300 text-rose-600">
                                <span>Remove Custom Image (Revert to Fallback)</span>
                            </label>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Mobile Hero Image Card -->
                <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm space-y-4" x-data="{ previewMobileUrl: null }">
                    <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                        <div>
                            <span class="text-xs uppercase font-bold tracking-wider text-[#9C451B] block">Mobile Optimization</span>
                            <h3 class="font-heading font-bold text-base text-[#091433]">Mobile Hero Image (Optional)</h3>
                        </div>
                        <span class="text-[11px] text-stone-400">Rec: 800 &times; 1000px portrait</span>
                    </div>

                    <!-- Current Mobile Preview -->
                    <div class="relative rounded-xl overflow-hidden bg-stone-900 border border-stone-200 max-w-xs mx-auto">
                        <template x-if="previewMobileUrl">
                            <img :src="previewMobileUrl" alt="New Mobile Preview" class="w-full h-48 object-cover">
                        </template>
                        <template x-if="!previewMobileUrl">
                            <img src="{{ $heroData['mobile'] }}" alt="{{ $heroData['alt'] }}" class="w-full h-48 object-cover">
                        </template>

                        <div class="absolute bottom-2 left-2 bg-[#091433]/85 text-white px-2.5 py-0.5 rounded text-[10px] backdrop-blur-sm border border-white/10">
                            @if($heroData['has_custom_mobile'])
                                <span class="text-emerald-400 font-semibold">&bull; Custom Mobile</span>
                            @else
                                <span class="text-stone-300 font-semibold">&bull; Inherits Desktop</span>
                            @endif
                        </div>
                    </div>

                    <!-- Mobile Controls -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center pt-2">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">
                                Upload Mobile Variant
                            </label>
                            <input type="file" 
                                   name="hero_image_mobile" 
                                   accept="image/jpeg,image/png,image/webp" 
                                   @change="previewMobileUrl = URL.createObjectURL($event.target.files[0])"
                                   class="block w-full text-xs text-stone-500 file:mr-3 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-[#091433] file:text-white hover:file:bg-[#394F3D] cursor-pointer">
                        </div>

                        @if($heroData['has_custom_mobile'])
                        <div class="sm:text-right">
                            <label class="inline-flex items-center gap-2 cursor-pointer text-xs text-rose-600 hover:text-rose-800 font-medium">
                                <input type="checkbox" name="remove_mobile_image" value="1" class="rounded border-rose-300 text-rose-600">
                                <span>Remove Mobile Variant</span>
                            </label>
                        </div>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Right: Hero SEO & Copy Settings -->
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm space-y-4">
                    <div class="pb-3 border-b border-stone-100">
                        <span class="text-xs uppercase font-bold tracking-wider text-[#9C451B] block">Accessibility &amp; SEO</span>
                        <h3 class="font-heading font-bold text-base text-[#091433]">Image Alt Text &amp; Copy</h3>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">
                            Hero Image Alt Text *
                        </label>
                        <input type="text" 
                               name="hero_image_alt" 
                               value="{{ old('hero_image_alt', $heroData['alt']) }}" 
                               required 
                               placeholder="e.g. Shiv Aaradhana Agricultural Heritage and Sesame Seed Processing" 
                               class="w-full px-3.5 py-2.5 rounded-lg border border-stone-300 text-xs focus:ring-2 focus:ring-[#9C451B]">
                        <span class="text-[11px] text-stone-400 mt-1 block">Accurately describes image for international buyers and search engines.</span>
                    </div>

                    <div class="pt-2 border-t border-stone-100">
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">
                            Hero Headline Title
                        </label>
                        <input type="text" 
                               name="title" 
                               value="{{ old('title', $heroSection?->title ?? 'From Indian Roots to Global Markets.') }}" 
                               class="w-full px-3.5 py-2.5 rounded-lg border border-stone-300 text-xs focus:ring-2 focus:ring-[#9C451B]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">
                            Hero Subtitle / Heritage Tag
                        </label>
                        <input type="text" 
                               name="subtitle" 
                               value="{{ old('subtitle', $heroSection?->subtitle ?? 'Shiv Aaradhana Private Limited') }}" 
                               class="w-full px-3.5 py-2.5 rounded-lg border border-stone-300 text-xs focus:ring-2 focus:ring-[#9C451B]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">
                            Hero Lead Narrative
                        </label>
                        <textarea name="content" 
                                  rows="4" 
                                  class="w-full px-3.5 py-2.5 rounded-lg border border-stone-300 text-xs focus:ring-2 focus:ring-[#9C451B]">{{ old('content', $heroSection?->content) }}</textarea>
                    </div>

                    <div class="pt-4 border-t border-stone-100">
                        <button type="submit" class="w-full py-3 rounded-lg bg-[#091433] hover:bg-[#394F3D] text-white font-bold text-xs shadow-md transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Save Hero Visuals &amp; Content</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<!-- ========================================== -->
<!-- 2. COMPANY BRANDING & LOGOS SECTION -->
<!-- ========================================== -->
<div x-show="tab === 'branding'" class="space-y-6">
    <form action="{{ route('admin.branding.logo.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left: Main and Light Logos -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Main Logo (For Light Backgrounds) -->
                <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm space-y-4" x-data="{ previewMainLogo: null }">
                    <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                        <div>
                            <span class="text-xs uppercase font-bold tracking-wider text-[#9C451B] block">General Branding</span>
                            <h3 class="font-heading font-bold text-base text-[#091433]">Main Company Logo</h3>
                        </div>
                        <span class="text-[11px] text-stone-400">PNG, SVG, or WebP with transparent background</span>
                    </div>

                    <div class="p-6 rounded-xl bg-stone-50 border border-dashed border-stone-300 flex flex-col sm:flex-row items-center justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <template x-if="previewMainLogo">
                                <img :src="previewMainLogo" alt="Main Logo Preview" class="h-14 w-auto max-w-[200px] object-contain">
                            </template>
                            <template x-if="!previewMainLogo">
                                @if(!empty($branding['main_logo']))
                                    <img src="{{ $branding['main_logo'] }}" alt="Current Main Logo" class="h-14 w-auto max-w-[200px] object-contain">
                                @else
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-lg bg-[#394F3D] border border-[#EBD6B4] flex items-center justify-center font-heading font-bold text-[#EBD6B4] text-xl shadow">
                                            SA
                                        </div>
                                        <div>
                                            <span class="font-heading font-bold text-stone-800 text-sm block">Shiv Aaradhana</span>
                                            <span class="text-[10px] text-stone-400 uppercase font-semibold">Default Fallback Monogram</span>
                                        </div>
                                    </div>
                                @endif
                            </template>
                        </div>

                        <div class="w-full sm:w-auto space-y-2">
                            <input type="file" 
                                   name="main_logo" 
                                   accept="image/png,image/jpeg,image/webp,image/svg+xml" 
                                   @change="previewMainLogo = URL.createObjectURL($event.target.files[0])"
                                   class="block w-full text-xs text-stone-500 file:mr-3 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-[#091433] file:text-white hover:file:bg-[#394F3D] cursor-pointer">
                            
                            @if(!empty($branding['main_logo']))
                            <label class="inline-flex items-center gap-2 cursor-pointer text-xs text-rose-600 hover:text-rose-800 font-medium">
                                <input type="checkbox" name="remove_main_logo" value="1" class="rounded border-rose-300 text-rose-600">
                                <span>Remove Custom Main Logo (Revert to Fallback)</span>
                            </label>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Light Logo (For Dark Backgrounds like Navbar & Footer) -->
                <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm space-y-4" x-data="{ previewLightLogo: null }">
                    <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                        <div>
                            <span class="text-xs uppercase font-bold tracking-wider text-[#9C451B] block">Dark Header &amp; Footer</span>
                            <h3 class="font-heading font-bold text-base text-[#091433]">Light Logo Variant (White / Gold / Inverted)</h3>
                        </div>
                        <span class="text-[11px] text-stone-400">Rendered on #091433 navy navigation bar</span>
                    </div>

                    <div class="p-6 rounded-xl bg-[#091433] border border-stone-800 flex flex-col sm:flex-row items-center justify-between gap-6 text-white">
                        <div class="flex items-center gap-4">
                            <template x-if="previewLightLogo">
                                <img :src="previewLightLogo" alt="Light Logo Preview" class="h-12 w-auto max-w-[200px] object-contain">
                            </template>
                            <template x-if="!previewLightLogo">
                                @if(!empty($branding['light_logo']))
                                    <img src="{{ $branding['light_logo'] }}" alt="Current Light Logo" class="h-12 w-auto max-w-[200px] object-contain">
                                @elseif(!empty($branding['main_logo']))
                                    <img src="{{ $branding['main_logo'] }}" alt="Main Logo Fallback" class="h-12 w-auto max-w-[200px] object-contain">
                                @else
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-[#394F3D] to-[#091433] border border-[#EBD6B4]/40 flex items-center justify-center font-heading font-bold text-[#EBD6B4] text-lg">
                                            SA
                                        </div>
                                        <div>
                                            <span class="font-heading font-bold text-white text-sm block">SHIV AARADHANA</span>
                                            <span class="text-[10px] text-[#EBD6B4]/80 tracking-wider uppercase block">Storefront Fallback</span>
                                        </div>
                                    </div>
                                @endif
                            </template>
                        </div>

                        <div class="w-full sm:w-auto space-y-2">
                            <input type="file" 
                                   name="light_logo" 
                                   accept="image/png,image/jpeg,image/webp,image/svg+xml" 
                                   @change="previewLightLogo = URL.createObjectURL($event.target.files[0])"
                                   class="block w-full text-xs text-stone-400 file:mr-3 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-white/10 file:text-[#EBD6B4] hover:file:bg-white/20 cursor-pointer">
                            
                            @if(!empty($branding['light_logo']))
                            <label class="inline-flex items-center gap-2 cursor-pointer text-xs text-rose-300 hover:text-rose-100 font-medium">
                                <input type="checkbox" name="remove_light_logo" value="1" class="rounded border-rose-300 text-rose-600">
                                <span>Remove Light Logo Variant</span>
                            </label>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right: Favicon & Brand Details -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Favicon Card -->
                <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm space-y-4" x-data="{ previewFavicon: null }">
                    <div class="pb-3 border-b border-stone-100">
                        <span class="text-xs uppercase font-bold tracking-wider text-[#9C451B] block">Browser Tab Icon</span>
                        <h3 class="font-heading font-bold text-base text-[#091433]">Favicon</h3>
                    </div>

                    <div class="p-4 rounded-xl bg-stone-50 border border-stone-200 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-lg bg-white border border-stone-300 flex items-center justify-center p-1 shadow-sm">
                            <template x-if="previewFavicon">
                                <img :src="previewFavicon" alt="Favicon Preview" class="w-8 h-8 object-contain">
                            </template>
                            <template x-if="!previewFavicon">
                                @if(!empty($branding['favicon']))
                                    <img src="{{ $branding['favicon'] }}" alt="Current Favicon" class="w-8 h-8 object-contain">
                                @else
                                    <span class="text-xs text-stone-400 font-bold font-mono">SA</span>
                                @endif
                            </template>
                        </div>
                        <div class="text-xs">
                            <span class="font-bold text-stone-800 block">Tab Icon (32 &times; 32px)</span>
                            <span class="text-stone-400 text-[11px]">Accepts ICO, PNG, SVG</span>
                        </div>
                    </div>

                    <div>
                        <input type="file" 
                               name="favicon" 
                               accept="image/x-icon,image/vnd.microsoft.icon,image/png,image/svg+xml" 
                               @change="previewFavicon = URL.createObjectURL($event.target.files[0])"
                               class="block w-full text-xs text-stone-500 file:mr-3 file:py-1.5 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-[#091433] file:text-white hover:file:bg-[#394F3D] cursor-pointer">
                        
                        @if(!empty($branding['favicon']))
                        <label class="inline-flex items-center gap-2 cursor-pointer text-xs text-rose-600 hover:text-rose-800 font-medium mt-2">
                            <input type="checkbox" name="remove_favicon" value="1" class="rounded border-rose-300 text-rose-600">
                            <span>Remove Custom Favicon</span>
                        </label>
                        @endif
                    </div>
                </div>

                <!-- Brand Name / Presentation -->
                <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">
                            Official Entity Display Name
                        </label>
                        <input type="text" 
                               name="company_name" 
                               value="{{ old('company_name', $branding['company_name']) }}" 
                               class="w-full px-3.5 py-2.5 rounded-lg border border-stone-300 text-xs focus:ring-2 focus:ring-[#9C451B]">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-3 rounded-lg bg-[#091433] hover:bg-[#394F3D] text-white font-bold text-xs shadow-md transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Save Branding &amp; Logo Settings</span>
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </form>
</div>

@endsection
