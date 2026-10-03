@extends('layouts.storefront')

@section('title', 'About Our Heritage & Operations — Shiv Aaradhana Private Limited')
@section('meta_description', 'Learn about Shiv Aaradhana Private Limited, our fifth-generation agricultural roots in Gujarat, India, and our commitment to ethical sourcing and global trade.')

@section('content')

<!-- About Hero -->
<section class="bg-[#091433] text-white py-16 md:py-24 border-b border-stone-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl space-y-4">
            <span class="text-xs uppercase tracking-widest font-bold text-[#EBD6B4] block">5th-Generation Agrarian Lineage</span>
            <h1 class="text-3xl sm:text-5xl font-heading font-extrabold text-white tracking-tight">
                Rooted in Indian Soil, Delivering to World Markets
            </h1>
            <p class="text-sm sm:text-base text-stone-300 font-light leading-relaxed">
                Shiv Aaradhana Private Limited bridges traditional Indian agricultural heritage with modern international export logistics, delivering purity, transparency, and consistency.
            </p>
        </div>
    </div>
</section>

<!-- Heritage Story -->
<section class="py-20 bg-white border-b border-stone-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <div class="lg:col-span-6 space-y-6">
                <span class="text-xs uppercase tracking-widest font-bold text-[#9C451B]">Our Origin</span>
                <h2 class="text-2xl sm:text-4xl font-heading font-bold text-[#091433] tracking-tight">
                    Five Generations of Farming Knowledge in Saurashtra, Gujarat
                </h2>
                <div class="w-16 h-1 bg-[#9C451B] rounded"></div>
                <div class="text-sm sm:text-base text-stone-600 space-y-4 leading-relaxed">
                    <p>
                        Shiv Aaradhana is a family-owned enterprise deeply grounded in the agrarian culture of Gujarat, India. Across five generations, our family has cultivated the land, traded in regional mandis, and nurtured trusted ties with farming communities across Saurashtra.
                    </p>
                    <p>
                        Recognizing the growing global demand for authentic Indian agricultural produce, spices, and natural cotton fibres, Shiv Aaradhana Private Limited was established to formalize international trade corridors, offering overseas buyers direct access to origin-verified commodities.
                    </p>
                </div>
            </div>

            <div class="lg:col-span-6">
                <div class="p-8 sm:p-10 rounded-3xl bg-[#FAF5ED] border border-[#EBD6B4] space-y-6">
                    <h3 class="font-heading font-bold text-xl text-[#091433]">Agricultural Ecosystem in Gujarat</h3>
                    <p class="text-xs sm:text-sm text-stone-600 leading-relaxed">
                        Gujarat stands at the forefront of Indian agricultural exports, commanding dominant market shares in sesame seeds, groundnuts, cumin, fennel, and Shankar-6 cotton.
                    </p>
                    
                    <div class="grid grid-cols-2 gap-4 pt-2 text-xs">
                        <div class="p-3 bg-white rounded-xl border border-stone-200">
                            <span class="font-bold text-[#091433] block">Farm-Gate Procurement</span>
                            <span class="text-stone-500 mt-1 block">Selected direct harvest aggregation across Rajkot, Jamnagar, and Gondal belts.</span>
                        </div>
                        <div class="p-3 bg-white rounded-xl border border-stone-200">
                            <span class="font-bold text-[#091433] block">Optical Cleaning & Sizing</span>
                            <span class="text-stone-500 mt-1 block">Advanced multi-stage Sortex grading and magnetic separation.</span>
                        </div>
                        <div class="p-3 bg-white rounded-xl border border-stone-200">
                            <span class="font-bold text-[#091433] block">Moisture & Purity Labs</span>
                            <span class="text-stone-500 mt-1 block">Verified batch testing for volatile oil, moisture, and absence of adulterants.</span>
                        </div>
                        <div class="p-3 bg-white rounded-xl border border-stone-200">
                            <span class="font-bold text-[#091433] block">Port Proximity</span>
                            <span class="text-stone-500 mt-1 block">Rapid container transit to Mundra and Kandla ports within 24-48 hours.</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Mission & Vision -->
<section class="py-20 bg-[#FAF5ED] border-b border-stone-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <!-- Mission -->
            <div class="p-8 sm:p-10 rounded-2xl bg-white border border-stone-200 shadow-sm space-y-4">
                <div class="w-12 h-12 rounded-xl bg-[#394F3D]/10 text-[#394F3D] flex items-center justify-center font-bold text-xl">
                    M
                </div>
                <h3 class="text-2xl font-heading font-bold text-[#091433]">Our Mission</h3>
                <p class="text-sm text-stone-600 leading-relaxed">
                    Bring India's agricultural richness to global markets while supporting rural livelihoods, preserving farming traditions and maintaining high quality standards.
                </p>
            </div>

            <!-- Vision -->
            <div class="p-8 sm:p-10 rounded-2xl bg-[#091433] text-white shadow-xl space-y-4 border border-stone-800">
                <div class="w-12 h-12 rounded-xl bg-[#EBD6B4]/20 text-[#EBD6B4] flex items-center justify-center font-bold text-xl">
                    V
                </div>
                <h3 class="text-2xl font-heading font-bold text-[#EBD6B4]">Our Vision</h3>
                <p class="text-sm text-stone-300 leading-relaxed">
                    Become a globally trusted export brand recognized for quality excellence, ethical sourcing and sustainable growth.
                </p>
            </div>
        </div>

        <!-- Values Grid -->
        <div class="mt-16 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="p-6 rounded-xl bg-white border border-stone-200 space-y-2">
                <h4 class="font-heading font-bold text-base text-[#091433]">Integrity & Transparency</h4>
                <p class="text-xs text-stone-500 leading-relaxed">No altered specifications, realistic delivery schedules, and verifiable laboratory parameters.</p>
            </div>
            <div class="p-6 rounded-xl bg-white border border-stone-200 space-y-2">
                <h4 class="font-heading font-bold text-base text-[#091433]">Farming Stewardship</h4>
                <p class="text-xs text-stone-500 leading-relaxed">Long-term relationships with grower cooperatives ensuring fair farmer returns and reliable crop access.</p>
            </div>
            <div class="p-6 rounded-xl bg-white border border-stone-200 space-y-2">
                <h4 class="font-heading font-bold text-base text-[#091433]">Global Compliance</h4>
                <p class="text-xs text-stone-500 leading-relaxed">Adherence to phytosanitary certificates, non-GMO documentation, and destination country health regulations.</p>
            </div>
            <div class="p-6 rounded-xl bg-white border border-stone-200 space-y-2">
                <h4 class="font-heading font-bold text-base text-[#091433]">Logistics Precision</h4>
                <p class="text-xs text-stone-500 leading-relaxed">Careful container stuffing, moisture barrier lining, and synchronized customs dispatch.</p>
            </div>
        </div>

    </div>
</section>

<!-- Registered Location & Contact Callout -->
<section class="py-16 bg-white text-center">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
        <h3 class="text-2xl font-heading font-bold text-[#091433]">
            Discuss Your Import Requirements with Our Trade Officers
        </h3>
        <p class="text-xs sm:text-sm text-stone-600 leading-relaxed">
            Headquartered at Movaiya Circle, Rajkot-Jamnagar Highway, Gujarat. We coordinate sample dispatches, container stuffing inspections, and CIF/FOB pricing.
        </p>
        <div class="pt-2">
            <a href="{{ route('contact') }}" class="px-6 py-3 rounded-lg bg-[#9C451B] hover:bg-[#b85322] text-white text-xs font-bold shadow-md transition-colors inline-block">
                View Contact Office Details &rarr;
            </a>
        </div>
    </div>
</section>

@endsection
