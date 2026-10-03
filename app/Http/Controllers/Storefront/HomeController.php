<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\CmsSection;
use App\Services\Catalog\CatalogService;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __construct(protected CatalogService $catalogService)
    {
    }

    public function index(): View
    {
        $categories = $this->catalogService->getActiveCategoryTree();
        $featuredProducts = $this->catalogService->getFeaturedProducts(6);

        $hero = CmsSection::getByKey('hero');
        $heritage = CmsSection::getByKey('about_heritage');
        $process = CmsSection::getByKey('sourcing_process');
        $mission = CmsSection::getByKey('mission_vision');
        $contact = CmsSection::getByKey('contact_verified');

        return view('storefront.home', compact(
            'categories',
            'featuredProducts',
            'hero',
            'heritage',
            'process',
            'mission',
            'contact'
        ));
    }
}
