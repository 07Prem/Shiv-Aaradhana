<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Services\Catalog\CatalogService;
use App\Services\Recommendations\RecommendationService;
use Illuminate\Contracts\View\View;

class ProductDetailController extends Controller
{
    public function __construct(
        protected CatalogService $catalogService,
        protected RecommendationService $recommendationService
    ) {
    }

    public function show(string $slug): View
    {
        $product = $this->catalogService->findPublishedProduct($slug);
        $recommendations = $this->recommendationService->getRecommendations($product, 4);

        // Filter populated specifications only (no blank labels or invented values)
        $specifications = $product->attributeValues->filter(function ($attrVal) {
            return filled($attrVal->text_value) 
                || filled($attrVal->number_value) 
                || ! empty($attrVal->json_value)
                || ! is_null($attrVal->boolean_value);
        });

        return view('storefront.catalog.product-detail', compact(
            'product',
            'recommendations',
            'specifications'
        ));
    }
}
