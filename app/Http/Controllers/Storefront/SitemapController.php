<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $products = Product::published()->latest('updated_at')->get();
        $categories = Category::active()->latest('updated_at')->get();
        $productTypes = ProductType::active()->with('category')->latest('updated_at')->get();

        $content = view('storefront.sitemap', compact('products', 'categories', 'productTypes'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }
}
