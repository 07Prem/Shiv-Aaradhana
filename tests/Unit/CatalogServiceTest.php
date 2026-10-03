<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductType;
use App\Services\Catalog\CatalogService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CatalogService $catalogService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->catalogService = new CatalogService();
    }

    public function test_can_retrieve_active_category_tree(): void
    {
        $activeCat = Category::create([
            'name' => 'Active Category',
            'slug' => 'active-category',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $inactiveCat = Category::create([
            'name' => 'Inactive Category',
            'slug' => 'inactive-category',
            'is_active' => false,
            'sort_order' => 2,
        ]);

        $tree = $this->catalogService->getActiveCategoryTree();

        $this->assertTrue($tree->contains('id', $activeCat->id));
        $this->assertFalse($tree->contains('id', $inactiveCat->id));
    }

    public function test_can_find_published_product_by_slug(): void
    {
        $cat = Category::create(['name' => 'Agro', 'slug' => 'agro', 'is_active' => true]);
        $type = ProductType::create(['category_id' => $cat->id, 'name' => 'Seeds', 'slug' => 'seeds', 'is_active' => true]);

        $product = Product::create([
            'category_id' => $cat->id,
            'product_type_id' => $type->id,
            'name' => 'Cumin Seeds',
            'slug' => 'cumin-seeds',
            'origin' => 'Gujarat, India',
            'short_description' => 'Aromatic seeds',
            'description' => 'Sortex clean cumin seeds for export.',
            'status' => Product::STATUS_PUBLISHED,
            'published_at' => now(),
        ]);

        $found = $this->catalogService->findPublishedProduct('cumin-seeds');

        $this->assertEquals($product->id, $found->id);
        $this->assertEquals('Cumin Seeds', $found->name);
    }
}
