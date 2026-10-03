<?php

namespace App\Services\Search;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class DatabaseSearchService implements SearchServiceInterface
{
    /**
     * Search products with query, filters, sorting, and pagination.
     */
    public function searchProducts(array $criteria, int $perPage = 12): LengthAwarePaginator
    {
        $query = Product::query()
            ->published()
            ->with(['category', 'productType']);

        // Keyword query
        if (! empty($criteria['q'])) {
            $term = trim($criteria['q']);
            $query->where(function (Builder $q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('short_description', 'like', "%{$term}%")
                  ->orWhere('description', 'like', "%{$term}%")
                  ->orWhere('hs_code', 'like', "%{$term}%");
            });
        }

        // Category filter
        if (! empty($criteria['category'])) {
            $categorySlug = $criteria['category'];
            $query->whereHas('category', function (Builder $q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Product Type filter
        if (! empty($criteria['type'])) {
            $typeSlug = $criteria['type'];
            $query->whereHas('productType', function (Builder $q) use ($typeSlug) {
                $q->where('slug', $typeSlug);
            });
        }

        // Featured filter
        if (! empty($criteria['featured'])) {
            $query->featured();
        }

        // Sorting
        $sort = $criteria['sort'] ?? 'newest';
        match ($sort) {
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            'oldest' => $query->oldest('published_at'),
            default => $query->latest('published_at'),
        };

        return $query->paginate($perPage)->withQueryString();
    }
}
