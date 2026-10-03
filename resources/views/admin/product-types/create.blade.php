@extends('layouts.admin')

@section('title', 'Add Product Line')
@section('header', 'Create Product Line')

@section('content')

<div class="max-w-2xl bg-white p-8 rounded-2xl border border-stone-200 shadow-sm">
    <form action="{{ route('admin.product-types.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Parent Category *</label>
            <select name="category_id" required class="w-full px-3.5 py-2 rounded-lg border border-stone-300 text-sm focus:ring-2 focus:ring-[#9C451B]">
                <option value="">-- Select Category --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Product Line Name *</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3.5 py-2 rounded-lg border border-stone-300 text-sm focus:ring-2 focus:ring-[#9C451B]">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Slug (Optional)</label>
            <input type="text" name="slug" value="{{ old('slug') }}" class="w-full px-3.5 py-2 rounded-lg border border-stone-300 text-sm focus:ring-2 focus:ring-[#9C451B]">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Description</label>
            <textarea name="description" rows="3" class="w-full px-3.5 py-2 rounded-lg border border-stone-300 text-sm focus:ring-2 focus:ring-[#9C451B]">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="w-full px-3.5 py-2 rounded-lg border border-stone-300 text-sm focus:ring-2 focus:ring-[#9C451B]">
            </div>
            <div class="flex items-center pt-6">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-stone-300 text-[#9C451B]">
                    <span class="text-xs font-bold uppercase text-stone-700">Active</span>
                </label>
            </div>
        </div>

        <div class="pt-4 flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 rounded-lg bg-[#091433] hover:bg-[#394F3D] text-white text-xs font-bold transition-colors">
                Save Product Line
            </button>
            <a href="{{ route('admin.product-types.index') }}" class="px-5 py-2.5 rounded-lg border border-stone-300 text-stone-700 hover:bg-stone-50 text-xs font-medium">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection
