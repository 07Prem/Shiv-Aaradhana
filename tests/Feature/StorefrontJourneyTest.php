<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Inquiry;
use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontJourneyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed basic catalog structure
        $cat = Category::create([
            'name' => 'Agro Products',
            'slug' => 'agro-products',
            'description' => 'Fine agricultural commodities.',
            'is_active' => true,
        ]);

        $type = ProductType::create([
            'category_id' => $cat->id,
            'name' => 'Oilseeds',
            'slug' => 'oilseeds',
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $cat->id,
            'product_type_id' => $type->id,
            'name' => 'Natural White Sesame Seeds',
            'slug' => 'natural-white-sesame-seeds',
            'origin' => 'Gujarat, India',
            'short_description' => '99.9% Sortex purity sesame seeds.',
            'description' => 'High oil yield natural white sesame seeds for tahini and bakery.',
            'status' => Product::STATUS_PUBLISHED,
            'is_featured' => true,
            'published_at' => now(),
        ]);
    }

    public function test_journey_a_product_discovery(): void
    {
        // 1. Visit Homepage
        $homeRes = $this->get('/');
        $homeRes->assertStatus(200);
        $homeRes->assertSee('From Indian Roots to Global Markets');

        // 2. Visit Category Page
        $catRes = $this->get('/category/agro-products');
        $catRes->assertStatus(200);
        $catRes->assertSee('Agro Products');

        // 3. Visit Product Type Page
        $typeRes = $this->get('/category/agro-products/oilseeds');
        $typeRes->assertStatus(200);
        $typeRes->assertSee('Oilseeds');

        // 4. Visit Product Detail Page
        $prodRes = $this->get('/products/natural-white-sesame-seeds');
        $prodRes->assertStatus(200);
        $prodRes->assertSee('Natural White Sesame Seeds');
        $prodRes->assertSee('Request a Quote for this Product');
    }

    public function test_journey_b_business_inquiry_submission(): void
    {
        $product = Product::first();

        $payload = [
            'product_id' => $product->id,
            'full_name' => 'Alexander Vance',
            'company_name' => 'Vance Commodity Traders UK',
            'email' => 'alex@vancetraders.co.uk',
            'phone' => '+44 20 7946 0912',
            'country' => 'United Kingdom',
            'target_quantity' => '2 x 20ft FCL (38 MT)',
            'packaging_requirements' => '25kg Multi-wall paper bags',
            'port_of_destination' => 'Felixstowe Port',
            'message' => 'Please provide CIF Felixstowe rate indication and confirmed harvest crop certificate.',
            'website_hp' => '', // Clean honeypot
        ];

        $response = $this->postJson('/inquiries/quote', $payload);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // Verify record in database
        $this->assertDatabaseHas('inquiries', [
            'email' => 'alex@vancetraders.co.uk',
            'full_name' => 'Alexander Vance',
            'country' => 'United Kingdom',
            'status' => 'new',
        ]);
    }

    public function test_journey_e_security_unauthorized_admin_access(): void
    {
        // Guest attempting to access protected admin route
        $response = $this->get('/admin');
        $response->assertRedirect('/admin/login');

        $prodResponse = $this->get('/admin/products');
        $prodResponse->assertRedirect('/admin/login');
    }
}
