<?php

namespace Tests\Feature;

use App\Models\CmsSection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BrandingAndHeroManagementTest extends TestCase
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
            'name' => 'Regular User',
            'email' => 'guest@example.com',
            'password' => Hash::make('SecretPass123!'),
            'role' => 'buyer',
            'is_active' => true,
        ]);

        CmsSection::create([
            'section_key' => 'hero',
            'title' => 'From Indian Roots to Global Markets.',
            'subtitle' => 'Shiv Aaradhana Private Limited',
            'content' => 'Discover agricultural commodities and spices.',
            'is_active' => true,
            'payload' => [
                'hero_image_desktop' => null,
                'hero_image_mobile' => null,
                'hero_image_alt' => null,
            ],
        ]);
    }

    public function test_unauthorized_users_cannot_access_or_change_branding(): void
    {
        $guestRes = $this->get('/admin/branding');
        $guestRes->assertRedirect('/admin/login');

        $userRes = $this->actingAs($this->regularUser)->get('/admin/branding');
        $userRes->assertStatus(403);

        $userPostRes = $this->actingAs($this->regularUser)->post('/admin/branding/logo', [
            'main_logo' => UploadedFile::fake()->image('logo.png', 200, 200),
        ]);
        $userPostRes->assertStatus(403);

        // Guest attempts should redirect to login
        auth()->logout();
        $guestPostRes = $this->post('/admin/branding/logo', [
            'main_logo' => UploadedFile::fake()->image('logo.png', 200, 200),
        ]);
        $guestPostRes->assertRedirect('/admin/login');
    }

    public function test_admin_can_upload_and_replace_hero_images(): void
    {
        $desktopImage = UploadedFile::fake()->image('gujarat_farms.jpg', 1920, 1080);
        $mobileImage = UploadedFile::fake()->image('gujarat_mobile.webp', 800, 1000);

        $response = $this->actingAs($this->superAdmin)->post('/admin/branding/hero', [
            'hero_image_desktop' => $desktopImage,
            'hero_image_mobile' => $mobileImage,
            'hero_image_alt' => 'Gujarat Organic Sesame Sourcing Corridors',
            'title' => 'Direct Agro Exports from Gujarat',
        ]);

        $response->assertRedirect('/admin/branding?tab=hero');
        $response->assertSessionHas('success');

        $hero = CmsSection::getByKey('hero');
        $this->assertNotNull($hero->payload['hero_image_desktop']);
        $this->assertNotNull($hero->payload['hero_image_mobile']);
        $this->assertEquals('Gujarat Organic Sesame Sourcing Corridors', $hero->payload['hero_image_alt']);
        $this->assertEquals('Direct Agro Exports from Gujarat', $hero->title);

        // Verify public homepage renders the new hero image
        $homeRes = $this->get('/');
        $homeRes->assertStatus(200);
        $homeRes->assertSee($hero->payload['hero_image_desktop']);
        $homeRes->assertSee('Gujarat Organic Sesame Sourcing Corridors');

        // Test Replacing Hero Image
        $replacementImage = UploadedFile::fake()->image('new_harvest.png', 1920, 1080);
        $replaceRes = $this->actingAs($this->superAdmin)->post('/admin/branding/hero', [
            'hero_image_desktop' => $replacementImage,
            'hero_image_alt' => 'Updated Winter Harvest Sesame Fields',
        ]);

        $replaceRes->assertRedirect('/admin/branding?tab=hero');
        $heroUpdated = CmsSection::getByKey('hero');
        $this->assertNotEquals($hero->payload['hero_image_desktop'], $heroUpdated->payload['hero_image_desktop']);
        $this->assertEquals('Updated Winter Harvest Sesame Fields', $heroUpdated->payload['hero_image_alt']);

        $homeUpdatedRes = $this->get('/');
        $homeUpdatedRes->assertSee($heroUpdated->payload['hero_image_desktop']);
    }

    public function test_invalid_hero_image_file_is_rejected(): void
    {
        $badFile = UploadedFile::fake()->create('malicious.php', 100, 'text/php');

        $response = $this->actingAs($this->superAdmin)->post('/admin/branding/hero', [
            'hero_image_desktop' => $badFile,
        ]);

        $response->assertSessionHasErrors(['hero_image_desktop']);
    }

    public function test_removing_hero_image_reverts_to_default_fallback(): void
    {
        $desktopImage = UploadedFile::fake()->image('temp_hero.jpg', 800, 600);
        $this->actingAs($this->superAdmin)->post('/admin/branding/hero', [
            'hero_image_desktop' => $desktopImage,
        ]);

        // Now remove it
        $removeRes = $this->actingAs($this->superAdmin)->post('/admin/branding/hero', [
            'remove_desktop_image' => '1',
        ]);

        $removeRes->assertSessionHas('success');
        $hero = CmsSection::getByKey('hero');
        $this->assertNull($hero->payload['hero_image_desktop']);

        // Public site should render default fallback
        $homeRes = $this->get('/');
        $homeRes->assertStatus(200);
        $homeRes->assertSee('/images/categories/agro-products.jpg');
    }

    public function test_admin_can_upload_and_replace_company_logos_and_favicon(): void
    {
        $mainLogo = UploadedFile::fake()->image('sa_logo_main.png', 400, 100);
        $lightLogo = UploadedFile::fake()->image('sa_logo_white.png', 400, 100);
        $favicon = UploadedFile::fake()->image('favicon.png', 32, 32);

        $response = $this->actingAs($this->superAdmin)->post('/admin/branding/logo', [
            'main_logo' => $mainLogo,
            'light_logo' => $lightLogo,
            'favicon' => $favicon,
            'company_name' => 'Shiv Aaradhana Global Exports',
        ]);

        $response->assertRedirect('/admin/branding?tab=branding');
        $response->assertSessionHas('success');

        $branding = CmsSection::getByKey('branding');
        $this->assertNotNull($branding->payload['main_logo']);
        $this->assertNotNull($branding->payload['light_logo']);
        $this->assertNotNull($branding->payload['favicon']);
        $this->assertEquals('Shiv Aaradhana Global Exports', $branding->payload['company_name']);

        // Verify public site renders the active logo in header & footer
        $homeRes = $this->get('/');
        $homeRes->assertStatus(200);
        $homeRes->assertSee($branding->payload['light_logo']);
        $homeRes->assertSee($branding->payload['favicon']);

        // Test Replacing Logo
        $newMainLogo = UploadedFile::fake()->image('sa_brand_v2.png', 500, 120);
        $replaceRes = $this->actingAs($this->superAdmin)->post('/admin/branding/logo', [
            'main_logo' => $newMainLogo,
        ]);

        $replaceRes->assertSessionHas('success');
        $brandingUpdated = CmsSection::getByKey('branding');
        $this->assertNotEquals($branding->payload['main_logo'], $brandingUpdated->payload['main_logo']);

        // Test Removing Logo reverts to graceful fallback
        $removeRes = $this->actingAs($this->superAdmin)->post('/admin/branding/logo', [
            'remove_main_logo' => '1',
            'remove_light_logo' => '1',
        ]);

        $removeRes->assertSessionHas('success');
        $brandingPurged = CmsSection::getByKey('branding');
        $this->assertNull($brandingPurged->payload['main_logo']);
        $this->assertNull($brandingPurged->payload['light_logo']);

        $homeFallbackRes = $this->get('/');
        $homeFallbackRes->assertSee('font-heading font-black text-xl text-[#EBD6B4] tracking-tight');
    }

    public function test_invalid_logo_files_are_rejected(): void
    {
        $exeFile = UploadedFile::fake()->create('shell.exe', 50, 'application/x-msdownload');

        $response = $this->actingAs($this->superAdmin)->post('/admin/branding/logo', [
            'main_logo' => $exeFile,
        ]);

        $response->assertSessionHasErrors(['main_logo']);
    }
}
