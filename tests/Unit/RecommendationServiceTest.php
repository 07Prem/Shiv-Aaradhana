<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductRecommendation;
use App\Models\ProductType;
use App\Services\Recommendations\RecommendationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecommendationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected RecommendationService $recService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->recService = new RecommendationService();
    }

    public function test_recommendations_exclude_current_product_and_drafts(): void
    {
        $cat = Category::create(['name' => 'Agro', 'slug' => 'agro', 'is_active' => true]);
        $type = ProductType::create(['category_id' => $cat->id, 'name' => 'Seeds', 'slug' => 'seeds', 'is_active' => true]);

        $mainProduct = Product::create([
            'category_id' => $cat->id,
            'product_type_id' => $type->id,
            'name' => 'Main Product',
            'slug' => 'main-product',
            'origin' => 'Gujarat, India',
            'short_description' => 'Main description',
            'description' => 'Main details',
            'status' => Product::STATUS_PUBLISHED,
        ]);

        $publishedRec = Product::create([
            'category_id' => $cat->id,
            'product_type_id' => $type->id,
            'name' => 'Published Recommended',
            'slug' => 'published-rec',
            'origin' => 'Gujarat, India',
            'short_description' => 'Rec description',
            'description' => 'Rec details',
            'status' => Product::STATUS_PUBLISHED,
        ]);

        $draftRec = Product::create([
            'category_id' => $cat->id,
            'product_type_id' => $type->id,
            'name' => 'Draft Product',
            'slug' => 'draft-product',
            'origin' => 'Gujarat, India',
            'short_description' => 'Draft description',
            'description' => 'Draft details',
            'status' => Product::STATUS_DRAFT,
        ]);

        // Explicit recommendation pointing to both
        ProductRecommendation::create([
            'source_product_id' => $mainProduct->id,
            'recommended_product_id' => $publishedRec->id,
            'relation_type' => 'complementary',
            'priority' => 10,
        ]);

        ProductRecommendation::create([
            'source_product_id' => $mainProduct->id,
            'recommended_product_id' => $draftRec->id,
            'relation_type' => 'related',
            'priority' => 5,
        ]);

        $results = $this->recService->getRecommendations($mainProduct, 4);

        // Assert self is never recommended
        $this->assertFalse($results->contains('id', $mainProduct->id));

        // Assert published recommendation is returned
        $this->assertTrue($results->contains('id', $publishedRec->id));

        // Assert draft recommendation is rejected
        $this->assertFalse($results->contains('id', $draftRec->id));
    }
}
