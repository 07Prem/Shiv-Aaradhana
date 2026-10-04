<?php

namespace App\Services\Settings;

use App\Models\CmsSection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;

class BrandingService
{
    protected const CACHE_KEY_BRANDING = 'app_branding_settings';
    protected const CACHE_KEY_HERO = 'app_hero_settings';

    protected array $allowedLogoMimes = [
        'image/png',
        'image/jpeg',
        'image/webp',
        'image/svg+xml',
        'image/x-icon',
        'image/vnd.microsoft.icon',
    ];

    protected array $allowedHeroMimes = [
        'image/jpeg',
        'image/png',
        'image/webp',
    ];

    /**
     * Retrieve active branding configuration.
     *
     * @return array{main_logo: ?string, light_logo: ?string, favicon: ?string, company_name: string}
     */
    public function getBranding(): array
    {
        return Cache::rememberForever(self::CACHE_KEY_BRANDING, function () {
            $section = CmsSection::getByKey('branding');
            $payload = $section?->payload ?? [];

            return [
                'main_logo' => $payload['main_logo'] ?? null,
                'light_logo' => $payload['light_logo'] ?? null,
                'favicon' => $payload['favicon'] ?? null,
                'company_name' => $payload['company_name'] ?? 'Shiv Aaradhana Private Limited',
            ];
        });
    }

    /**
     * Retrieve active hero images configuration.
     *
     * @return array{desktop: string, mobile: string, alt: string, has_custom_desktop: bool, has_custom_mobile: bool}
     */
    public function getHeroImages(): array
    {
        return Cache::rememberForever(self::CACHE_KEY_HERO, function () {
            $section = CmsSection::getByKey('hero');
            $payload = $section?->payload ?? [];

            $defaultDesktop = '/images/categories/agro-products.jpg';
            $desktop = ! empty($payload['hero_image_desktop']) ? $payload['hero_image_desktop'] : $defaultDesktop;
            $mobile = ! empty($payload['hero_image_mobile']) ? $payload['hero_image_mobile'] : $desktop;
            $alt = ! empty($payload['hero_image_alt']) ? $payload['hero_image_alt'] : 'Shiv Aaradhana Agricultural Heritage and Export Commodities';

            return [
                'desktop' => $desktop,
                'mobile' => $mobile,
                'alt' => $alt,
                'has_custom_desktop' => ! empty($payload['hero_image_desktop']),
                'has_custom_mobile' => ! empty($payload['hero_image_mobile']),
            ];
        });
    }

    /**
     * Update branding assets (main logo, light logo, favicon).
     */
    public function updateBranding(
        ?UploadedFile $mainLogo = null,
        ?UploadedFile $lightLogo = null,
        ?UploadedFile $favicon = null,
        array $removeFlags = [],
        ?string $companyName = null
    ): void {
        $section = CmsSection::firstOrCreate(
            ['section_key' => 'branding'],
            [
                'title' => 'Corporate Identity & Branding',
                'subtitle' => 'Logos, Favicon & Brand Presentation',
                'is_active' => true,
                'payload' => [],
            ]
        );

        $payload = $section->payload ?? [];

        // Handle Removals
        if (! empty($removeFlags['remove_main_logo']) && ! empty($payload['main_logo'])) {
            $this->deleteStoredFile($payload['main_logo']);
            $payload['main_logo'] = null;
        }

        if (! empty($removeFlags['remove_light_logo']) && ! empty($payload['light_logo'])) {
            $this->deleteStoredFile($payload['light_logo']);
            $payload['light_logo'] = null;
        }

        if (! empty($removeFlags['remove_favicon']) && ! empty($payload['favicon'])) {
            $this->deleteStoredFile($payload['favicon']);
            $payload['favicon'] = null;
        }

        // Handle Main Logo Upload
        if ($mainLogo !== null) {
            $this->validateFile($mainLogo, $this->allowedLogoMimes, 3 * 1024 * 1024);
            if (! empty($payload['main_logo'])) {
                $this->deleteStoredFile($payload['main_logo']);
            }
            $filename = 'logo-main-' . Str::uuid() . '.' . $this->resolveExtension($mainLogo);
            $path = $mainLogo->storeAs('branding', $filename, 'public');
            $payload['main_logo'] = Storage::url($path);
        }

        // Handle Light Logo Upload (for dark navigation bars)
        if ($lightLogo !== null) {
            $this->validateFile($lightLogo, $this->allowedLogoMimes, 3 * 1024 * 1024);
            if (! empty($payload['light_logo'])) {
                $this->deleteStoredFile($payload['light_logo']);
            }
            $filename = 'logo-light-' . Str::uuid() . '.' . $this->resolveExtension($lightLogo);
            $path = $lightLogo->storeAs('branding', $filename, 'public');
            $payload['light_logo'] = Storage::url($path);
        }

        // Handle Favicon Upload
        if ($favicon !== null) {
            $this->validateFile($favicon, $this->allowedLogoMimes, 1024 * 1024);
            if (! empty($payload['favicon'])) {
                $this->deleteStoredFile($payload['favicon']);
            }
            $filename = 'favicon-' . Str::uuid() . '.' . $this->resolveExtension($favicon);
            $path = $favicon->storeAs('branding', $filename, 'public');
            $payload['favicon'] = Storage::url($path);
        }

        if ($companyName !== null) {
            $payload['company_name'] = trim($companyName);
        }

        $section->update(['payload' => $payload]);
        Cache::forget(self::CACHE_KEY_BRANDING);
    }

    /**
     * Update hero images and attributes.
     */
    public function updateHeroImages(
        ?UploadedFile $desktopImage = null,
        ?UploadedFile $mobileImage = null,
        ?string $altText = null,
        array $removeFlags = []
    ): void {
        $section = CmsSection::firstOrCreate(
            ['section_key' => 'hero'],
            [
                'title' => 'From Indian Roots to Global Markets.',
                'subtitle' => 'Shiv Aaradhana Private Limited',
                'content' => 'Discover agricultural produce, spices, food products, and textiles sourced with care from India’s agricultural heartlands and prepared to meet rigorous international B2B buyer specifications.',
                'is_active' => true,
                'payload' => [],
            ]
        );

        $payload = $section->payload ?? [];

        // Handle Removals
        if (! empty($removeFlags['remove_desktop_image']) && ! empty($payload['hero_image_desktop'])) {
            $this->deleteStoredFile($payload['hero_image_desktop']);
            $payload['hero_image_desktop'] = null;
        }

        if (! empty($removeFlags['remove_mobile_image']) && ! empty($payload['hero_image_mobile'])) {
            $this->deleteStoredFile($payload['hero_image_mobile']);
            $payload['hero_image_mobile'] = null;
        }

        // Handle Desktop Hero Upload
        if ($desktopImage !== null) {
            $this->validateFile($desktopImage, $this->allowedHeroMimes, 8 * 1024 * 1024);
            if (! empty($payload['hero_image_desktop'])) {
                $this->deleteStoredFile($payload['hero_image_desktop']);
            }
            $filename = 'hero-desktop-' . Str::uuid() . '.' . $this->resolveExtension($desktopImage);
            $path = $desktopImage->storeAs('hero', $filename, 'public');
            $payload['hero_image_desktop'] = Storage::url($path);
        }

        // Handle Mobile Hero Upload
        if ($mobileImage !== null) {
            $this->validateFile($mobileImage, $this->allowedHeroMimes, 8 * 1024 * 1024);
            if (! empty($payload['hero_image_mobile'])) {
                $this->deleteStoredFile($payload['hero_image_mobile']);
            }
            $filename = 'hero-mobile-' . Str::uuid() . '.' . $this->resolveExtension($mobileImage);
            $path = $mobileImage->storeAs('hero', $filename, 'public');
            $payload['hero_image_mobile'] = Storage::url($path);
        }

        if ($altText !== null) {
            $payload['hero_image_alt'] = trim($altText);
        }

        $section->update(['payload' => $payload]);
        Cache::forget(self::CACHE_KEY_HERO);
    }

    /**
     * Purge stored file from public disk.
     */
    protected function deleteStoredFile(string $url): void
    {
        $relativePath = Str::after($url, '/storage/');
        if ($relativePath && Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }
    }

    /**
     * Validate file type and size.
     */
    protected function validateFile(UploadedFile $file, array $allowedMimes, int $maxSizeBytes): void
    {
        $mime = $file->getMimeType();
        if (! in_array($mime, $allowedMimes, true)) {
            throw new InvalidArgumentException("Invalid file format ({$mime}). Permitted formats: " . implode(', ', $allowedMimes));
        }

        if ($file->getSize() > $maxSizeBytes) {
            $maxMb = round($maxSizeBytes / 1024 / 1024, 1);
            throw new InvalidArgumentException("File exceeds maximum allowable size of {$maxMb}MB.");
        }
    }

    /**
     * Resolve safe extension.
     */
    protected function resolveExtension(UploadedFile $file): string
    {
        $ext = strtolower($file->getClientOriginalExtension());
        if (in_array($ext, ['php', 'exe', 'sh', 'bat', 'html', 'js', 'phtml'], true) || empty($ext)) {
            return $file->guessExtension() ?? 'bin';
        }
        return $ext;
    }
}
