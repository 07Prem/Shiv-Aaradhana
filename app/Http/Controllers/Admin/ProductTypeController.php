<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\ProductType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductTypeController extends Controller
{
    public function index(): View
    {
        $productTypes = ProductType::with('category')
            ->withCount('products')
            ->orderBy('category_id')
            ->orderBy('sort_order')
            ->paginate(15);

        return view('admin.product-types.index', compact('productTypes'));
    }

    public function create(): View
    {
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.product-types.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:140', 'unique:product_types,slug'],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $type = ProductType::create($validated);

        AuditLog::record('product_type_created', "Created product type {$type->name}", $type);

        return redirect()->route('admin.product-types.index')->with('success', "Product type '{$type->name}' created.");
    }

    public function edit(ProductType $productType): View
    {
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.product-types.edit', compact('productType', 'categories'));
    }

    public function update(Request $request, ProductType $productType): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['required', 'string', 'max:140', Rule::unique('product_types', 'slug')->ignore($productType->id)],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $productType->update($validated);

        AuditLog::record('product_type_updated', "Updated product type {$productType->name}", $productType);

        return redirect()->route('admin.product-types.index')->with('success', "Product type '{$productType->name}' updated.");
    }

    public function destroy(ProductType $productType): RedirectResponse
    {
        if ($productType->products()->exists()) {
            return back()->withErrors(['error' => "Cannot delete product type '{$productType->name}' because it contains {$productType->products()->count()} active product(s)."]);
        }

        $name = $productType->name;
        $productType->delete();

        AuditLog::record('product_type_deleted', "Deleted product type {$name}");

        return redirect()->route('admin.product-types.index')->with('success', "Product type '{$name}' deleted.");
    }
}
