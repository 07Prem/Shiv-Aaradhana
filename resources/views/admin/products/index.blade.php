@extends('layouts.admin')

@section('title', 'Manage Products')
@section('header', 'Export Products Catalog')

@section('content')

<!-- Header & Actions -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <p class="text-xs text-stone-500">Maintain commodities, specifications, origin details, and publication states.</p>
    <a href="{{ route('admin.products.create') }}" class="px-4 py-2.5 rounded-lg bg-[#091433] hover:bg-[#394F3D] text-white text-xs font-bold transition-colors inline-block text-center">
        + Add Export Product
    </a>
</div>

<!-- Filters Bar -->
<div class="bg-white p-4 rounded-2xl border border-stone-200 mb-6 shadow-sm">
    <form action="{{ route('admin.products.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
        <div>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by name, HS code..." class="w-full px-3 py-2 rounded-lg border border-stone-300">
        </div>
        <div>
            <select name="category" class="w-full px-3 py-2 rounded-lg border border-stone-300">
                <option value="">-- All Categories --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <select name="status" class="w-full px-3 py-2 rounded-lg border border-stone-300">
                <option value="">-- All Statuses --</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 rounded-lg bg-stone-800 text-white font-semibold">Filter</button>
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 rounded-lg border border-stone-300 text-stone-600 hover:bg-stone-50 flex items-center justify-center">Reset</a>
        </div>
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-sm">
    <table class="w-full text-xs text-left">
        <thead>
            <tr class="bg-stone-50 text-stone-500 uppercase tracking-wider border-b border-stone-200">
                <th class="py-3 px-4">Commodity / Name</th>
                <th class="py-3 px-4">Category & Line</th>
                <th class="py-3 px-4">HS Code</th>
                <th class="py-3 px-4">Status</th>
                <th class="py-3 px-4">Featured</th>
                <th class="py-3 px-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100">
            @foreach($products as $product)
            <tr class="hover:bg-stone-50 transition-colors">
                <td class="py-3.5 px-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ $product->primary_image ?? '/images/products/sesame-seeds.jpg' }}" alt="" class="w-10 h-10 rounded-lg object-cover border border-stone-200 bg-stone-100">
                        <div>
                            <span class="font-bold text-[#091433] block text-sm">{{ $product->name }}</span>
                            <span class="text-stone-400 font-mono text-[11px]">{{ $product->slug }}</span>
                        </div>
                    </div>
                </td>
                <td class="py-3.5 px-4">
                    <span class="font-semibold text-stone-800 block">{{ $product->category->name }}</span>
                    <span class="text-stone-500 text-[11px]">{{ $product->productType?->name }}</span>
                </td>
                <td class="py-3.5 px-4 font-mono font-medium text-stone-700">
                    {{ $product->hs_code ?? '—' }}
                </td>
                <td class="py-3.5 px-4">
                    @if($product->status === 'published')
                        <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[10px] font-bold">Published</span>
                    @elseif($product->status === 'draft')
                        <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 text-[10px] font-bold">Draft</span>
                    @else
                        <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-600 text-[10px] font-bold">Archived</span>
                    @endif
                </td>
                <td class="py-3.5 px-4">
                    @if($product->is_featured)
                        <span class="text-amber-500 font-bold">★ Featured</span>
                    @else
                        <span class="text-stone-400">—</span>
                    @endif
                </td>
                <td class="py-3.5 px-4 text-right space-x-2">
                    <form action="{{ route('admin.products.toggle_status', $product->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-xs text-stone-600 hover:text-stone-900 underline font-semibold">
                            {{ $product->status === 'published' ? 'Unpublish' : 'Publish' }}
                        </button>
                    </form>
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="text-blue-600 hover:underline font-semibold">
                        Edit
                    </a>
                    <a href="{{ route('catalog.product', $product->slug) }}" target="_blank" class="text-stone-500 hover:underline font-semibold">
                        View
                    </a>
                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
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
    {{ $products->links() }}
</div>

@endsection
