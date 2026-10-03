<?php

namespace Database\Seeders;

use App\Models\AttributeDefinition;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttributeValue;
use App\Models\ProductRecommendation;
use App\Models\ProductType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Categories
        $categoriesData = [
            [
                'name' => 'Agro Products',
                'slug' => 'agro-products',
                'description' => 'Sourced directly from fertile agricultural belts across Saurashtra and Gujarat, featuring premium oilseeds, grains, and staple cash crops harvested with traditional care.',
                'image_path' => '/images/categories/agro-products.jpg',
                'icon' => 'leaf',
                'sort_order' => 1,
            ],
            [
                'name' => 'Spices and Food Products',
                'slug' => 'spices-and-food-products',
                'description' => 'Aromatic, sun-dried, sortex-cleaned Indian spices prized worldwide for intense volatile oil content, natural color retention, and rigorous purity standards.',
                'image_path' => '/images/categories/spices-food.jpg',
                'icon' => 'sparkles',
                'sort_order' => 2,
            ],
            [
                'name' => 'Textiles and Fabrics',
                'slug' => 'textiles-and-fabrics',
                'description' => 'Gujarat Shankar-6 raw cotton bales, combed ring-spun cotton yarns, and durable industrial greige fabrics serving international apparel mills.',
                'image_path' => '/images/categories/textiles.jpg',
                'icon' => 'scissors',
                'sort_order' => 3,
            ],
            [
                'name' => 'Other Export Products',
                'slug' => 'other-export-products',
                'description' => 'High-value value-added agro commodities including air-dehydrated onions, garlic granules, and pharmaceutical-grade psyllium husk.',
                'image_path' => '/images/categories/other-exports.jpg',
                'icon' => 'cube',
                'sort_order' => 4,
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $c) {
            $categories[$c['slug']] = Category::updateOrCreate(['slug' => $c['slug']], $c);
        }

        // 2. Product Types
        $typesData = [
            [
                'category_id' => $categories['agro-products']->id,
                'name' => 'Oilseeds & Groundnuts',
                'slug' => 'oilseeds-and-groundnuts',
                'description' => 'Selected bold kernels, natural and hulled sesame, and premium export-grade oilseeds.',
                'sort_order' => 1,
            ],
            [
                'category_id' => $categories['agro-products']->id,
                'name' => 'Agricultural Seeds & Grains',
                'slug' => 'agricultural-seeds-and-grains',
                'description' => 'Cleaning, sorting, and export sizing for whole commercial seed varieties.',
                'sort_order' => 2,
            ],
            [
                'category_id' => $categories['spices-and-food-products']->id,
                'name' => 'Whole Spices',
                'slug' => 'whole-spices',
                'description' => 'Machine cleaned, Sortex-graded whole aromatic seeds and sun-dried pods.',
                'sort_order' => 1,
            ],
            [
                'category_id' => $categories['spices-and-food-products']->id,
                'name' => 'Ground & Processed Spices',
                'slug' => 'ground-and-processed-spices',
                'description' => 'Cold-milled powders with preserved essential oil profiles and zero artificial colors.',
                'sort_order' => 2,
            ],
            [
                'category_id' => $categories['textiles-and-fabrics']->id,
                'name' => 'Raw Cotton & Fibres',
                'slug' => 'raw-cotton-and-fibres',
                'description' => 'Gujarat Shankar-6 premium staple cotton bailing with low trash content and strong micronaire.',
                'sort_order' => 1,
            ],
            [
                'category_id' => $categories['textiles-and-fabrics']->id,
                'name' => 'Cotton Yarns & Fabrics',
                'slug' => 'cotton-yarns-and-fabrics',
                'description' => '100% Combed ring spun yarn Ne 20s to 40s and industrial greige weaving fabrics.',
                'sort_order' => 2,
            ],
            [
                'category_id' => $categories['other-export-products']->id,
                'name' => 'Dehydrated Agro Foods',
                'slug' => 'dehydrated-agro-foods',
                'description' => 'Advanced hot-air dehydrated white/red onion flakes, minced, powder, and garlic cloves.',
                'sort_order' => 1,
            ],
            [
                'category_id' => $categories['other-export-products']->id,
                'name' => 'Botanical & Psyllium Products',
                'slug' => 'botanical-and-psyllium-products',
                'description' => 'High-swelling psyllium husk and seed powder for dietary, nutraceutical, and culinary uses.',
                'sort_order' => 2,
            ],
        ];

        $types = [];
        foreach ($typesData as $t) {
            $types[$t['slug']] = ProductType::updateOrCreate(['slug' => $t['slug']], $t);
        }

        // 3. Attribute Definitions
        $attrs = [
            ['name' => 'Purity / Cleanliness', 'code' => 'purity', 'type' => 'number', 'unit' => '%', 'is_filterable' => true, 'sort_order' => 1],
            ['name' => 'Moisture Content', 'code' => 'moisture', 'type' => 'number', 'unit' => '%', 'is_filterable' => true, 'sort_order' => 2],
            ['name' => 'Admixture / Foreign Matter', 'code' => 'admixture', 'type' => 'number', 'unit' => '%', 'is_filterable' => false, 'sort_order' => 3],
            ['name' => 'Oil / Fat Content', 'code' => 'oil_content', 'type' => 'number', 'unit' => '%', 'is_filterable' => false, 'sort_order' => 4],
            ['name' => 'Sortex Grading', 'code' => 'sortex_grading', 'type' => 'text', 'unit' => null, 'is_filterable' => true, 'sort_order' => 5],
            ['name' => 'Crop Season', 'code' => 'crop_season', 'type' => 'text', 'unit' => null, 'is_filterable' => false, 'sort_order' => 6],
            ['name' => 'Cultivar / Variety', 'code' => 'variety', 'type' => 'text', 'unit' => null, 'is_filterable' => true, 'sort_order' => 7],
            ['name' => 'Staple Length / Count', 'code' => 'staple_count', 'type' => 'text', 'unit' => null, 'is_filterable' => false, 'sort_order' => 8],
        ];

        $attrDefs = [];
        foreach ($attrs as $a) {
            $attrDefs[$a['code']] = AttributeDefinition::updateOrCreate(['code' => $a['code']], $a);
        }

        // 4. Products
        $productsData = [
            [
                'category_id' => $categories['agro-products']->id,
                'product_type_id' => $types['oilseeds-and-groundnuts']->id,
                'name' => 'Natural White Sesame Seeds (99/1 & 99/1/1)',
                'slug' => 'natural-white-sesame-seeds',
                'hs_code' => '12074090',
                'origin' => 'Gujarat, India',
                'short_description' => 'Premium Gujarat natural white sesame seeds, Sortex-cleaned to 99.9% purity, widely used in tahini, bakery, and confectionery.',
                'description' => "Shiv Aaradhana sources prime-grade natural white sesame seeds directly from Saurashtra farm fields. Carefully cleaned through multi-stage air aspiration, de-stoners, and advanced optical Sortex machines, our sesame seeds boast uniform pearly ivory color, high natural oil yield (>50%), and exceptionally low moisture levels. Packed in food-grade multilayer bags to preserve crispness and natural flavor during sea transit.",
                'primary_image' => '/images/products/sesame-seeds.jpg',
                'status' => 'published',
                'is_featured' => true,
                'harvest_season' => 'October – January',
                'supply_capacity' => '500 Metric Tons / Month',
                'minimum_order_qty' => '1 x 20ft FCL (19 MT approx.)',
                'packaging_options' => '25kg / 50kg PP Bags, Multi-wall Paper Bags with PE liner, or 1000kg Big Bags',
                'published_at' => now(),
                'attrs' => [
                    'purity' => ['num' => 99.9, 'text' => '99.90%'],
                    'moisture' => ['num' => 5.5, 'text' => 'Max 6.0%'],
                    'admixture' => ['num' => 0.1, 'text' => 'Max 0.10%'],
                    'oil_content' => ['num' => 51.0, 'text' => 'Min 50.0%'],
                    'sortex_grading' => ['num' => null, 'text' => '100% Optical Sortex Cleaned'],
                    'crop_season' => ['num' => null, 'text' => 'Current Year Harvest (New Crop)'],
                    'variety' => ['num' => null, 'text' => 'Natural Gujarat Bold White'],
                ],
            ],
            [
                'category_id' => $categories['agro-products']->id,
                'product_type_id' => $types['oilseeds-and-groundnuts']->id,
                'name' => 'Bold & Java Peanuts (HPS Groundnut Kernels)',
                'slug' => 'bold-java-peanuts-hps',
                'hs_code' => '12024210',
                'origin' => 'Rajkot / Saurashtra, Gujarat',
                'short_description' => 'Hand-picked and mechanically sorted bold peanut kernels (counts 38/42, 40/50, 50/60) with guaranteed aflatoxin control.',
                'description' => "Known globally as Gujarat Bold Peanuts, these kernels are grown in the red and sandy-loam soils of Saurashtra. Processed in modern shelling facilities with electronic color-sorting and X-ray foreign matter detection. Our strict storage protocols prevent humidity accumulation, ensuring low aflatoxin levels meeting European and Middle Eastern import regulations.",
                'primary_image' => '/images/products/groundnuts.jpg',
                'status' => 'published',
                'is_featured' => true,
                'harvest_season' => 'November – February',
                'supply_capacity' => '800 Metric Tons / Month',
                'minimum_order_qty' => '1 x 20ft FCL (19 MT in bags)',
                'packaging_options' => '25kg Vacuum Packs, 25kg / 50kg Jute Bags, PP Bags',
                'published_at' => now(),
                'attrs' => [
                    'purity' => ['num' => 99.0, 'text' => '99.00%'],
                    'moisture' => ['num' => 7.0, 'text' => 'Max 7.5%'],
                    'oil_content' => ['num' => 48.0, 'text' => 'Min 48.0%'],
                    'sortex_grading' => ['num' => null, 'text' => 'Sortex & Hand-Picked Selected (HPS)'],
                    'variety' => ['num' => null, 'text' => 'Gujarat Bold (Count 40/50, 50/60)'],
                ],
            ],
            [
                'category_id' => $categories['spices-and-food-products']->id,
                'product_type_id' => $types['whole-spices']->id,
                'name' => 'Premium Cumin Seeds (Jeera) - Sortex Clean',
                'slug' => 'premium-cumin-seeds-jeera',
                'hs_code' => '09093129',
                'origin' => 'Gujarat & Rajasthan Belt, India',
                'short_description' => 'Aromatic, sun-cured cumin seeds with minimum 2.5% volatile oil and 99.5% to 99.9% machine/sortex purity.',
                'description' => "India produces over 70% of the world's cumin, and Gujarat's Unjha and Rajkot mandis represent the global center for quality Jeera. Shiv Aaradhana procures directly during the fresh crop arrivals in March. The seeds undergo rigorous magnetic separation, stone removal, and optical color sorting to eliminate weed seeds and dust, delivering intense aroma and rich culinary character.",
                'primary_image' => '/images/products/cumin-seeds.jpg',
                'status' => 'published',
                'is_featured' => true,
                'harvest_season' => 'February – April',
                'supply_capacity' => '600 Metric Tons / Month',
                'minimum_order_qty' => '1 x 20ft FCL (13-14 MT in bags)',
                'packaging_options' => '25kg / 50kg Eco PP Bags, Paper Bags, Jute Bags',
                'published_at' => now(),
                'attrs' => [
                    'purity' => ['num' => 99.5, 'text' => '99.50% / 99.90% Sortex'],
                    'moisture' => ['num' => 8.0, 'text' => 'Max 8.5%'],
                    'admixture' => ['num' => 0.5, 'text' => 'Max 0.50%'],
                    'oil_content' => ['num' => 2.8, 'text' => 'Volatile Oil Min 2.5%'],
                    'variety' => ['num' => null, 'text' => 'Cuminum cyminum (Gujarat Growth)'],
                ],
            ],
            [
                'category_id' => $categories['spices-and-food-products']->id,
                'product_type_id' => $types['whole-spices']->id,
                'name' => 'Green Fennel Seeds (Saunf / Variyali)',
                'slug' => 'green-fennel-seeds-variyali',
                'hs_code' => '09096139',
                'origin' => 'Gujarat, India',
                'short_description' => 'Bright natural green fennel seeds with sweet liquorice taste, graded for table mouth fresheners and spice extracts.',
                'description' => "Harvested primarily in Gujarat's rich alluvial soil, our fennel seeds (Variyali) are shade-dried immediately after cutting to preserve their distinctive pale-green coloration and sweet anethole volatile oil. Graded meticulously into Medium Bold and Super Fine varieties, popular across confectionery, herbal tea blending, and international seasoning blends.",
                'primary_image' => '/images/products/fennel-seeds.jpg',
                'status' => 'published',
                'is_featured' => false,
                'harvest_season' => 'March – May',
                'supply_capacity' => '300 Metric Tons / Month',
                'minimum_order_qty' => '1 x 20ft FCL (13 MT)',
                'packaging_options' => '25kg PP / Jute Bags with inner liner',
                'published_at' => now(),
                'attrs' => [
                    'purity' => ['num' => 99.0, 'text' => '99.00% Sortex'],
                    'moisture' => ['num' => 9.0, 'text' => 'Max 9.5%'],
                    'variety' => ['num' => null, 'text' => 'Lucknowi & Gujarat Natural Green'],
                ],
            ],
            [
                'category_id' => $categories['spices-and-food-products']->id,
                'product_type_id' => $types['whole-spices']->id,
                'name' => 'Indian Coriander Seeds (Dhania) - Eagle & Scooter',
                'slug' => 'indian-coriander-seeds-dhania',
                'hs_code' => '09092110',
                'origin' => 'Gujarat & MP, India',
                'short_description' => 'Whole round golden-green coriander seeds offering warm citrus aroma, low split percentage, and high cleanliness.',
                'description' => "Our whole coriander seeds are sourced during the peak winter harvest. Available in Eagle, Scooter, and Single Parrot grades according to buyer color and split preferences. Extensively utilized in ground curry powders, pickling spices, and European gin distillation.",
                'primary_image' => '/images/products/coriander-seeds.jpg',
                'status' => 'published',
                'is_featured' => false,
                'harvest_season' => 'January – March',
                'supply_capacity' => '400 Metric Tons / Month',
                'minimum_order_qty' => '1 x 40ft HC (18-20 MT bulky load)',
                'packaging_options' => '20kg / 25kg PP Woven Bags or Jute Bags',
                'published_at' => now(),
                'attrs' => [
                    'purity' => ['num' => 98.5, 'text' => '98.50% - 99.00%'],
                    'moisture' => ['num' => 8.5, 'text' => 'Max 9.0%'],
                    'variety' => ['num' => null, 'text' => 'Eagle / Scooter Whole Round'],
                ],
            ],
            [
                'category_id' => $categories['spices-and-food-products']->id,
                'product_type_id' => $types['ground-and-processed-spices']->id,
                'name' => 'Pure Turmeric Fingers & Powder (Curcumin 3% - 5%)',
                'slug' => 'pure-turmeric-fingers-and-powder',
                'hs_code' => '09103020',
                'origin' => 'India (Nizamabad / Salem / Rajapuri)',
                'short_description' => 'Polished deep yellow turmeric fingers and cool-milled pure turmeric powder tested for heavy metals and curcumin content.',
                'description' => "Carefully boiled, sun-cured, and double-polished turmeric fingers with deep golden interior flesh. Highly valued for natural medicinal curcuminoids, culinary color, and oleoresin extraction. We provide certified laboratory analyses verifying zero artificial color adulteration (Metanil yellow, lead chromate free) and strict pesticide residue conformance.",
                'primary_image' => '/images/products/turmeric.jpg',
                'status' => 'published',
                'is_featured' => true,
                'harvest_season' => 'February – May',
                'supply_capacity' => '500 Metric Tons / Month',
                'minimum_order_qty' => '1 x 20ft FCL (18 MT in fingers, 20 MT in powder)',
                'packaging_options' => '25kg / 50kg Jute / PP Bags with inner poly barrier',
                'published_at' => now(),
                'attrs' => [
                    'purity' => ['num' => 99.0, 'text' => '99.00% Double Polished'],
                    'moisture' => ['num' => 9.5, 'text' => 'Max 10.0%'],
                    'variety' => ['num' => null, 'text' => 'Salem / Nizamabad Fingers (Curcumin > 3.0%)'],
                ],
            ],
            [
                'category_id' => $categories['spices-and-food-products']->id,
                'product_type_id' => $types['whole-spices']->id,
                'name' => 'Sun-Dried Red Chillies (Sanam S4 / Teja Stemless)',
                'slug' => 'sun-dried-red-chillies-sanam-teja',
                'hs_code' => '09042110',
                'origin' => 'Gujarat & Andhra Border Belts, India',
                'short_description' => 'Vibrant red whole dried chillies with spicy pungent heat (25,000 to 75,000 SHU) and high ASTA color value.',
                'description' => "Supplied in stemless or with-stem formats, our red chillies are carefully selected from early picking lots to guarantee bright uniform ruby-red skin, intact seed chambers, and low moisture. Tested for aflatoxin and Sudan dyes to meet European and North American standards.",
                'primary_image' => '/images/products/red-chilli.jpg',
                'status' => 'published',
                'is_featured' => false,
                'harvest_season' => 'January – April',
                'supply_capacity' => '350 Metric Tons / Month',
                'minimum_order_qty' => '1 x 40ft HC (14 MT packed in bales/cartons)',
                'packaging_options' => '10kg / 25kg Jute bags, Corrugated cartons, or compressed bales',
                'published_at' => now(),
                'attrs' => [
                    'moisture' => ['num' => 10.5, 'text' => 'Max 11.0%'],
                    'variety' => ['num' => null, 'text' => 'Sanam S4 / Teja (Stemless available)'],
                ],
            ],
            [
                'category_id' => $categories['textiles-and-fabrics']->id,
                'product_type_id' => $types['raw-cotton-and-fibres']->id,
                'name' => 'Gujarat Shankar-6 Raw Cotton Bales',
                'slug' => 'gujarat-shankar-6-raw-cotton-bales',
                'hs_code' => '52010015',
                'origin' => 'Gujarat, India',
                'short_description' => 'World-renowned Shankar-6 medium-long staple cotton (28mm to 29mm), low trash percentage (<2.5%), and 3.8-4.4 micronaire.',
                'description' => "Gujarat is India's leading cotton producing state. Shiv Aaradhana sources gin-run Shankar-6 cotton directly from certified ginners across Saurashtra. Bales are hydraulically pressed and wrapped in standard 170kg export format. Uniform staple strength and excellent spinning consistency make S-6 the preferred choice for yarn spinning mills in Bangladesh, Vietnam, and Indonesia.",
                'primary_image' => '/images/products/cotton-bales.jpg',
                'status' => 'published',
                'is_featured' => true,
                'harvest_season' => 'October – March',
                'supply_capacity' => '1,200 Metric Tons / Month',
                'minimum_order_qty' => '1 x 40ft HC (approx. 165 bales / 26 MT)',
                'packaging_options' => 'Standard Export Pressed Bales (170kg approx.), fully wrapped with PET/Steel straps',
                'published_at' => now(),
                'attrs' => [
                    'moisture' => ['num' => 7.5, 'text' => 'Max 8.5%'],
                    'staple_count' => ['num' => null, 'text' => 'Staple Length 28.5mm – 29.5mm | Mic 3.8 – 4.4'],
                    'variety' => ['num' => null, 'text' => 'Shankar-6 (S-6) 100% Cotton Lint'],
                ],
            ],
            [
                'category_id' => $categories['textiles-and-fabrics']->id,
                'product_type_id' => $types['cotton-yarns-and-fabrics']->id,
                'name' => '100% Combed Cotton Ring Spun Yarns (Ne 30s & 40s)',
                'slug' => '100-percent-combed-cotton-ring-spun-yarns',
                'hs_code' => '52052200',
                'origin' => 'Gujarat Mills, India',
                'short_description' => 'Export-grade combed ring spun weaving & knitting yarns on paper cones with high tensile strength and low hairiness.',
                'description' => "Produced in modern state-of-the-art ring spinning mills utilizing Gujarat Shankar-6 cotton fibres. Available in counts Ne 24/1, 30/1, 32/1, 40/1 for circular knitting and rapier weaving. Packed on paper cones, UV-inspected, and poly-wrapped inside export cartons or wooden pallet boxes.",
                'primary_image' => '/images/products/cotton-yarn.jpg',
                'status' => 'published',
                'is_featured' => false,
                'harvest_season' => 'Year-Round Production',
                'supply_capacity' => '400 Metric Tons / Month',
                'minimum_order_qty' => '1 x 40ft FCL (20-22 MT)',
                'packaging_options' => 'Paper cones, carton packed (approx. 45.36kg / carton) or palletized',
                'published_at' => now(),
                'attrs' => [
                    'staple_count' => ['num' => null, 'text' => 'Ne 30/1 & Ne 40/1 Combed Ring Spun'],
                    'variety' => ['num' => null, 'text' => '100% Virgin Indian Cotton Fibres'],
                ],
            ],
            [
                'category_id' => $categories['other-export-products']->id,
                'product_type_id' => $types['dehydrated-agro-foods']->id,
                'name' => 'Dehydrated White Onion Flakes & Minced Granules',
                'slug' => 'dehydrated-white-onion-flakes-minced',
                'hs_code' => '07122000',
                'origin' => 'Mahuva / Bhavnagar Belt, Gujarat',
                'short_description' => 'Hygienically dehydrated crisp white onion flakes (kibbled) and minced granules with strong natural pungency and zero additives.',
                'description' => "Gujarat's Mahuva region is the undisputed dehydration capital of India. Fresh white onions are washed, peeled, sliced, and hot-air dehydrated in certified processing units. Free from scorched particles, foreign material, and off-flavors. Excellent rehydration ratio (1:6) used widely in industrial seasonings, canned soups, pizza toppings, and spice mixtures.",
                'primary_image' => '/images/products/dehydrated-onion.jpg',
                'status' => 'published',
                'is_featured' => true,
                'harvest_season' => 'February – June',
                'supply_capacity' => '300 Metric Tons / Month',
                'minimum_order_qty' => '1 x 20ft FCL (7-8 MT in cartons) / 40ft HC (15 MT)',
                'packaging_options' => '14kg / 20kg Poly-lined Multi-wall Kraft Paper Bags / Corrugated Master Cartons',
                'published_at' => now(),
                'attrs' => [
                    'moisture' => ['num' => 5.0, 'text' => 'Max 5.5%'],
                    'purity' => ['num' => 99.5, 'text' => '100% Pure Onion (No Preservatives)'],
                    'variety' => ['num' => null, 'text' => 'White Onion Flakes (Kibbled) & Minced (1-3mm)'],
                ],
            ],
            [
                'category_id' => $categories['other-export-products']->id,
                'product_type_id' => $types['botanical-and-psyllium-products']->id,
                'name' => 'Organic Psyllium Husk 99% (Isabgol USP / EP Grade)',
                'slug' => 'organic-psyllium-husk-99-purity',
                'hs_code' => '12119032',
                'origin' => 'Gujarat / Sidhpur, India',
                'short_description' => 'Pharmaceutical & food-grade psyllium seed husk with 99% purity and over 50 ml/g swelling volume for digestive and baking formulation.',
                'description' => "Milled from the outer husk of Plantago ovata seeds grown in north Gujarat. Processed in GMP-compliant facilities under hygienic clean-room conditions. Our 98% and 99% purity psyllium husk offers exceptional hydrophilic swelling capacity, making it a critical dietary fibre source in pharmaceutical capsules, gluten-free artisan breads, and nutraceutical drinks.",
                'primary_image' => '/images/products/psyllium-husk.jpg',
                'status' => 'published',
                'is_featured' => false,
                'harvest_season' => 'March – May',
                'supply_capacity' => '250 Metric Tons / Month',
                'minimum_order_qty' => '1 x 20ft FCL (9-10 MT palletized)',
                'packaging_options' => '25kg Paper bags with polyethylene liner, or fiber drums',
                'published_at' => now(),
                'attrs' => [
                    'purity' => ['num' => 99.0, 'text' => '99.00% High Swelling (> 50 ml/g)'],
                    'moisture' => ['num' => 9.0, 'text' => 'Max 10.0%'],
                    'variety' => ['num' => null, 'text' => 'Plantago ovata (Isabgol Husk)'],
                ],
            ],
        ];

        $createdProducts = [];
        foreach ($productsData as $pData) {
            $attrsValues = $pData['attrs'] ?? [];
            unset($pData['attrs']);

            $product = Product::updateOrCreate(['slug' => $pData['slug']], $pData);
            $createdProducts[$product->slug] = $product;

            // Set attributes
            foreach ($attrsValues as $code => $val) {
                if (isset($attrDefs[$code])) {
                    ProductAttributeValue::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'attribute_definition_id' => $attrDefs[$code]->id,
                        ],
                        [
                            'text_value' => $val['text'] ?? null,
                            'number_value' => $val['num'] ?? null,
                        ]
                    );
                }
            }
        }

        // 5. Product Recommendations (Deterministic B2B Complementary & Related links)
        $recommendationPairs = [
            // Cumin seeds relates to Fennel, Coriander
            ['source' => 'premium-cumin-seeds-jeera', 'target' => 'green-fennel-seeds-variyali', 'type' => 'complementary', 'pri' => 10],
            ['source' => 'premium-cumin-seeds-jeera', 'target' => 'indian-coriander-seeds-dhania', 'type' => 'related', 'pri' => 9],
            ['source' => 'premium-cumin-seeds-jeera', 'target' => 'pure-turmeric-fingers-and-powder', 'type' => 'complementary', 'pri' => 8],

            // Sesame seeds relates to Peanuts, Cumin
            ['source' => 'natural-white-sesame-seeds', 'target' => 'bold-java-peanuts-hps', 'type' => 'related', 'pri' => 10],
            ['source' => 'natural-white-sesame-seeds', 'target' => 'premium-cumin-seeds-jeera', 'type' => 'curated', 'pri' => 7],

            // Peanuts relates to Sesame
            ['source' => 'bold-java-peanuts-hps', 'target' => 'natural-white-sesame-seeds', 'type' => 'related', 'pri' => 10],

            // Cotton Bales relates to Cotton Yarn
            ['source' => 'gujarat-shankar-6-raw-cotton-bales', 'target' => '100-percent-combed-cotton-ring-spun-yarns', 'type' => 'complementary', 'pri' => 10],
            ['source' => '100-percent-combed-cotton-ring-spun-yarns', 'target' => 'gujarat-shankar-6-raw-cotton-bales', 'type' => 'alternative', 'pri' => 10],

            // Turmeric relates to Red Chilli, Cumin
            ['source' => 'pure-turmeric-fingers-and-powder', 'target' => 'sun-dried-red-chillies-sanam-teja', 'type' => 'complementary', 'pri' => 10],
            ['source' => 'pure-turmeric-fingers-and-powder', 'target' => 'premium-cumin-seeds-jeera', 'type' => 'related', 'pri' => 8],

            // Dehydrated Onion relates to Psyllium Husk
            ['source' => 'dehydrated-white-onion-flakes-minced', 'target' => 'organic-psyllium-husk-99-purity', 'type' => 'related', 'pri' => 9],
        ];

        foreach ($recommendationPairs as $pair) {
            if (isset($createdProducts[$pair['source']]) && isset($createdProducts[$pair['target']])) {
                ProductRecommendation::updateOrCreate(
                    [
                        'source_product_id' => $createdProducts[$pair['source']]->id,
                        'recommended_product_id' => $createdProducts[$pair['target']]->id,
                    ],
                    [
                        'relation_type' => $pair['type'],
                        'priority' => $pair['pri'],
                    ]
                );
            }
        }
    }
}
