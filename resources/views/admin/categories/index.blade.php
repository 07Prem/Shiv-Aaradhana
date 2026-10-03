@extends('layouts.admin')

@section('title', 'Manage Categories')
@section('header', 'Commodity Categories')

@section('content')

<div class="flex items-center justify-between mb-6">
    <p class="text-xs text-stone-500">Manage high-level export commodity departments and category hierarchy.</p>
    <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 rounded-lg bg-[#091433] hover:bg-[#394F3D] text-white text-xs font-bold transition-colors">
        + Add New Category
    </a>
</div>

<div class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-sm">
    <table class="w-full text-xs text-left">
        <thead>
            <tr class="bg-stone-50 text-stone-500 uppercase tracking-wider border-b border-stone-200">
                <th class="py-3 px-4">Category Name</th>
                <th class="py-3 px-4">Slug</th>
                <th class="py-3 px-4">Parent Category</th>
                <th class="py-3 px-4">Lines / Types</th>
                <th class="py-3 px-4">Active Products</th>
                <th class="py-3 px-4">Status</th>
                <th class="py-3 px-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100">
            @foreach($categories as $category)
            <tr class="hover:bg-stone-50 transition-colors">
                <td class="py-3.5 px-4 font-bold text-[#091433]">
                    {{ $category->name }}
                </td>
                <td class="py-3.5 px-4 font-mono text-stone-500">
                    {{ $category->slug }}
                </td>
                <td class="py-3.5 px-4 text-stone-600">
                    {{ $category->parent?->name ?? '— (Top-Level)' }}
                </td>
                <td class="py-3.5 px-4 font-semibold text-stone-700">
                    {{ $category->product_types_count }} lines
                </td>
                <td class="py-3.5 px-4 font-semibold text-stone-700">
                    {{ $category->products_count }} products
                </td>
                <td class="py-3.5 px-4">
                    @if($category->is_active)
                        <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[10px] font-bold">Active</span>
                    @else
                        <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-600 text-[10px] font-bold">Inactive</span>
                    @endif
                </td>
                <td class="py-3.5 px-4 text-right space-x-2">
                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="text-blue-600 hover:underline font-semibold">
                        Edit
                    </a>
                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-rose-600 hover:underline font-semibold">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $categories->links() }}
</div>

@endsection
