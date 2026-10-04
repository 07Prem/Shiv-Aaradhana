<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$section = \App\Models\CmsSection::firstOrCreate(
    ['section_key' => 'branding'],
    ['title' => 'Corporate Identity & Branding', 'is_active' => true, 'payload' => []]
);
$payload = $section->payload ?? [];
$payload['main_logo'] = '/images/logo.png';
$payload['light_logo'] = '/images/logo.png';
$section->update(['payload' => $payload]);
\Illuminate\Support\Facades\Cache::forget('app_branding_settings');
echo "Branding updated successfully to PNG.\n";
