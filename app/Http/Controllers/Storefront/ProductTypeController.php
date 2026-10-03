<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Services\Catalog\CatalogService;
use App\Services\Search\SearchServiceInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProductTypeController extends Controller
{
    public function __construct(
        protected CatalogService $catalogService,
        protected SearchServiceInterface $searchService
    ) {
    }

    public function show(string $categorySlug, string $typeSlug, Request $request): View
    {
        $productType = $this->catalogService->findProductType($categorySlug, $typeSlug);
        $category = $productType->category;

        $criteria = [
            'category' => $category->slug,
            'type' => $productType->slug,
            'q' => $request->query('q'),
            'sort' => $request->query('sort', 'newest'),
        ];

        $products = $this->searchService->searchProducts($criteria, 12);
        $relatedTypes = $category->productTypes()->where('id', '!=', $productType->id)->get();

        return view('storefront.catalog.product-type', compact(
            'category',
            'productType',
            'products',
            'relatedTypes',
            'criteria'
        ));
    }
}
