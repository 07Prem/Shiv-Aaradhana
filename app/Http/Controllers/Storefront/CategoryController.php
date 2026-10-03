<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\Catalog\CatalogService;
use App\Services\Search\SearchServiceInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        protected CatalogService $catalogService,
        protected SearchServiceInterface $searchService
    ) {
    }

    public function show(string $slug, Request $request): View
    {
        $category = $this->catalogService->findCategoryBySlug($slug);

        $criteria = [
            'category' => $category->slug,
            'type' => $request->query('type'),
            'q' => $request->query('q'),
            'sort' => $request->query('sort', 'newest'),
        ];

        $products = $this->searchService->searchProducts($criteria, 12);
        $productTypes = $category->productTypes;

        return view('storefront.catalog.category', compact(
            'category',
            'products',
            'productTypes',
            'criteria'
        ));
    }
}
