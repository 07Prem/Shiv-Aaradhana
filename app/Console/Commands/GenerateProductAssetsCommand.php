<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateProductAssetsCommand extends Command
{
    protected $signature = 'app:generate-assets';
    protected $description = 'Generate rich SVG imagery for categories and products';

    public function handle(): int
    {
        $categoriesDir = public_path('images/categories');
        $productsDir = public_path('images/products');

        File::ensureDirectoryExists($categoriesDir);
        File::ensureDirectoryExists($productsDir);

        $categories = [
            'agro-products.jpg' => ['title' => 'Agro Products & Oilseeds', 'subtitle' => 'Gujarat Mandi Direct Sourcing', 'c1' => '#091433', 'c2' => '#394F3D', 'accent' => '#EBD6B4', 'icon' => '🌾'],
            'spices-food.jpg' => ['title' => 'Spices & Food Products', 'subtitle' => 'Aromatic Sortex Grade Purity', 'c1' => '#561118', 'c2' => '#9C451B', 'accent' => '#EBD6B4', 'icon' => '🌶️'],
            'textiles.jpg' => ['title' => 'Textiles & Cotton Fibres', 'subtitle' => 'Shankar-6 Bales & Ring Spun Yarns', 'c1' => '#091433', 'c2' => '#16244f', 'accent' => '#EBD6B4', 'icon' => '🧵'],
            'other-exports.jpg' => ['title' => 'Dehydrated Foods & Botanicals', 'subtitle' => 'White Onion Flakes & Psyllium Husk', 'c1' => '#394F3D', 'c2' => '#9C451B', 'accent' => '#EBD6B4', 'icon' => '🌱'],
        ];

        foreach ($categories as $filename => $info) {
            $svg = $this->renderSvg($info['title'], $info['subtitle'], $info['c1'], $info['c2'], $info['accent'], $info['icon'], 800, 500);
            File::put($categoriesDir . '/' . $filename, $svg);
        }

        $products = [
            'sesame-seeds.jpg' => ['title' => 'Natural White Sesame Seeds', 'subtitle' => 'Purity 99.9% Sortex Cleaned', 'c1' => '#394F3D', 'c2' => '#091433', 'accent' => '#EBD6B4', 'icon' => '✨'],
            'groundnuts.jpg' => ['title' => 'Bold Groundnuts (HPS Kernels)', 'subtitle' => 'Count 40/50 & 50/60 Raw', 'c1' => '#9C451B', 'c2' => '#561118', 'accent' => '#EBD6B4', 'icon' => '🥜'],
            'cumin-seeds.jpg' => ['title' => 'Premium Cumin Seeds (Jeera)', 'subtitle' => 'High Volatile Oil • Sortex Clean', 'c1' => '#091433', 'c2' => '#394F3D', 'accent' => '#EBD6B4', 'icon' => '🌿'],
            'fennel-seeds.jpg' => ['title' => 'Green Fennel Seeds (Variyali)', 'subtitle' => 'Natural Green Shade Dried', 'c1' => '#394F3D', 'c2' => '#16244f', 'accent' => '#EBD6B4', 'icon' => '🍃'],
            'coriander-seeds.jpg' => ['title' => 'Indian Coriander Seeds (Dhania)', 'subtitle' => 'Eagle & Scooter Round Grain', 'c1' => '#561118', 'c2' => '#9C451B', 'accent' => '#EBD6B4', 'icon' => '🌱'],
            'turmeric.jpg' => ['title' => 'Pure Turmeric Fingers & Powder', 'subtitle' => 'Curcumin 3% - 5% Polished', 'c1' => '#9C451B', 'c2' => '#091433', 'accent' => '#EBD6B4', 'icon' => '☀️'],
            'red-chilli.jpg' => ['title' => 'Sun-Dried Red Chillies', 'subtitle' => 'Sanam S4 / Teja Stemless', 'c1' => '#561118', 'c2' => '#9C451B', 'accent' => '#EBD6B4', 'icon' => '🌶️'],
            'cotton-bales.jpg' => ['title' => 'Gujarat Shankar-6 Cotton Bales', 'subtitle' => 'Staple 28.5mm+ • 170kg Pressed', 'c1' => '#091433', 'c2' => '#394F3D', 'accent' => '#EBD6B4', 'icon' => '☁️'],
            'cotton-yarn.jpg' => ['title' => '100% Combed Cotton Ring Spun', 'subtitle' => 'Ne 30s & 40s Weaving/Knitting', 'c1' => '#16244f', 'c2' => '#091433', 'accent' => '#EBD6B4', 'icon' => '🧵'],
            'dehydrated-onion.jpg' => ['title' => 'Dehydrated White Onion Flakes', 'subtitle' => 'Kibbled Flakes & Minced Granules', 'c1' => '#394F3D', 'c2' => '#9C451B', 'accent' => '#EBD6B4', 'icon' => '🧅'],
            'psyllium-husk.jpg' => ['title' => 'Organic Psyllium Husk 99%', 'subtitle' => 'Pharmaceutical & Food Grade Isabgol', 'c1' => '#091433', 'c2' => '#561118', 'accent' => '#EBD6B4', 'icon' => '🌾'],
        ];

        foreach ($products as $filename => $info) {
            $svg = $this->renderSvg($info['title'], $info['subtitle'], $info['c1'], $info['c2'], $info['accent'], $info['icon'], 600, 450);
            File::put($productsDir . '/' . $filename, $svg);
        }

        $this->info("Assets generated successfully in public/images/");
        return 0;
    }

    protected function renderSvg(string $title, string $subtitle, string $c1, string $c2, string $accent, string $icon, int $width, int $height): string
    {
        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {$width} {$height}" width="100%" height="100%">
  <defs>
    <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="{$c1}" />
      <stop offset="100%" stop-color="{$c2}" />
    </linearGradient>
    <radialGradient id="glow" cx="80%" cy="20%" r="60%">
      <stop offset="0%" stop-color="{$accent}" stop-opacity="0.25" />
      <stop offset="100%" stop-color="{$c1}" stop-opacity="0" />
    </radialGradient>
    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
      <path d="M 40 0 L 0 0 0 40" fill="none" stroke="{$accent}" stroke-width="0.5" stroke-opacity="0.12" />
    </pattern>
  </defs>
  
  <rect width="{$width}" height="{$height}" fill="url(#grad)" />
  <rect width="{$width}" height="{$height}" fill="url(#glow)" />
  <rect width="{$width}" height="{$height}" fill="url(#grid)" />
  
  <circle cx="{$width}" cy="0" r="180" fill="{$accent}" fill-opacity="0.08" />
  <circle cx="0" cy="{$height}" r="140" fill="{$accent}" fill-opacity="0.05" />

  <g transform="translate(40, 50)">
    <rect width="180" height="26" rx="4" fill="{$accent}" fill-opacity="0.15" stroke="{$accent}" stroke-opacity="0.3" stroke-width="1"/>
    <text x="12" y="17" font-family="'Plus Jakarta Sans', system-ui, sans-serif" font-size="11" font-weight="700" fill="{$accent}" letter-spacing="1.5">SHIV AARADHANA</text>
  </g>

  <g transform="translate(40, 160)">
    <text x="0" y="40" font-family="'Playfair Display', Georgia, serif" font-size="28" font-weight="700" fill="#ffffff" letter-spacing="0.2">
      {$title}
    </text>
    <text x="0" y="75" font-family="'Plus Jakarta Sans', system-ui, sans-serif" font-size="14" font-weight="400" fill="{$accent}" letter-spacing="0.5">
      {$subtitle}
    </text>
    
    <line x1="0" y1="100" x2="80" y2="100" stroke="{$accent}" stroke-width="3" stroke-linecap="round" />
    <text x="0" y="130" font-family="'Plus Jakarta Sans', system-ui, sans-serif" font-size="12" font-weight="600" fill="#ffffff" fill-opacity="0.75" letter-spacing="1">
      ORIGIN: GUJARAT, INDIA &bull; EXPORT BENCHMARK
    </text>
  </g>

  <g transform="translate({$width} - 110, {$height} - 110)">
    <circle cx="50" cy="50" r="44" fill="#ffffff" fill-opacity="0.08" stroke="{$accent}" stroke-opacity="0.3" stroke-width="1.5" />
    <text x="50" y="62" font-size="36" text-anchor="middle">{$icon}</text>
  </g>
</svg>
SVG;
    }
}
