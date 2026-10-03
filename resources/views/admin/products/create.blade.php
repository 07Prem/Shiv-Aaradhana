@extends('layouts.admin')

@section('title', 'Add Export Product')
@section('header', 'Create Export Commodity')

@section('content')

<div class="max-w-4xl bg-white p-8 rounded-2xl border border-stone-200 shadow-sm">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
            <span class="font-bold block mb-1">Please fix the following validation errors:</span>
            <ul class="list-disc pl-4 space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Commodity Category *</label>
                <select name="category_id" required class="w-full px-3.5 py-2.5 rounded-lg border border-stone-300 text-sm focus:ring-2 focus:ring-[#9C451B]">
                    <option value="">-- Select Category --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Product Line / Type *</label>
                <select name="product_type_id" required class="w-full px-3.5 py-2.5 rounded-lg border border-stone-300 text-sm focus:ring-2 focus:ring-[#9C451B]">
                    <option value="">-- Select Product Type --</option>
                    @foreach($productTypes as $pt)
                        <option value="{{ $pt->id }}" {{ old('product_type_id') == $pt->id ? 'selected' : '' }}>{{ $pt->name }} ({{ $pt->category->name ?? '' }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Product Commercial Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Natural White Sesame Seeds (99/1)" class="w-full px-3.5 py-2.5 rounded-lg border border-stone-300 text-sm focus:ring-2 focus:ring-[#9C451B]">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">HS Code</label>
                <input type="text" name="hs_code" value="{{ old('hs_code') }}" placeholder="e.g. 12074090" class="w-full px-3.5 py-2.5 rounded-lg border border-stone-300 text-sm focus:ring-2 focus:ring-[#9C451B]">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Slug (Optional, auto-generated)</label>
                <input type="text" name="slug" value="{{ old('slug') }}" class="w-full px-3.5 py-2.5 rounded-lg border border-stone-300 text-sm focus:ring-2 focus:ring-[#9C451B]">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Geographical Origin *</label>
                <input type="text" name="origin" value="{{ old('origin', 'Gujarat, India') }}" required class="w-full px-3.5 py-2.5 rounded-lg border border-stone-300 text-sm focus:ring-2 focus:ring-[#9C451B]">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Short Description (Summary) *</label>
            <textarea name="short_description" rows="2" required placeholder="Concise summary for catalog listings..." class="w-full px-3.5 py-2.5 rounded-lg border border-stone-300 text-sm focus:ring-2 focus:ring-[#9C451B]">{{ old('short_description') }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Full Export Specification & Description *</label>
            <textarea name="description" rows="5" required placeholder="In-depth details on sourcing, optical grading, purity, and commercial application..." class="w-full px-3.5 py-2.5 rounded-lg border border-stone-300 text-sm focus:ring-2 focus:ring-[#9C451B]">{{ old('description') }}</textarea>
        </div>

        <!-- Commercial Parameters -->
        <div class="p-5 rounded-xl bg-stone-50 border border-stone-200 space-y-4">
            <h4 class="text-xs font-bold uppercase tracking-wider text-[#091433]">Commercial Terms & Packaging</h4>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-stone-600 mb-1">Harvest Season</label>
                    <input type="text" name="harvest_season" value="{{ old('harvest_season') }}" placeholder="e.g. October – January" class="w-full px-3 py-2 rounded-lg border border-stone-300 text-sm bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-stone-600 mb-1">Minimum Order Qty (MOQ)</label>
                    <input type="text" name="minimum_order_qty" value="{{ old('minimum_order_qty') }}" placeholder="e.g. 1 x 20ft FCL (19 MT)" class="w-full px-3 py-2 rounded-lg border border-stone-300 text-sm bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-stone-600 mb-1">Monthly Supply Capacity</label>
                    <input type="text" name="supply_capacity" value="{{ old('supply_capacity') }}" placeholder="e.g. 500 Metric Tons / Month" class="w-full px-3 py-2 rounded-lg border border-stone-300 text-sm bg-white">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-stone-600 mb-1">Standard Packaging Options</label>
                <input type="text" name="packaging_options" value="{{ old('packaging_options') }}" placeholder="e.g. 25kg / 50kg PP Bags, Multi-wall Paper Bags with PE liner" class="w-full px-3 py-2 rounded-lg border border-stone-300 text-sm bg-white">
            </div>
        </div>

        <!-- Dynamic Specifications Builder -->
        <div class="p-5 rounded-xl bg-[#FAF5ED] border border-[#EBD6B4] space-y-4">
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-[#091433]">Laboratory & Quality Specifications</h4>
                <p class="text-[11px] text-stone-500">Only populated parameters will be displayed on the public spec sheet.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($attributeDefinitions as $attr)
                <div>
                    <label class="block text-xs font-bold text-stone-700 mb-1">
                        {{ $attr->name }} {{ $attr->unit ? '(' . $attr->unit . ')' : '' }}
                    </label>
                    <input type="text" name="attributes[{{ $attr->id }}]" value="{{ old('attributes.' . $attr->id) }}" placeholder="{{ $attr->type === 'number' ? 'e.g. 99.9' : 'e.g. Sortex Cleaned' }}" class="w-full px-3 py-2 rounded-lg border border-stone-300 text-sm bg-white">
                </div>
                @endforeach
            </div>
        </div>

        <!-- Media & Status -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Primary Image (JPG/PNG/WebP, max 5MB)</label>
                <input type="file" name="primary_image" accept="image/*" class="w-full text-xs text-stone-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-stone-200 file:text-stone-700 hover:file:bg-stone-300">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Lifecycle Status *</label>
                <select name="status" required class="w-full px-3.5 py-2 rounded-lg border border-stone-300 text-sm">
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft (Private)</option>
                    <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Published (Live on Catalog)</option>
                    <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>

            <div class="pt-5">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded border-stone-300 text-[#9C451B]">
                    <span class="text-xs font-bold text-stone-800">Feature on Storefront Homepage</span>
                </label>
            </div>
        </div>

        <div class="pt-4 border-t border-stone-200 flex items-center gap-4">
            <button type="submit" class="px-8 py-3 rounded-lg bg-[#091433] hover:bg-[#394F3D] text-white text-xs font-bold shadow-md transition-colors">
                Save & Publish Product
            </button>
            <a href="{{ route('admin.products.index') }}" class="px-5 py-3 rounded-lg border border-stone-300 text-stone-700 hover:bg-stone-50 text-xs font-medium">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection
