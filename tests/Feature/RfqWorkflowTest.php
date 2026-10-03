<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Inquiry;
use App\Models\InquiryActivity;
use App\Models\InquiryItem;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RfqWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected Product $productA;
    protected Product $productB;
    protected Product $draftProduct;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create super admin
        $this->adminUser = User::create([
            'name' => 'Export Director',
            'email' => 'director@shivaaradhana.com',
            'password' => Hash::make('AdminSecure2026!'),
            'role' => User::ROLE_SUPER_ADMIN,
            'is_active' => true,
        ]);

        // 2. Create catalog
        $category = Category::create([
            'name' => 'Spices & Seeds',
            'slug' => 'spices-seeds',
            'description' => 'Indian origin export spices.',
            'is_active' => true,
        ]);

        $type = ProductType::create([
            'category_id' => $category->id,
            'name' => 'Seeds',
            'slug' => 'seeds',
            'is_active' => true,
        ]);

        $this->productA = Product::create([
            'category_id' => $category->id,
            'product_type_id' => $type->id,
            'name' => 'Cumin Seeds Machine Cleaned',
            'slug' => 'cumin-seeds-machine-cleaned',
            'hs_code' => '09093129',
            'origin' => 'Unjha, Gujarat, India',
            'short_description' => 'High aromatic oil content cumin seeds.',
            'description' => 'Export grade Singapore/Europe quality cumin.',
            'status' => Product::STATUS_PUBLISHED,
            'is_featured' => true,
            'published_at' => now(),
        ]);

        $this->productB = Product::create([
            'category_id' => $category->id,
            'product_type_id' => $type->id,
            'name' => 'Bold Groundnuts / Peanuts',
            'slug' => 'bold-groundnuts-peanuts',
            'hs_code' => '12024210',
            'origin' => 'Saurashtra, Gujarat, India',
            'short_description' => 'Aflatoxin-controlled bold peanuts.',
            'description' => 'Count 40/50 & 50/60 peanuts.',
            'status' => Product::STATUS_PUBLISHED,
            'is_featured' => true,
            'published_at' => now(),
        ]);

        $this->draftProduct = Product::create([
            'category_id' => $category->id,
            'product_type_id' => $type->id,
            'name' => 'Draft Unverified Product',
            'slug' => 'draft-unverified-product',
            'hs_code' => '00000000',
            'origin' => 'India',
            'short_description' => 'Unpublished commodity.',
            'description' => 'Draft unverified product description.',
            'status' => Product::STATUS_DRAFT,
            'is_featured' => false,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | 1. Session RFQ Cart / Quotation List Tests
    |--------------------------------------------------------------------------
    */

    public function test_customer_can_add_published_product_to_rfq_list(): void
    {
        $response = $this->postJson('/rfq/items', [
            'product_id' => $this->productA->id,
            'quantity' => 2,
            'notes' => '100% sortex clean',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'count' => 1,
        ]);

        // Verify session content
        $listResponse = $this->getJson('/rfq/items');
        $listResponse->assertStatus(200);
        $listResponse->assertJsonCount(1, 'items');
        $this->assertEquals($this->productA->name, $listResponse->json('items.0.name'));
        $this->assertEquals(2, $listResponse->json('items.0.quantity'));
        $this->assertEquals('09093129', $listResponse->json('items.0.hs_code'));
    }

    public function test_customer_can_add_multiple_commodities_to_rfq_list(): void
    {
        $this->postJson('/rfq/items', [
            'product_id' => $this->productA->id,
            'quantity' => 1,
        ]);

        $this->postJson('/rfq/items', [
            'product_id' => $this->productB->id,
            'quantity' => 3,
            'notes' => 'Count 40/50, vacuum packed',
        ]);

        $listResponse = $this->getJson('/rfq/items');
        $listResponse->assertStatus(200);
        $listResponse->assertJson([
            'success' => true,
            'count' => 2,
        ]);
    }

    public function test_duplicate_product_addition_increments_quantity(): void
    {
        $this->postJson('/rfq/items', [
            'product_id' => $this->productA->id,
            'quantity' => 1,
        ]);

        $this->postJson('/rfq/items', [
            'product_id' => $this->productA->id,
            'quantity' => 2,
        ]);

        $listResponse = $this->getJson('/rfq/items');
        $listResponse->assertStatus(200);
        $listResponse->assertJsonCount(1, 'items');
        $this->assertEquals(3, $listResponse->json('items.0.quantity'));
    }

    public function test_customer_can_update_rfq_item_quantity(): void
    {
        $this->postJson('/rfq/items', [
            'product_id' => $this->productA->id,
            'quantity' => 1,
        ]);

        $updateResponse = $this->patchJson("/rfq/items/{$this->productA->id}", [
            'quantity' => 5,
            'notes' => 'Bulk sea freight in jumbo bags',
        ]);

        $updateResponse->assertStatus(200);
        $updateResponse->assertJson(['success' => true]);

        $listResponse = $this->getJson('/rfq/items');
        $this->assertEquals(5, $listResponse->json('items.0.quantity'));
        $this->assertEquals('Bulk sea freight in jumbo bags', $listResponse->json('items.0.notes'));
    }

    public function test_customer_can_remove_product_from_rfq_list(): void
    {
        $this->postJson('/rfq/items', ['product_id' => $this->productA->id, 'quantity' => 1]);
        $this->postJson('/rfq/items', ['product_id' => $this->productB->id, 'quantity' => 1]);

        $deleteResponse = $this->deleteJson("/rfq/items/{$this->productA->id}");
        $deleteResponse->assertStatus(200);
        $deleteResponse->assertJson([
            'success' => true,
            'count' => 1,
        ]);

        $listResponse = $this->getJson('/rfq/items');
        $this->assertEquals($this->productB->id, $listResponse->json('items.0.product_id'));
    }

    public function test_customer_can_clear_entire_rfq_list(): void
    {
        $this->postJson('/rfq/items', ['product_id' => $this->productA->id, 'quantity' => 1]);
        $this->postJson('/rfq/items', ['product_id' => $this->productB->id, 'quantity' => 1]);

        $clearResponse = $this->deleteJson('/rfq/items');
        $clearResponse->assertStatus(200);
        $clearResponse->assertJson([
            'success' => true,
            'count' => 0,
        ]);
    }

    public function test_rfq_rejects_non_existent_or_draft_products(): void
    {
        // 1. Non-existent product
        $nonExistentResponse = $this->postJson('/rfq/items', [
            'product_id' => '00000000-0000-0000-0000-000000000000',
            'quantity' => 1,
        ]);
        $nonExistentResponse->assertStatus(422);

        // 2. Draft / unpublished product
        $draftResponse = $this->postJson('/rfq/items', [
            'product_id' => $this->draftProduct->id,
            'quantity' => 1,
        ]);
        $draftResponse->assertStatus(422);
    }

    public function test_rfq_rejects_zero_or_negative_quantities(): void
    {
        $response = $this->postJson('/rfq/items', [
            'product_id' => $this->productA->id,
            'quantity' => 0,
        ]);
        $response->assertStatus(422);

        $responseNeg = $this->postJson('/rfq/items', [
            'product_id' => $this->productA->id,
            'quantity' => -3,
        ]);
        $responseNeg->assertStatus(422);
    }

    /*
    |--------------------------------------------------------------------------
    | 2. Customer Submission & Validation Tests
    |--------------------------------------------------------------------------
    */

    public function test_customer_can_submit_multi_product_rfq_successfully(): void
    {
        // Populate session
        $this->postJson('/rfq/items', ['product_id' => $this->productA->id, 'quantity' => 2, 'notes' => '25kg PP bags']);
        $this->postJson('/rfq/items', ['product_id' => $this->productB->id, 'quantity' => 1, 'notes' => 'Jumbo bags']);

        $payload = [
            'full_name' => 'Hans Gruber',
            'company_name' => 'EuroAgro Importers GmbH',
            'email' => 'hans@euroagro.de',
            'phone' => '+49 40 1234567',
            'country' => 'Germany',
            'port_of_destination' => 'Hamburg Port',
            'message' => 'Please provide CIF Hamburg container quotation with SGS inspection.',
            'items' => [
                [
                    'product_id' => $this->productA->id,
                    'quantity' => 2,
                    'notes' => '25kg PP bags',
                ],
                [
                    'product_id' => $this->productB->id,
                    'quantity' => 1,
                    'notes' => 'Jumbo bags',
                ],
            ],
            'website_hp' => '',
        ];

        $response = $this->postJson('/inquiries/quote', $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'reference_no' => true,
        ]);

        // Verify inquiry record in DB
        $inquiry = Inquiry::where('email', 'hans@euroagro.de')->first();
        $this->assertNotNull($inquiry);
        $this->assertEquals('Hans Gruber', $inquiry->full_name);
        $this->assertEquals('EuroAgro Importers GmbH', $inquiry->company_name);
        $this->assertEquals('Germany', $inquiry->country);
        $this->assertEquals('Hamburg Port', $inquiry->port_of_destination);
        $this->assertEquals(Inquiry::STATUS_NEW, $inquiry->status);
        $this->assertStringStartsWith('SA-2026-', $inquiry->reference_no);

        // Verify items in DB with authoritative data snapshot
        $this->assertCount(2, $inquiry->items);

        $itemA = $inquiry->items->where('product_id', $this->productA->id)->first();
        $this->assertNotNull($itemA);
        $this->assertEquals($this->productA->name, $itemA->product_name);
        $this->assertEquals($this->productA->hs_code, $itemA->hs_code);
        $this->assertEquals($this->productA->slug, $itemA->product_slug);
        $this->assertEquals('2', (string)$itemA->quantity);
        $this->assertEquals('25kg PP bags', $itemA->notes);

        $itemB = $inquiry->items->where('product_id', $this->productB->id)->first();
        $this->assertNotNull($itemB);
        $this->assertEquals($this->productB->name, $itemB->product_name);
        $this->assertEquals($this->productB->hs_code, $itemB->hs_code);

        // Verify session RFQ cart was cleared automatically
        $listResponse = $this->getJson('/rfq/items');
        $this->assertEquals(0, $listResponse->json('count'));
    }

    public function test_single_product_quote_creates_inquiry_and_line_item(): void
    {
        $payload = [
            'product_id' => $this->productA->id,
            'full_name' => 'Kenji Sato',
            'company_name' => 'Tokyo Trading Corp',
            'email' => 'kenji@tokyotrading.co.jp',
            'phone' => '+81 3 5555 0123',
            'country' => 'Japan',
            'target_quantity' => '1 x 20ft FCL (19 MT)',
            'port_of_destination' => 'Yokohama',
            'message' => 'Seeking top grade Sortex cumin with pesticide residue analysis.',
            'website_hp' => '',
        ];

        $response = $this->postJson('/inquiries/quote', $payload);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $inquiry = Inquiry::where('email', 'kenji@tokyotrading.co.jp')->first();
        $this->assertNotNull($inquiry);
        $this->assertEquals('1 x 20ft FCL (19 MT)', $inquiry->target_quantity);

        // Verify line item exists
        $this->assertCount(1, $inquiry->items);
        $item = $inquiry->items->first();
        $this->assertEquals($this->productA->id, $item->product_id);
        $this->assertEquals($this->productA->name, $item->product_name);
        $this->assertEquals('1 x 20ft FCL (19 MT)', $item->quantity);
    }

    public function test_submission_fails_with_empty_items_and_no_product_id(): void
    {
        $payload = [
            'full_name' => 'Incomplete Buyer',
            'email' => 'incomplete@buyer.com',
            'phone' => '+1 555 1234',
            'country' => 'USA',
            'message' => 'No products specified.',
            'website_hp' => '',
        ];

        $response = $this->postJson('/inquiries/quote', $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['items']);
    }

    public function test_submission_fails_with_invalid_contact_fields(): void
    {
        $payload = [
            'product_id' => $this->productA->id,
            'full_name' => '', // missing
            'email' => 'not-an-email', // invalid
            'phone' => '', // missing
            'country' => '', // missing
            'website_hp' => '',
        ];

        $response = $this->postJson('/inquiries/quote', $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['full_name', 'email', 'phone', 'country']);
    }

    public function test_honeypot_spam_submission_is_silently_discarded(): void
    {
        $payload = [
            'product_id' => $this->productA->id,
            'full_name' => 'Spam Bot',
            'email' => 'bot@spammer.org',
            'phone' => '+1 800 0000',
            'country' => 'Unknown',
            'message' => 'Check this spam link!',
            'website_hp' => 'I am a bot fill me in', // Honeypot triggered
        ];

        $response = $this->postJson('/inquiries/quote', $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['website_hp']);

        // Assert no inquiry is written to database
        $this->assertDatabaseMissing('inquiries', [
            'email' => 'bot@spammer.org',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | 3. Database Persistence & Resilience Tests
    |--------------------------------------------------------------------------
    */

    public function test_mail_failure_does_not_rollback_inquiry_persistence(): void
    {
        // Simulate Mail throwing an exception
        Mail::shouldReceive('send')->andThrow(new \Exception('SMTP Connection Timeout'));

        $payload = [
            'product_id' => $this->productA->id,
            'full_name' => 'Resilient Customer',
            'company_name' => 'Durable Imports Ltd',
            'email' => 'durable@imports.com',
            'phone' => '+44 7700 900123',
            'country' => 'United Kingdom',
            'target_quantity' => '1 FCL',
            'message' => 'Testing email resilience.',
            'website_hp' => '',
        ];

        $response = $this->postJson('/inquiries/quote', $payload);

        // Submission should still succeed because inquiry is saved before email
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('inquiries', [
            'email' => 'durable@imports.com',
            'full_name' => 'Resilient Customer',
        ]);
    }

    public function test_database_snapshots_prevent_authoritative_forgery(): void
    {
        // Malicious client tries to forge product name in items array
        $payload = [
            'full_name' => 'Tamper Test',
            'email' => 'tamper@test.com',
            'phone' => '+1 234 567 8901',
            'country' => 'Canada',
            'message' => 'Checking product snapshot.',
            'items' => [
                [
                    'product_id' => $this->productA->id,
                    'quantity' => 10,
                    // Note: Product name is resolved from DB by the server, not from client
                ],
            ],
            'website_hp' => '',
        ];

        $response = $this->postJson('/inquiries/quote', $payload);
        $response->assertStatus(200);

        $inquiry = Inquiry::where('email', 'tamper@test.com')->firstOrFail();
        $item = $inquiry->items->first();

        // Must match actual DB name, not any client forgery
        $this->assertEquals($this->productA->name, $item->product_name);
        $this->assertEquals($this->productA->hs_code, $item->hs_code);
    }

    /*
    |--------------------------------------------------------------------------
    | 4. Admin Panel Synchronization & Lifecycle Tests
    |--------------------------------------------------------------------------
    */

    public function test_submitted_rfq_appears_immediately_in_admin_inquiries_listing(): void
    {
        // 1. Submit RFQ as customer
        $this->postJson('/inquiries/quote', [
            'full_name' => 'Tariq Al-Mansoor',
            'company_name' => 'Gulf Grain & Spice Co.',
            'email' => 'tariq@gulfspice.ae',
            'phone' => '+971 4 123 4567',
            'country' => 'United Arab Emirates',
            'message' => 'Need 2 containers Cumin Seeds FOB Mundra.',
            'items' => [
                ['product_id' => $this->productA->id, 'quantity' => 2, 'notes' => 'Machine Cleaned 99%'],
                ['product_id' => $this->productB->id, 'quantity' => 1, 'notes' => 'Bold 40/50'],
            ],
            'website_hp' => '',
        ]);

        // 2. Admin loads inquiries listing
        $adminListing = $this->actingAs($this->adminUser)->get('/admin/inquiries');
        $adminListing->assertStatus(200);

        // Verify customer & commodity details appear in admin table
        $adminListing->assertSee('Tariq Al-Mansoor');
        $adminListing->assertSee('Gulf Grain &amp; Spice Co.', false);
        $adminListing->assertSee('United Arab Emirates');
        $adminListing->assertSee('Cumin Seeds Machine Cleaned');
        $adminListing->assertSee('Bold Groundnuts / Peanuts');
    }

    public function test_admin_can_view_complete_rfq_details_and_line_items(): void
    {
        $this->postJson('/inquiries/quote', [
            'full_name' => 'Claire Dupont',
            'company_name' => 'Dupont Agro Importation',
            'email' => 'claire@dupontagro.fr',
            'phone' => '+33 1 42 68 55 00',
            'country' => 'France',
            'port_of_destination' => 'Le Havre Port',
            'message' => 'Please attach certificate of analysis and heavy metal testing report.',
            'items' => [
                ['product_id' => $this->productA->id, 'quantity' => 3, 'notes' => '25kg poly-lined bags'],
            ],
            'website_hp' => '',
        ]);

        $inquiry = Inquiry::where('email', 'claire@dupontagro.fr')->firstOrFail();

        $detailResponse = $this->actingAs($this->adminUser)->get("/admin/inquiries/{$inquiry->id}");
        $detailResponse->assertStatus(200);

        // Customer details
        $detailResponse->assertSee('Claire Dupont');
        $detailResponse->assertSee('Dupont Agro Importation');
        $detailResponse->assertSee('claire@dupontagro.fr');
        $detailResponse->assertSee('Le Havre Port');
        $detailResponse->assertSee('Requested Commodities &amp; Products', false);
        $detailResponse->assertSee('Cumin Seeds Machine Cleaned');
        $detailResponse->assertSee('09093129'); // HS Code
        $detailResponse->assertSee('25kg poly-lined bags');
    }

    public function test_admin_can_update_status_across_all_lifecycle_stages(): void
    {
        $inquiry = Inquiry::create([
            'reference_no' => 'SA-2026-LIFE01',
            'inquiry_type' => Inquiry::TYPE_QUOTE,
            'full_name' => 'Stefan Zweig',
            'email' => 'stefan@austriatrade.at',
            'phone' => '+43 1 505 1234',
            'country' => 'Austria',
            'message' => 'Inquiry for cumin and groundnuts.',
            'status' => Inquiry::STATUS_NEW,
        ]);

        // Stage 1: Under Evaluation (in_progress)
        $res1 = $this->actingAs($this->adminUser)->post("/admin/inquiries/{$inquiry->id}/status", [
            'status' => Inquiry::STATUS_IN_PROGRESS,
            'status_note' => 'Evaluating export freight rates to Vienna.',
        ]);
        $res1->assertRedirect();
        $this->assertEquals(Inquiry::STATUS_IN_PROGRESS, $inquiry->fresh()->status);

        // Stage 2: Quotation Sent (responded)
        $res2 = $this->actingAs($this->adminUser)->post("/admin/inquiries/{$inquiry->id}/status", [
            'status' => Inquiry::STATUS_RESPONDED,
            'status_note' => 'Dispatched formal proforma invoice #PI-2026-089.',
        ]);
        $res2->assertRedirect();
        $this->assertEquals(Inquiry::STATUS_RESPONDED, $inquiry->fresh()->status);

        // Stage 3: Deal Confirmed (accepted)
        $res3 = $this->actingAs($this->adminUser)->post("/admin/inquiries/{$inquiry->id}/status", [
            'status' => Inquiry::STATUS_ACCEPTED,
            'status_note' => 'Buyer confirmed purchase order and opened 100% LC.',
        ]);
        $res3->assertRedirect();
        $this->assertEquals(Inquiry::STATUS_ACCEPTED, $inquiry->fresh()->status);

        // Verify activity log captured every transition
        $activities = InquiryActivity::where('inquiry_id', $inquiry->id)->get();
        $this->assertGreaterThanOrEqual(3, $activities->count());
    }

    public function test_admin_status_filter_tabs_work_correctly(): void
    {
        $inqNew = Inquiry::create([
            'reference_no' => 'SA-2026-F1',
            'full_name' => 'Buyer Alpha',
            'email' => 'alpha@test.com',
            'phone' => '+1 111',
            'country' => 'USA',
            'message' => 'Testing new tab filter.',
            'status' => Inquiry::STATUS_NEW,
        ]);

        $inqAccepted = Inquiry::create([
            'reference_no' => 'SA-2026-F2',
            'full_name' => 'Buyer Beta',
            'email' => 'beta@test.com',
            'phone' => '+1 222',
            'country' => 'Canada',
            'message' => 'Testing accepted tab filter.',
            'status' => Inquiry::STATUS_ACCEPTED,
        ]);

        // Filter by 'new'
        $newTab = $this->actingAs($this->adminUser)->get('/admin/inquiries?status=new');
        $newTab->assertSee('Buyer Alpha');
        $newTab->assertDontSee('Buyer Beta');

        // Filter by 'accepted'
        $acceptedTab = $this->actingAs($this->adminUser)->get('/admin/inquiries?status=accepted');
        $acceptedTab->assertSee('Buyer Beta');
        $acceptedTab->assertDontSee('Buyer Alpha');
    }

    public function test_admin_search_finds_inquiries_by_product_name_and_buyer(): void
    {
        $inquiry = Inquiry::create([
            'reference_no' => 'SA-2026-SRCH1',
            'full_name' => 'Dmitri Ivanov',
            'email' => 'dmitri@moscowtrade.ru',
            'phone' => '+7 495 123 4567',
            'country' => 'Russia',
            'message' => 'Seeking cumin seeds shipment.',
            'status' => Inquiry::STATUS_NEW,
        ]);

        InquiryItem::create([
            'inquiry_id' => $inquiry->id,
            'product_id' => $this->productA->id,
            'product_name' => 'Cumin Seeds Machine Cleaned',
            'quantity' => '10 MT',
        ]);

        // 1. Search by buyer name
        $resBuyer = $this->actingAs($this->adminUser)->get('/admin/inquiries?search=Dmitri');
        $resBuyer->assertSee('Dmitri Ivanov');

        // 2. Search by commodity line item name
        $resProduct = $this->actingAs($this->adminUser)->get('/admin/inquiries?search=Cumin+Seeds');
        $resProduct->assertSee('Dmitri Ivanov');

        // 3. Search for unmatched term
        $resNone = $this->actingAs($this->adminUser)->get('/admin/inquiries?search=NonExistentCommodityXYZ');
        $resNone->assertDontSee('Dmitri Ivanov');
    }

    public function test_unauthorized_guests_cannot_access_or_modify_inquiries(): void
    {
        $inquiry = Inquiry::create([
            'reference_no' => 'SA-2026-SEC01',
            'full_name' => 'Protected Buyer',
            'email' => 'protected@trade.com',
            'phone' => '+1 333',
            'country' => 'UK',
            'message' => 'Testing security guest access.',
            'status' => Inquiry::STATUS_NEW,
        ]);

        // Guest attempting to view listing
        $resList = $this->get('/admin/inquiries');
        $resList->assertRedirect('/admin/login');

        // Guest attempting to view single inquiry
        $resShow = $this->get("/admin/inquiries/{$inquiry->id}");
        $resShow->assertRedirect('/admin/login');

        // Guest attempting to change status
        $resStatus = $this->post("/admin/inquiries/{$inquiry->id}/status", [
            'status' => Inquiry::STATUS_CLOSED,
        ]);
        $resStatus->assertRedirect('/admin/login');

        // Verify status remains unchanged
        $this->assertEquals(Inquiry::STATUS_NEW, $inquiry->fresh()->status);
    }

    /*
    |--------------------------------------------------------------------------
    | 5. End-to-End Integration Journey Test
    |--------------------------------------------------------------------------
    */

    public function test_complete_rfq_end_to_end_journey(): void
    {
        // Step 1: Customer browses and adds 2 commodities to RFQ cart
        $this->postJson('/rfq/items', [
            'product_id' => $this->productA->id,
            'quantity' => 2,
            'notes' => 'Machine Cleaned 99.5% Purity',
        ])->assertStatus(200);

        $this->postJson('/rfq/items', [
            'product_id' => $this->productB->id,
            'quantity' => 1,
            'notes' => 'Bold Peanuts 40/50 count',
        ])->assertStatus(200);

        // Step 2: Customer verifies their RFQ list
        $cartCheck = $this->getJson('/rfq/items');
        $cartCheck->assertStatus(200);
        $cartCheck->assertJsonCount(2, 'items');

        // Step 3: Customer submits the RFQ form
        $submitResponse = $this->postJson('/inquiries/quote', [
            'full_name' => 'Faisal Al-Sabah',
            'company_name' => 'Kuwait Import & Distribution W.L.L.',
            'email' => 'faisal@kuwaitdistribution.kw',
            'phone' => '+965 2224 5678',
            'country' => 'Kuwait',
            'port_of_destination' => 'Shuwaikh Port, Kuwait',
            'message' => 'Please provide competitive CIF Shuwaikh quote. Ready to place initial 3 container trial order.',
            'items' => [
                ['product_id' => $this->productA->id, 'quantity' => 2, 'notes' => 'Machine Cleaned 99.5% Purity'],
                ['product_id' => $this->productB->id, 'quantity' => 1, 'notes' => 'Bold Peanuts 40/50 count'],
            ],
            'website_hp' => '',
        ]);

        $submitResponse->assertStatus(200);
        $refNo = $submitResponse->json('reference_no');
        $this->assertNotEmpty($refNo);

        // Step 4: Verify Database has inquiry and both items
        $inquiry = Inquiry::where('reference_no', $refNo)->with('items')->firstOrFail();
        $this->assertEquals('Faisal Al-Sabah', $inquiry->full_name);
        $this->assertCount(2, $inquiry->items);

        // Step 5: Admin logs in and views inquiry in dashboard & inquiries list
        $dashRes = $this->actingAs($this->adminUser)->get('/admin');
        $dashRes->assertStatus(200);
        $dashRes->assertSee('Kuwait Import &amp; Distribution', false);

        $listRes = $this->actingAs($this->adminUser)->get('/admin/inquiries');
        $listRes->assertStatus(200);
        $listRes->assertSee($refNo);
        $listRes->assertSee('Faisal Al-Sabah');

        // Step 6: Admin opens detail view to review line items
        $detailRes = $this->actingAs($this->adminUser)->get("/admin/inquiries/{$inquiry->id}");
        $detailRes->assertStatus(200);
        $detailRes->assertSee('Machine Cleaned 99.5% Purity');
        $detailRes->assertSee('Bold Peanuts 40/50 count');
        $detailRes->assertSee('Shuwaikh Port, Kuwait');

        // Step 7: Admin updates status to 'responded' with official quotation sent
        $quoteSentRes = $this->actingAs($this->adminUser)->post("/admin/inquiries/{$inquiry->id}/status", [
            'status' => Inquiry::STATUS_RESPONDED,
            'status_note' => 'Formal quotation PDF emailed with valid validity date of 7 days.',
        ]);
        $quoteSentRes->assertRedirect();
        $this->assertEquals(Inquiry::STATUS_RESPONDED, $inquiry->fresh()->status);

        // Step 8: Buyer accepts quote, Admin updates status to 'accepted'
        $acceptedRes = $this->actingAs($this->adminUser)->post("/admin/inquiries/{$inquiry->id}/status", [
            'status' => Inquiry::STATUS_ACCEPTED,
            'status_note' => 'Contract signed! Order placed for 3 FCL shipments.',
        ]);
        $acceptedRes->assertRedirect();
        $this->assertEquals(Inquiry::STATUS_ACCEPTED, $inquiry->fresh()->status);

        // Step 9: Verify updated status appears in admin index
        $updatedList = $this->actingAs($this->adminUser)->get('/admin/inquiries?status=accepted');
        $updatedList->assertStatus(200);
        $updatedList->assertSee($refNo);
        $updatedList->assertSee('Accepted');
    }
}
