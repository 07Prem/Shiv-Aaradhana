<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::with('parent')
            ->withCount(['products', 'productTypes'])
            ->orderBy('sort_order')
            ->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        $parents = Category::whereNull('parent_id')->orderBy('name')->get();
        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:140', 'unique:categories,slug'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['integer', 'min:0'],
            'is_active' => ['boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,webp,jpg', 'max:2048'],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $validated['image_path'] = \Illuminate\Support\Facades\Storage::url($path);
        }
        unset($validated['image']);

        $category = Category::create($validated);

        AuditLog::record('category_created', "Created category {$category->name}", $category);

        return redirect()->route('admin.categories.index')->with('success', "Category '{$category->name}' created successfully.");
    }

    public function edit(Category $category): View
    {
        $parents = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['required', 'string', 'max:140', Rule::unique('categories', 'slug')->ignore($category->id)],
            'parent_id' => ['nullable', 'exists:categories,id', Rule::notIn([$category->id])],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['integer', 'min:0'],
            'is_active' => ['boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,webp,jpg', 'max:2048'],
            'remove_image' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        
        if ($request->boolean('remove_image') && $category->image_path) {
            $oldPath = str_replace('/storage/', '', $category->image_path);
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($oldPath)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
            }
            $validated['image_path'] = null;
        }

        if ($request->hasFile('image')) {
            if ($category->image_path) {
                $oldPath = str_replace('/storage/', '', $category->image_path);
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($oldPath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                }
            }
            $path = $request->file('image')->store('categories', 'public');
            $validated['image_path'] = \Illuminate\Support\Facades\Storage::url($path);
        }
        unset($validated['image'], $validated['remove_image']);

        $category->update($validated);

        AuditLog::record('category_updated', "Updated category {$category->name}", $category);

        return redirect()->route('admin.categories.index')->with('success', "Category '{$category->name}' updated successfully.");
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return back()->withErrors(['error' => "Cannot delete category '{$category->name}' because it contains {$category->products()->count()} active product(s). Please reassign or delete products first."]);
        }

        if ($category->productTypes()->exists()) {
            return back()->withErrors(['error' => "Cannot delete category '{$category->name}' because it contains associated product type(s)."]);
        }

        $name = $category->name;
        $category->delete();

        AuditLog::record('category_deleted', "Deleted category {$name}");

        return redirect()->route('admin.categories.index')->with('success', "Category '{$name}' deleted.");
    }
}
