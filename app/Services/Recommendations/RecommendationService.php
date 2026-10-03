<?php

namespace App\Services\Recommendations;

use App\Models\Product;
use App\Models\ProductRecommendation;
use Illuminate\Database\Eloquent\Collection;

class RecommendationService
{
    /**
     * Get deterministic recommendations for a given product.
     */
    public function getRecommendations(Product $product, int $limit = 4): Collection
    {
        $recommendations = collect();

        // 1. Explicit Product Recommendations
        $explicit = ProductRecommendation::query()
            ->where('source_product_id', $product->id)
            ->with(['recommendedProduct' => fn ($q) => $q->published()->with(['category', 'productType'])])
            ->orderBy('priority', 'desc')
            ->get()
            ->map(function (ProductRecommendation $rec) {
                if ($rec->recommendedProduct && $rec->recommendedProduct->isPublished()) {
                    $item = $rec->recommendedProduct;
                    $item->recommendation_reason = match ($rec->relation_type) {
                        'curated' => 'Curated Selection',
                        'complementary' => 'Frequently Sourced Together',
                        'alternative' => 'Direct Specification Alternative',
                        default => 'Related Agro Product',
                    };
                    return $item;
                }
                return null;
            })
            ->filter();

        $recommendations = $recommendations->merge($explicit);

        // 2. Same Product Type (if more needed)
        if ($recommendations->count() < $limit && $product->product_type_id) {
            $sameType = Product::query()
                ->published()
                ->where('product_type_id', $product->product_type_id)
                ->where('id', '!=', $product->id)
                ->whereNotIn('id', $recommendations->pluck('id'))
                ->with(['category', 'productType'])
                ->take($limit - $recommendations->count())
                ->get()
                ->each(function ($item) {
                    $item->recommendation_reason = 'Same Product Line';
                });

            $recommendations = $recommendations->merge($sameType);
        }

        // 3. Same Category (if more needed)
        if ($recommendations->count() < $limit && $product->category_id) {
            $sameCat = Product::query()
                ->published()
                ->where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->whereNotIn('id', $recommendations->pluck('id'))
                ->with(['category', 'productType'])
                ->take($limit - $recommendations->count())
                ->get()
                ->each(function ($item) {
                    $item->recommendation_reason = 'Same Commodity Category';
                });

            $recommendations = $recommendations->merge($sameCat);
        }

        return new Collection($recommendations->take($limit)->values()->all());
    }
}
