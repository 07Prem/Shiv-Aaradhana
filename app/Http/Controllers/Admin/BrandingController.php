<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\CmsSection;
use App\Services\Settings\BrandingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BrandingController extends Controller
{
    public function __construct(protected BrandingService $brandingService)
    {
    }

    public function index(Request $request): View
    {
        $branding = $this->brandingService->getBranding();
        $heroData = $this->brandingService->getHeroImages();
        $heroSection = CmsSection::getByKey('hero');

        $activeTab = $request->query('tab', 'hero');

        return view('admin.branding.index', compact('branding', 'heroData', 'heroSection', 'activeTab'));
    }

    public function updateBranding(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'main_logo' => ['nullable', 'image', 'mimes:jpeg,png,webp,svg', 'max:3072'],
            'light_logo' => ['nullable', 'image', 'mimes:jpeg,png,webp,svg', 'max:3072'],
            'favicon' => ['nullable', 'file', 'mimes:jpeg,png,webp,ico,svg', 'max:1024'],
            'remove_main_logo' => ['nullable', 'boolean'],
            'remove_light_logo' => ['nullable', 'boolean'],
            'remove_favicon' => ['nullable', 'boolean'],
            'company_name' => ['nullable', 'string', 'max:120'],
        ]);

        $this->brandingService->updateBranding(
            mainLogo: $request->file('main_logo'),
            lightLogo: $request->file('light_logo'),
            favicon: $request->file('favicon'),
            removeFlags: [
                'remove_main_logo' => $request->boolean('remove_main_logo'),
                'remove_light_logo' => $request->boolean('remove_light_logo'),
                'remove_favicon' => $request->boolean('remove_favicon'),
            ],
            companyName: $validated['company_name'] ?? null
        );

        AuditLog::record('branding_updated', "Updated corporate identity and logo configuration");

        return redirect()->route('admin.branding.index', ['tab' => 'branding'])
            ->with('success', 'Corporate branding, logos, and favicon settings successfully updated.');
    }

    public function updateHero(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'hero_image_desktop' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:8192'],
            'hero_image_mobile' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:8192'],
            'hero_image_alt' => ['nullable', 'string', 'max:255'],
            'remove_desktop_image' => ['nullable', 'boolean'],
            'remove_mobile_image' => ['nullable', 'boolean'],
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string', 'max:1000'],
        ]);

        $this->brandingService->updateHeroImages(
            desktopImage: $request->file('hero_image_desktop'),
            mobileImage: $request->file('hero_image_mobile'),
            altText: $validated['hero_image_alt'] ?? null,
            removeFlags: [
                'remove_desktop_image' => $request->boolean('remove_desktop_image'),
                'remove_mobile_image' => $request->boolean('remove_mobile_image'),
            ]
        );

        // Update hero copy if provided
        $hero = CmsSection::getByKey('hero');
        if ($hero) {
            $updates = array_filter([
                'title' => $validated['title'] ?? null,
                'subtitle' => $validated['subtitle'] ?? null,
                'content' => $validated['content'] ?? null,
            ]);
            if (! empty($updates)) {
                $hero->update($updates);
            }
        }

        AuditLog::record('hero_updated', "Updated homepage hero visual assets and presentation text", $hero);

        return redirect()->route('admin.branding.index', ['tab' => 'hero'])
            ->with('success', 'Homepage hero visuals and presentation settings successfully updated.');
    }
}
