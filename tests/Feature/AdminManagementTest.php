<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Inquiry;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::create([
            'name' => 'Operations Admin',
            'email' => 'admin@shivaaradhana.com',
            'password' => Hash::make('SecretPass123!'),
            'role' => User::ROLE_SUPER_ADMIN,
            'is_active' => true,
        ]);
    }

    public function test_admin_authentication_and_dashboard_access(): void
    {
        $loginRes = $this->post('/admin/login', [
            'email' => 'admin@shivaaradhana.com',
            'password' => 'SecretPass123!',
        ]);

        $loginRes->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->adminUser);

        $dashRes = $this->actingAs($this->adminUser)->get('/admin');
        $dashRes->assertStatus(200);
        $dashRes->assertSee('Shiv Aaradhana');
    }

    public function test_journey_c_admin_catalog_lifecycle(): void
    {
        // 1. Create Category
        $catRes = $this->actingAs($this->adminUser)->post('/admin/categories', [
            'name' => 'Textiles and Fabrics',
            'description' => 'Cotton export line.',
            'sort_order' => 1,
            'is_active' => 1,
        ]);
        $catRes->assertRedirect(route('admin.categories.index'));
        $category = Category::where('name', 'Textiles and Fabrics')->firstOrFail();

        // 2. Create Product Type
        $typeRes = $this->actingAs($this->adminUser)->post('/admin/product-types', [
            'category_id' => $category->id,
            'name' => 'Raw Cotton Bales',
            'sort_order' => 1,
            'is_active' => 1,
        ]);
        $typeRes->assertRedirect(route('admin.product-types.index'));
        $type = ProductType::where('name', 'Raw Cotton Bales')->firstOrFail();

        // 3. Create Product as Published
        $prodRes = $this->actingAs($this->adminUser)->post('/admin/products', [
            'category_id' => $category->id,
            'product_type_id' => $type->id,
            'name' => 'Gujarat Shankar-6 Cotton Bales',
            'hs_code' => '52010015',
            'origin' => 'Gujarat, India',
            'short_description' => 'Export Shankar-6 pressed cotton.',
            'description' => 'Detailed spinning parameters and micronaire report.',
            'status' => Product::STATUS_PUBLISHED,
            'is_featured' => 1,
        ]);
        $prodRes->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Gujarat Shankar-6 Cotton Bales',
            'status' => 'published',
            'is_featured' => 1,
        ]);
    }

    public function test_journey_d_inquiry_processing_and_status_update(): void
    {
        $inquiry = Inquiry::create([
            'reference_no' => 'SA-2026-TEST01',
            'inquiry_type' => 'general',
            'full_name' => 'Marcus Aurelius',
            'email' => 'marcus@trade.it',
            'phone' => '+39 06 6987 1234',
            'country' => 'Italy',
            'message' => 'Seeking bulk pricing on cumin seeds.',
            'status' => 'new',
        ]);

        // Admin views inquiry
        $viewRes = $this->actingAs($this->adminUser)->get("/admin/inquiries/{$inquiry->id}");
        $viewRes->assertStatus(200);
        $viewRes->assertSee('Marcus Aurelius');

        // Admin updates status to responded
        $statusRes = $this->actingAs($this->adminUser)->post("/admin/inquiries/{$inquiry->id}/status", [
            'status' => 'responded',
            'status_note' => 'Sent formal FOB Mundra pricing sheet.',
        ]);
        $statusRes->assertRedirect();

        $this->assertDatabaseHas('inquiries', [
            'id' => $inquiry->id,
            'status' => 'responded',
        ]);

        // Add internal note
        $noteRes = $this->actingAs($this->adminUser)->post("/admin/inquiries/{$inquiry->id}/note", [
            'notes' => 'Buyer confirmed receipt and requested sample shipment.',
        ]);
        $noteRes->assertRedirect();

        $this->assertDatabaseHas('inquiry_activities', [
            'inquiry_id' => $inquiry->id,
            'action' => 'internal_note',
        ]);
    }
}
