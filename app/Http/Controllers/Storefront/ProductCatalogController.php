<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProductType;
use App\Services\Search\SearchServiceInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProductCatalogController extends Controller
{
    public function __construct(protected SearchServiceInterface $searchService)
    {
    }

    public function index(Request $request): View
    {
        $criteria = [
            'q' => $request->query('q'),
            'category' => $request->query('category'),
            'type' => $request->query('type'),
            'sort' => $request->query('sort', 'newest'),
            'featured' => $request->boolean('featured'),
        ];

        $products = $this->searchService->searchProducts($criteria, 12);

        $categories = Category::query()
            ->active()
            ->root()
            ->withCount(['products' => fn ($q) => $q->published()])
            ->orderBy('sort_order')
            ->get();

        $selectedCategory = $criteria['category'] 
            ? Category::where('slug', $criteria['category'])->with('productTypes')->first() 
            : null;

        $productTypes = $selectedCategory
            ? $selectedCategory->productTypes()->active()->withCount(['products' => fn ($q) => $q->published()])->get()
            : ProductType::query()->active()->withCount(['products' => fn ($q) => $q->published()])->get();

        return view('storefront.catalog.index', compact(
            'products',
            'categories',
            'productTypes',
            'criteria',
            'selectedCategory'
        ));
    }
}
