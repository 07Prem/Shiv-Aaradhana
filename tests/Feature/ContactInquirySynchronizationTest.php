<?php

namespace Tests\Feature;

use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContactInquirySynchronizationTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->superAdmin = User::create([
            'name' => 'Super Administrator',
            'email' => 'admin@shivaaradhana.com',
            'password' => Hash::make('AdminPass123!'),
            'role' => User::ROLE_SUPER_ADMIN,
            'is_active' => true,
        ]);

        $this->regularUser = User::create([
            'name' => 'Guest User',
            'email' => 'guest@example.com',
            'password' => Hash::make('SecretPass123!'),
            'role' => 'buyer',
            'is_active' => true,
        ]);
    }

    public function test_public_contact_inquiry_persists_in_database_with_status_new(): void
    {
        $payload = [
            'full_name' => 'Jean-Luc Picard',
            'company_name' => 'Château Picard Exports',
            'email' => 'jeanluc@picard-trade.fr',
            'phone' => '+33 1 42 68 55 00',
            'country' => 'France',
            'subject' => 'Organic Cumin and Sesame Consignment for European Distribution',
            'message' => 'We are seeking 2 x 20ft FCL container loads of 99/1 sortex cleaned cumin seeds CIF Le Havre.',
            'website_hp' => '',
        ];

        // 1. Submit via Standard Form POST
        $response = $this->post('/inquiries/general', $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success_inquiry');

        // 2. Verify Database Persistence
        $this->assertDatabaseHas('inquiries', [
            'inquiry_type' => 'general',
            'full_name' => 'Jean-Luc Picard',
            'company_name' => 'Château Picard Exports',
            'email' => 'jeanluc@picard-trade.fr',
            'phone' => '+33 1 42 68 55 00',
            'country' => 'France',
            'subject' => 'Organic Cumin and Sesame Consignment for European Distribution',
            'status' => 'new',
        ]);

        $inquiry = Inquiry::where('email', 'jeanluc@picard-trade.fr')->firstOrFail();
        $this->assertStringStartsWith('SA-', $inquiry->reference_no);
        $this->assertNotEmpty($inquiry->activities);
    }

    public function test_contact_form_supports_ajax_json_submission(): void
    {
        $payload = [
            'full_name' => 'Sato Kenji',
            'company_name' => 'Tokyo Agro Trading Inc.',
            'email' => 'kenji@tokyo-agro.jp',
            'phone' => '+81 3 5555 0199',
            'country' => 'Japan',
            'subject' => 'Hulled Sesame Seeds Specifications Inquiry',
            'message' => 'Please provide microbiological analysis and pesticide residue limits for Sortex sesame seeds.',
            'website_hp' => '',
        ];

        $response = $this->postJson('/inquiries/general', $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $json = $response->json();
        $this->assertNotEmpty($json['reference_no']);
        $this->assertDatabaseHas('inquiries', [
            'reference_no' => $json['reference_no'],
            'email' => 'kenji@tokyo-agro.jp',
        ]);
    }

    public function test_validation_rejects_missing_fields_and_invalid_email(): void
    {
        // Missing name and invalid email
        $response = $this->post('/inquiries/general', [
            'email' => 'not-an-email',
            'phone' => '+1234',
            'country' => 'US',
            'message' => 'Too short',
        ]);

        $response->assertSessionHasErrors(['full_name', 'email', 'message']);
    }

    public function test_honeypot_discards_automated_spam_submission(): void
    {
        $response = $this->post('/inquiries/general', [
            'full_name' => 'Spam Bot',
            'email' => 'bot@spammer.com',
            'phone' => '+1234567890',
            'country' => 'Russia',
            'message' => 'Buy cheap links now http://spam.example.com',
            'website_hp' => 'I am a bot', // Honeypot trap filled
        ]);

        $response->assertSessionHasErrors(['website_hp']);
        $this->assertDatabaseMissing('inquiries', ['email' => 'bot@spammer.com']);
    }

    public function test_admin_can_retrieve_filter_and_search_contact_inquiries(): void
    {
        $contactInquiry = Inquiry::create([
            'reference_no' => 'SA-2026-CT0001',
            'inquiry_type' => 'general',
            'full_name' => 'Dimitri Rostov',
            'company_name' => 'Black Sea Commodities',
            'email' => 'dimitri@blacksea.gr',
            'phone' => '+30 210 123456',
            'country' => 'Greece',
            'subject' => 'Sortex Sesame Seeds Shipment to Piraeus',
            'message' => 'Looking for CIF Piraeus rates on 1 x 20ft FCL container.',
            'status' => 'new',
        ]);

        $quoteInquiry = Inquiry::create([
            'reference_no' => 'SA-2026-QT0001',
            'inquiry_type' => 'quote',
            'full_name' => 'Carlos Mendez',
            'company_name' => 'Valencia Spices S.L.',
            'email' => 'carlos@valenciaspices.es',
            'phone' => '+34 96 123 4567',
            'country' => 'Spain',
            'message' => 'Requesting official FOB price schedule.',
            'status' => 'in_progress',
        ]);

        // 1. Admin Index loads all inquiries
        $resAll = $this->actingAs($this->superAdmin)->get('/admin/inquiries');
        $resAll->assertStatus(200);
        $resAll->assertSee('Dimitri Rostov');
        $resAll->assertSee('Carlos Mendez');

        // 2. Filter by Contact Leads (type=general)
        $resGeneral = $this->actingAs($this->superAdmin)->get('/admin/inquiries?type=general');
        $resGeneral->assertStatus(200);
        $resGeneral->assertSee('Dimitri Rostov');
        $resGeneral->assertDontSee('Carlos Mendez');

        // 3. Filter by Quote RFQs (type=quote)
        $resQuote = $this->actingAs($this->superAdmin)->get('/admin/inquiries?type=quote');
        $resQuote->assertStatus(200);
        $resQuote->assertSee('Carlos Mendez');
        $resQuote->assertDontSee('Dimitri Rostov');

        // 4. Search by subject
        $searchRes = $this->actingAs($this->superAdmin)->get('/admin/inquiries?q=Piraeus');
        $searchRes->assertStatus(200);
        $searchRes->assertSee('Dimitri Rostov');
        $searchRes->assertDontSee('Carlos Mendez');

        // 5. Admin Detail View
        $detailRes = $this->actingAs($this->superAdmin)->get("/admin/inquiries/{$contactInquiry->id}");
        $detailRes->assertStatus(200);
        $detailRes->assertSee('Dimitri Rostov');
        $detailRes->assertSee('Sortex Sesame Seeds Shipment to Piraeus');
        $detailRes->assertSee('Looking for CIF Piraeus rates on 1 x 20ft FCL container.');
    }

    public function test_admin_can_update_status_and_add_internal_notes(): void
    {
        $inquiry = Inquiry::create([
            'reference_no' => 'SA-2026-STAT01',
            'inquiry_type' => 'general',
            'full_name' => 'Elena Vasquez',
            'email' => 'elena@vasquez-agro.mx',
            'phone' => '+52 55 1234 5678',
            'country' => 'Mexico',
            'subject' => 'Cotton Bales Specifications',
            'message' => 'Inquiring regarding Gujarat Shankar-6 raw cotton bales.',
            'status' => 'new',
        ]);

        $updateRes = $this->actingAs($this->superAdmin)->post("/admin/inquiries/{$inquiry->id}/status", [
            'status' => 'responded',
            'status_note' => 'Dispatched formal specification catalog and FOB Mundra price indication.',
        ]);

        $updateRes->assertRedirect();
        $this->assertDatabaseHas('inquiries', [
            'id' => $inquiry->id,
            'status' => 'responded',
        ]);

        // Add internal note
        $noteRes = $this->actingAs($this->superAdmin)->post("/admin/inquiries/{$inquiry->id}/note", [
            'notes' => 'Follow up with buyer trade desk in 48 hours.',
        ]);

        $noteRes->assertRedirect();
        $this->assertDatabaseHas('inquiry_activities', [
            'inquiry_id' => $inquiry->id,
            'notes' => 'Follow up with buyer trade desk in 48 hours.',
        ]);
    }

    public function test_unauthorized_guests_cannot_view_or_modify_inquiries(): void
    {
        $inquiry = Inquiry::create([
            'reference_no' => 'SA-2026-SEC01',
            'inquiry_type' => 'general',
            'full_name' => 'Secret Buyer',
            'email' => 'secret@buyer.com',
            'phone' => '+1111111111',
            'country' => 'Canada',
            'message' => 'Private business trade message.',
            'status' => 'new',
        ]);

        // Guest redirected
        $this->get('/admin/inquiries')->assertRedirect('/admin/login');
        $this->get("/admin/inquiries/{$inquiry->id}")->assertRedirect('/admin/login');
        $this->post("/admin/inquiries/{$inquiry->id}/status", ['status' => 'closed'])->assertRedirect('/admin/login');

        // Regular user forbidden
        $this->actingAs($this->regularUser)->get('/admin/inquiries')->assertStatus(403);
    }

    public function test_email_failure_does_not_prevent_database_persistence(): void
    {
        // Fake Mail and simulate exception during dispatch
        Mail::shouldReceive('to->send')->andThrow(new \Exception('SMTP Server Timeout'));

        $payload = [
            'full_name' => 'Robert Thorne',
            'company_name' => 'Thorne Grains Ltd',
            'email' => 'robert@thornegrains.com',
            'phone' => '+44 161 999 8888',
            'country' => 'United Kingdom',
            'subject' => 'Barley and Sesame Contract',
            'message' => 'Please quote on prompt shipment 1 x 20ft FCL container.',
            'website_hp' => '',
        ];

        $response = $this->post('/inquiries/general', $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('inquiries', [
            'email' => 'robert@thornegrains.com',
            'full_name' => 'Robert Thorne',
            'status' => 'new',
        ]);
    }

    public function test_end_to_end_administrator_and_customer_workflow(): void
    {
        // 1. Admin logs in
        $loginRes = $this->post('/admin/login', [
            'email' => 'admin@shivaaradhana.com',
            'password' => 'AdminPass123!',
        ]);
        $loginRes->assertRedirect('/admin');
        $this->assertAuthenticatedAs($this->superAdmin);

        // 2. Admin uploads a new hero image
        $heroImage = UploadedFile::fake()->image('e2e_hero.jpg', 1920, 1080);
        $heroRes = $this->actingAs($this->superAdmin)->post('/admin/branding/hero', [
            'hero_image_desktop' => $heroImage,
            'hero_image_alt' => 'E2E Verified Agro Origin Corridor',
        ]);
        $heroRes->assertRedirect('/admin/branding?tab=hero');

        // 3. Open public homepage and verify the new hero image
        $homeRes = $this->get('/');
        $homeRes->assertStatus(200);
        $homeRes->assertSee('E2E Verified Agro Origin Corridor');

        // 4. Admin uploads new logo
        $logoImage = UploadedFile::fake()->image('e2e_logo.png', 400, 100);
        $logoRes = $this->actingAs($this->superAdmin)->post('/admin/branding/logo', [
            'light_logo' => $logoImage,
            'company_name' => 'Shiv Aaradhana Enterprise',
        ]);
        $logoRes->assertRedirect('/admin/branding?tab=branding');

        // 5. Verify updated logo in header and footer
        $homeAfterLogoRes = $this->get('/');
        $homeAfterLogoRes->assertStatus(200);
        $homeAfterLogoRes->assertSee('Shiv Aaradhana Enterprise');

        // 6. Public visitor visits Contact page and submits an inquiry
        auth()->logout();
        $inquiryRes = $this->post('/inquiries/general', [
            'full_name' => 'Heinrich Müller',
            'company_name' => 'Hamburg Speditions GmbH',
            'email' => 'heinrich@hamburg-spedition.de',
            'phone' => '+49 40 1234 5678',
            'country' => 'Germany',
            'subject' => 'Sesame Seeds 99/1 CIF Hamburg Request',
            'message' => 'Please provide proforma invoice for 20 MT Natural White Sesame Seeds 99/1.',
            'website_hp' => '',
        ]);
        $inquiryRes->assertRedirect();

        // 7. Admin logs back in, locates new inquiry, and updates its status
        $this->actingAs($this->superAdmin);
        $listRes = $this->get('/admin/inquiries?type=general');
        $listRes->assertStatus(200);
        $listRes->assertSee('Heinrich Müller');
        $listRes->assertSee('Hamburg Speditions GmbH');
        $listRes->assertSee('Sesame Seeds 99/1 CIF Hamburg Request');

        $inquiry = Inquiry::where('email', 'heinrich@hamburg-spedition.de')->firstOrFail();
        $this->assertEquals('new', $inquiry->status);

        // Update status to in_progress
        $statusRes = $this->post("/admin/inquiries/{$inquiry->id}/status", [
            'status' => 'in_progress',
            'status_note' => 'Preparing Proforma Invoice with export desk.',
        ]);
        $statusRes->assertRedirect();

        // 8. Verify status persisted after reload
        $freshInquiry = $inquiry->fresh();
        $this->assertEquals('in_progress', $freshInquiry->status);

        $detailRes = $this->get("/admin/inquiries/{$inquiry->id}");
        $detailRes->assertStatus(200);
        $detailRes->assertSee('In Progress');
        $detailRes->assertSee('Preparing Proforma Invoice with export desk.');
    }
}
