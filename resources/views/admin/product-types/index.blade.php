@extends('layouts.admin')

@section('title', 'Product Types')
@section('header', 'Product Lines & Sub-Types')

@section('content')

<div class="flex items-center justify-between mb-6">
    <p class="text-xs text-stone-500">Manage specialized product lines categorized under primary commodity departments.</p>
    <a href="{{ route('admin.product-types.create') }}" class="px-4 py-2 rounded-lg bg-[#091433] hover:bg-[#394F3D] text-white text-xs font-bold transition-colors">
        + Add New Product Line
    </a>
</div>

<div class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-sm">
    <table class="w-full text-xs text-left">
        <thead>
            <tr class="bg-stone-50 text-stone-500 uppercase tracking-wider border-b border-stone-200">
                <th class="py-3 px-4">Line Name</th>
                <th class="py-3 px-4">Slug</th>
                <th class="py-3 px-4">Category</th>
                <th class="py-3 px-4">Products</th>
                <th class="py-3 px-4">Status</th>
                <th class="py-3 px-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100">
            @foreach($productTypes as $pt)
            <tr class="hover:bg-stone-50 transition-colors">
                <td class="py-3.5 px-4 font-bold text-[#091433]">
                    {{ $pt->name }}
                </td>
                <td class="py-3.5 px-4 font-mono text-stone-500">
                    {{ $pt->slug }}
                </td>
                <td class="py-3.5 px-4">
                    <span class="px-2 py-0.5 rounded bg-stone-100 font-semibold text-stone-700">
                        {{ $pt->category->name }}
                    </span>
                </td>
                <td class="py-3.5 px-4 font-semibold text-stone-700">
                    {{ $pt->products_count }} products
                </td>
                <td class="py-3.5 px-4">
                    @if($pt->is_active)
                        <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[10px] font-bold">Active</span>
                    @else
                        <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-600 text-[10px] font-bold">Inactive</span>
                    @endif
                </td>
                <td class="py-3.5 px-4 text-right space-x-2">
                    <a href="{{ route('admin.product-types.edit', $pt->id) }}" class="text-blue-600 hover:underline font-semibold">
                        Edit
                    </a>
                    <form action="{{ route('admin.product-types.destroy', $pt->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this product type?');">
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
    {{ $productTypes->links() }}
</div>

@endsection
