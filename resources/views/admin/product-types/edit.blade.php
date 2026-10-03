@extends('layouts.admin')

@section('title', 'Edit Product Line')
@section('header', "Edit Line: {$productType->name}")

@section('content')

<div class="max-w-2xl bg-white p-8 rounded-2xl border border-stone-200 shadow-sm">
    <form action="{{ route('admin.product-types.update', $productType->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Parent Category *</label>
            <select name="category_id" required class="w-full px-3.5 py-2 rounded-lg border border-stone-300 text-sm focus:ring-2 focus:ring-[#9C451B]">
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $productType->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Product Line Name *</label>
            <input type="text" name="name" value="{{ old('name', $productType->name) }}" required class="w-full px-3.5 py-2 rounded-lg border border-stone-300 text-sm focus:ring-2 focus:ring-[#9C451B]">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Slug *</label>
            <input type="text" name="slug" value="{{ old('slug', $productType->slug) }}" required class="w-full px-3.5 py-2 rounded-lg border border-stone-300 text-sm focus:ring-2 focus:ring-[#9C451B]">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Description</label>
            <textarea name="description" rows="3" class="w-full px-3.5 py-2 rounded-lg border border-stone-300 text-sm focus:ring-2 focus:ring-[#9C451B]">{{ old('description', $productType->description) }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $productType->sort_order) }}" class="w-full px-3.5 py-2 rounded-lg border border-stone-300 text-sm focus:ring-2 focus:ring-[#9C451B]">
            </div>
            <div class="flex items-center pt-6">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $productType->is_active) ? 'checked' : '' }} class="rounded border-stone-300 text-[#9C451B]">
                    <span class="text-xs font-bold uppercase text-stone-700">Active</span>
                </label>
            </div>
        </div>

        <div class="pt-4 flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 rounded-lg bg-[#091433] hover:bg-[#394F3D] text-white text-xs font-bold transition-colors">
                Update Product Line
            </button>
            <a href="{{ route('admin.product-types.index') }}" class="px-5 py-2.5 rounded-lg border border-stone-300 text-stone-700 hover:bg-stone-50 text-xs font-medium">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection
