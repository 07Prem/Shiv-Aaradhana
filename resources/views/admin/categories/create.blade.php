@extends('layouts.admin')

@section('title', 'Add Category')
@section('header', 'Create New Commodity Category')

@section('content')

<div class="max-w-2xl bg-white p-8 rounded-2xl border border-stone-200 shadow-sm">
    <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Category Name *</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3.5 py-2 rounded-lg border border-stone-300 text-sm focus:ring-2 focus:ring-[#9C451B]">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Slug (Optional, auto-generated)</label>
            <input type="text" name="slug" value="{{ old('slug') }}" class="w-full px-3.5 py-2 rounded-lg border border-stone-300 text-sm focus:ring-2 focus:ring-[#9C451B]">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Parent Category</label>
            <select name="parent_id" class="w-full px-3.5 py-2 rounded-lg border border-stone-300 text-sm focus:ring-2 focus:ring-[#9C451B]">
                <option value="">-- None (Top-Level Category) --</option>
                @foreach($parents as $p)
                    <option value="{{ $p->id }}" {{ old('parent_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
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
                    <span class="text-xs font-bold uppercase text-stone-700">Active & Published</span>
                </label>
            </div>
        </div>

        <div class="pt-4 flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 rounded-lg bg-[#091433] hover:bg-[#394F3D] text-white text-xs font-bold transition-colors">
                Save Category
            </button>
            <a href="{{ route('admin.categories.index') }}" class="px-5 py-2.5 rounded-lg border border-stone-300 text-stone-700 hover:bg-stone-50 text-xs font-medium">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection
