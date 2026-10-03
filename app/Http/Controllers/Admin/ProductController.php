<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttributeDefinition;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttributeValue;
use App\Models\ProductType;
use App\Services\Media\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(protected MediaService $mediaService)
    {
    }

    public function index(Request $request): View
    {
        $query = Product::with(['category', 'productType'])->latest();

        if ($request->filled('q')) {
            $term = trim($request->input('q'));
            $query->search($term);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $products = $query->paginate(15)->withQueryString();
        $categories = Category::active()->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::active()->with('productTypes')->orderBy('name')->get();
        $productTypes = ProductType::active()->orderBy('name')->get();
        $attributeDefinitions = AttributeDefinition::orderBy('sort_order')->get();

        return view('admin.products.create', compact('categories', 'productTypes', 'attributeDefinitions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'product_type_id' => ['required', 'exists:product_types,id'],
            'name' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:200', 'unique:products,slug'],
            'hs_code' => ['nullable', 'string', 'max:30'],
            'origin' => ['required', 'string', 'max:100'],
            'short_description' => ['required', 'string', 'max:500'],
            'description' => ['required', 'string'],
            'status' => ['required', Rule::in([Product::STATUS_DRAFT, Product::STATUS_PUBLISHED, Product::STATUS_ARCHIVED])],
            'is_featured' => ['boolean'],
            'harvest_season' => ['nullable', 'string', 'max:100'],
            'supply_capacity' => ['nullable', 'string', 'max:120'],
            'minimum_order_qty' => ['nullable', 'string', 'max:120'],
            'packaging_options' => ['nullable', 'string', 'max:255'],
            'primary_image' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:5120'],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        if ($validated['status'] === Product::STATUS_PUBLISHED) {
            $validated['published_at'] = now();
        }

        $imageFile = $request->file('primary_image');
        unset($validated['primary_image']);

        $product = Product::create($validated);

        if ($imageFile) {
            $this->mediaService->uploadProductImage($product, $imageFile, true);
        }

        // Save specifications/attributes if provided
        if ($request->has('attributes') && is_array($request->input('attributes'))) {
            foreach ($request->input('attributes') as $defId => $val) {
                if (filled($val)) {
                    ProductAttributeValue::create([
                        'product_id' => $product->id,
                        'attribute_definition_id' => $defId,
                        'text_value' => is_numeric($val) ? null : $val,
                        'number_value' => is_numeric($val) ? (float) $val : null,
                    ]);
                }
            }
        }

        AuditLog::record('product_created', "Created product '{$product->name}' with status '{$product->status}'", $product);

        return redirect()->route('admin.products.index')->with('success', "Product '{$product->name}' created.");
    }

    public function edit(Product $product): View
    {
        $categories = Category::active()->with('productTypes')->orderBy('name')->get();
        $productTypes = ProductType::where('category_id', $product->category_id)->orderBy('name')->get();
        $attributeDefinitions = AttributeDefinition::orderBy('sort_order')->get();
        $existingValues = $product->attributeValues->pluck('text_value', 'attribute_definition_id')->toArray();
        $existingNumValues = $product->attributeValues->pluck('number_value', 'attribute_definition_id')->toArray();

        return view('admin.products.edit', compact(
            'product',
            'categories',
            'productTypes',
            'attributeDefinitions',
            'existingValues',
            'existingNumValues'
        ));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'product_type_id' => ['required', 'exists:product_types,id'],
            'name' => ['required', 'string', 'max:180'],
            'slug' => ['required', 'string', 'max:200', Rule::unique('products', 'slug')->ignore($product->id)],
            'hs_code' => ['nullable', 'string', 'max:30'],
            'origin' => ['required', 'string', 'max:100'],
            'short_description' => ['required', 'string', 'max:500'],
            'description' => ['required', 'string'],
            'status' => ['required', Rule::in([Product::STATUS_DRAFT, Product::STATUS_PUBLISHED, Product::STATUS_ARCHIVED])],
            'is_featured' => ['boolean'],
            'harvest_season' => ['nullable', 'string', 'max:100'],
            'supply_capacity' => ['nullable', 'string', 'max:120'],
            'minimum_order_qty' => ['nullable', 'string', 'max:120'],
            'packaging_options' => ['nullable', 'string', 'max:255'],
            'primary_image' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:5120'],
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        if ($validated['status'] === Product::STATUS_PUBLISHED && ! $product->published_at) {
            $validated['published_at'] = now();
        }

        $imageFile = $request->file('primary_image');
        unset($validated['primary_image']);

        $product->update($validated);

        if ($imageFile) {
            $this->mediaService->uploadProductImage($product, $imageFile, true);
        }

        // Update specifications/attributes
        if ($request->has('attributes') && is_array($request->input('attributes'))) {
            foreach ($request->input('attributes') as $defId => $val) {
                if (filled($val)) {
                    ProductAttributeValue::updateOrCreate(
                        ['product_id' => $product->id, 'attribute_definition_id' => $defId],
                        [
                            'text_value' => is_numeric($val) ? null : $val,
                            'number_value' => is_numeric($val) ? (float) $val : null,
                        ]
                    );
                } else {
                    ProductAttributeValue::where('product_id', $product->id)
                        ->where('attribute_definition_id', $defId)
                        ->delete();
                }
            }
        }

        AuditLog::record('product_updated', "Updated product '{$product->name}'", $product);

        return redirect()->route('admin.products.index')->with('success', "Product '{$product->name}' updated.");
    }

    public function toggleStatus(Product $product): RedirectResponse
    {
        $newStatus = $product->status === Product::STATUS_PUBLISHED ? Product::STATUS_DRAFT : Product::STATUS_PUBLISHED;
        $product->update([
            'status' => $newStatus,
            'published_at' => $newStatus === Product::STATUS_PUBLISHED ? ($product->published_at ?? now()) : $product->published_at,
        ]);

        AuditLog::record('product_status_toggled', "Product status changed to {$newStatus}", $product);

        return back()->with('success', "Product status updated to {$newStatus}.");
    }

    public function destroy(Product $product): RedirectResponse
    {
        $name = $product->name;

        // Clean up gallery images from storage
        foreach ($product->images as $img) {
            $this->mediaService->deleteProductImage($img);
        }

        $product->delete();

        AuditLog::record('product_deleted', "Deleted product {$name}");

        return redirect()->route('admin.products.index')->with('success', "Product '{$name}' deleted.");
    }
}
