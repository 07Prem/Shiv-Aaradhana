@extends('layouts.admin')

@section('title', 'Edit Product')
@section('header', "Edit Commodity: {$product->name}")

@section('content')

<div class="max-w-4xl bg-white p-8 rounded-2xl border border-stone-200 shadow-sm">
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

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
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Product Line / Type *</label>
                <select name="product_type_id" required class="w-full px-3.5 py-2.5 rounded-lg border border-stone-300 text-sm focus:ring-2 focus:ring-[#9C451B]">
                    @foreach($productTypes as $pt)
                        <option value="{{ $pt->id }}" {{ old('product_type_id', $product->product_type_id) == $pt->id ? 'selected' : '' }}>{{ $pt->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Product Commercial Name *</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full px-3.5 py-2.5 rounded-lg border border-stone-300 text-sm focus:ring-2 focus:ring-[#9C451B]">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">HS Code</label>
                <input type="text" name="hs_code" value="{{ old('hs_code', $product->hs_code) }}" class="w-full px-3.5 py-2.5 rounded-lg border border-stone-300 text-sm focus:ring-2 focus:ring-[#9C451B]">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Slug *</label>
                <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" required class="w-full px-3.5 py-2.5 rounded-lg border border-stone-300 text-sm focus:ring-2 focus:ring-[#9C451B]">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Geographical Origin *</label>
                <input type="text" name="origin" value="{{ old('origin', $product->origin) }}" required class="w-full px-3.5 py-2.5 rounded-lg border border-stone-300 text-sm focus:ring-2 focus:ring-[#9C451B]">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Short Description (Summary) *</label>
            <textarea name="short_description" rows="2" required class="w-full px-3.5 py-2.5 rounded-lg border border-stone-300 text-sm focus:ring-2 focus:ring-[#9C451B]">{{ old('short_description', $product->short_description) }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Full Export Specification & Description *</label>
            <textarea name="description" rows="5" required class="w-full px-3.5 py-2.5 rounded-lg border border-stone-300 text-sm focus:ring-2 focus:ring-[#9C451B]">{{ old('description', $product->description) }}</textarea>
        </div>

        <!-- Commercial Terms -->
        <div class="p-5 rounded-xl bg-stone-50 border border-stone-200 space-y-4">
            <h4 class="text-xs font-bold uppercase tracking-wider text-[#091433]">Commercial Terms & Packaging</h4>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-stone-600 mb-1">Harvest Season</label>
                    <input type="text" name="harvest_season" value="{{ old('harvest_season', $product->harvest_season) }}" class="w-full px-3 py-2 rounded-lg border border-stone-300 text-sm bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-stone-600 mb-1">Minimum Order Qty (MOQ)</label>
                    <input type="text" name="minimum_order_qty" value="{{ old('minimum_order_qty', $product->minimum_order_qty) }}" class="w-full px-3 py-2 rounded-lg border border-stone-300 text-sm bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-stone-600 mb-1">Monthly Supply Capacity</label>
                    <input type="text" name="supply_capacity" value="{{ old('supply_capacity', $product->supply_capacity) }}" class="w-full px-3 py-2 rounded-lg border border-stone-300 text-sm bg-white">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-stone-600 mb-1">Standard Packaging Options</label>
                <input type="text" name="packaging_options" value="{{ old('packaging_options', $product->packaging_options) }}" class="w-full px-3 py-2 rounded-lg border border-stone-300 text-sm bg-white">
            </div>
        </div>

        <!-- Dynamic Specifications Builder -->
        <div class="p-5 rounded-xl bg-[#FAF5ED] border border-[#EBD6B4] space-y-4">
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-[#091433]">Laboratory & Quality Specifications</h4>
                <p class="text-[11px] text-stone-500">Edit tested values. Leave blank to hide from public spec sheet.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($attributeDefinitions as $attr)
                @php
                    $valModel = isset($attributeValues) ? $attributeValues->get($attr->id) : null;
                    $rawVal = $valModel?->input_value ?? ($existingValues[$attr->id] ?? null);
                    if ($rawVal === null && isset($existingNumValues[$attr->id]) && $existingNumValues[$attr->id] !== null && $existingNumValues[$attr->id] !== '') {
                        $numStr = (string)$existingNumValues[$attr->id];
                        $rawVal = str_contains($numStr, '.') ? (rtrim(rtrim($numStr, '0'), '.') ?: '0') : $numStr;
                    }
                    $currentVal = $rawVal ?? '';
                @endphp
                <div>
                    <label class="block text-xs font-bold text-stone-700 mb-1">
                        {{ $attr->name }} {{ $attr->unit ? '(' . $attr->unit . ')' : '' }}
                    </label>
                    <input
                        type="text"
                        name="attributes[{{ $attr->id }}]"
                        value="{{ old('attributes.' . $attr->id, $currentVal) }}"
                        class="w-full px-3 py-2 rounded-lg border border-stone-300 text-sm bg-white"
                    >
                </div>
                @endforeach
            </div>
        </div>

        <!-- Image & Lifecycle -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Replace Image</label>
                @if($product->primary_image)
                    <div class="mb-2 flex items-center gap-2">
                        <img src="{{ $product->primary_image }}" alt="" class="w-12 h-12 rounded object-cover border">
                        <span class="text-[11px] text-stone-500">Current active image</span>
                    </div>
                @endif
                <input type="file" name="primary_image" accept="image/*" class="w-full text-xs text-stone-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-stone-200 file:text-stone-700 hover:file:bg-stone-300">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Lifecycle Status *</label>
                <select name="status" required class="w-full px-3.5 py-2 rounded-lg border border-stone-300 text-sm">
                    <option value="draft" {{ old('status', $product->status) === 'draft' ? 'selected' : '' }}>Draft (Private)</option>
                    <option value="published" {{ old('status', $product->status) === 'published' ? 'selected' : '' }}>Published (Live on Catalog)</option>
                    <option value="archived" {{ old('status', $product->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>

            <div class="pt-5">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="rounded border-stone-300 text-[#9C451B]">
                    <span class="text-xs font-bold text-stone-800">Feature on Storefront Homepage</span>
                </label>
            </div>
        </div>

        <div class="pt-4 border-t border-stone-200 flex items-center gap-4">
            <button type="submit" class="px-8 py-3 rounded-lg bg-[#091433] hover:bg-[#394F3D] text-white text-xs font-bold shadow-md transition-colors">
                Update Product
            </button>
            <a href="{{ route('admin.products.index') }}" class="px-5 py-3 rounded-lg border border-stone-300 text-stone-700 hover:bg-stone-50 text-xs font-medium">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection
