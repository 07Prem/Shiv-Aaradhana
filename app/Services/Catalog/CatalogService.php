<?php

namespace App\Services\Catalog;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CatalogService
{
    /**
     * Get active categories with their active product types and counts.
     */
    public function getActiveCategoryTree(): Collection
    {
        return Category::query()
            ->active()
            ->root()
            ->with(['children' => fn ($q) => $q->active()->orderBy('sort_order')])
            ->with(['productTypes' => fn ($q) => $q->active()->orderBy('sort_order')])
            ->withCount(['products' => fn ($q) => $q->published()])
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Find a category by slug or fail.
     */
    public function findCategoryBySlug(string $slug): Category
    {
        return Category::query()
            ->active()
            ->where('slug', $slug)
            ->with(['productTypes' => fn ($q) => $q->active()->withCount(['products' => fn ($p) => $p->published()]), 'children'])
            ->withCount(['products' => fn ($q) => $q->published()])
            ->firstOrFail();
    }

    /**
     * Find a product type by category and type slug.
     */
    public function findProductType(string $categorySlug, string $typeSlug): ProductType
    {
        $category = $this->findCategoryBySlug($categorySlug);

        return ProductType::query()
            ->active()
            ->where('category_id', $category->id)
            ->where('slug', $typeSlug)
            ->with('category')
            ->firstOrFail();
    }

    /**
     * Find a published product by slug with images, attributes, and category context.
     */
    public function findPublishedProduct(string $slug): Product
    {
        return Product::query()
            ->published()
            ->where('slug', $slug)
            ->with([
                'category',
                'productType',
                'images',
                'attributeValues.attributeDefinition',
                'recommendations.recommendedProduct.category',
            ])
            ->firstOrFail();
    }

    /**
     * Get featured products for homepage or discovery banner.
     */
    public function getFeaturedProducts(int $limit = 6): Collection
    {
        return Product::query()
            ->published()
            ->featured()
            ->with(['category', 'productType'])
            ->latest('published_at')
            ->take($limit)
            ->get();
    }
}
