<?php

namespace Database\Seeders;

use App\Models\CmsSection;
use Illuminate\Database\Seeder;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [
                'section_key' => 'hero',
                'title' => 'From Indian Roots to Global Markets.',
                'subtitle' => 'Shiv Aaradhana Private Limited',
                'content' => 'Discover premium agricultural produce, spices, food products, and textiles sourced with care from India’s agricultural heartlands and prepared to meet rigorous international B2B buyer specifications.',
                'payload' => [
                    'primary_cta_text' => 'Explore Our Products',
                    'primary_cta_link' => '/products',
                    'secondary_cta_text' => 'Request a Quote',
                    'secondary_cta_link' => '/contact',
                    'badge' => '5th-Generation Agricultural Heritage — Rajkot, Gujarat',
                    'stats' => [
                        ['label' => 'Agricultural Heritage', 'value' => '5th Gen'],
                        ['label' => 'Focus Sectors', 'value' => 'Agro, Spices & Textiles'],
                        ['label' => 'Operating Base', 'value' => 'Rajkot, Gujarat'],
                        ['label' => 'Trade Model', 'value' => 'Verified B2B Direct Sourcing'],
                    ],
                ],
            ],
            [
                'section_key' => 'about_heritage',
                'title' => 'Rooted in Gujarat, Serving Global Industry',
                'subtitle' => 'Our Heritage & Story',
                'content' => 'Shiv Aaradhana Private Limited is a family-owned enterprise deeply anchored in Gujarat, India, backed by five generations of farming, land stewardship, and agricultural expertise. Drawing from this enduring legacy, we bridge traditional farming communities with international trade corridors, delivering premium agricultural produce, spices, and textiles under strict quality benchmarks.',
                'payload' => [
                    'origin_region' => 'Saurashtra & Gujarat Agricultural Belt',
                    'core_strengths' => [
                        'Direct farm-gate and mandi procurement across premier Gujarat growing belts',
                        'Meticulous cleaning, grading, sorting, and moisture-controlled warehousing',
                        'Complete export documentation, phytosanitary compliance, and certificate management',
                        'Transparent batch tracking and customizable bulk export packaging options',
                    ],
                ],
            ],
            [
                'section_key' => 'mission_vision',
                'title' => 'Purpose-Driven International Trade',
                'subtitle' => 'Mission & Vision',
                'content' => 'Our mission is to bring India’s agricultural richness to global markets while supporting rural livelihoods, preserving farming traditions, and maintaining high quality standards. Our vision is to become a globally trusted export brand recognized for quality excellence, ethical sourcing, and sustainable growth.',
                'payload' => [
                    'mission' => "Bring India's agricultural richness to global markets while supporting rural livelihoods, preserving farming traditions and maintaining high quality standards.",
                    'vision' => "Become a globally trusted export brand recognized for quality excellence, ethical sourcing and sustainable growth.",
                    'values' => [
                        ['title' => 'Integrity & Transparency', 'desc' => 'Honest batch specifications, verifiable lab test parameters, and clear shipment terms.'],
                        ['title' => 'Agricultural Stewardship', 'desc' => 'Longstanding partnerships with local farming families ensuring fair practices and consistent harvest quality.'],
                        ['title' => 'Global Compliance', 'desc' => 'Rigorous alignment with international phytosanitary, pesticide residue, and export safety criteria.'],
                        ['title' => 'Dependable Fulfillment', 'desc' => 'Disciplined scheduling, secure packaging, and end-to-end logistics coordination from Indian ports.'],
                    ],
                ],
            ],
            [
                'section_key' => 'sourcing_process',
                'title' => 'Disciplined 4-Stage Sourcing & Export Process',
                'subtitle' => 'How We Work with International Buyers',
                'content' => 'From initial farm harvest to port-side container stuffing, each consignment undergoes verified procedural checks designed to give overseas importers full assurance of product specifications, purity, and timely delivery.',
                'payload' => [
                    'steps' => [
                        [
                            'step' => '01',
                            'title' => 'Procurement & Farm-Gate Selection',
                            'desc' => 'Raw lots are carefully inspected and sourced directly from reputable regional growers and APMC mandis across Saurashtra and Gujarat.',
                        ],
                        [
                            'step' => '02',
                            'title' => 'Grading, Cleaning & Laboratory Verification',
                            'desc' => 'Mechanical sortexing, air classification, gravity separation, and lab testing for moisture, purity, volatile oil, and count.',
                        ],
                        [
                            'step' => '03',
                            'title' => 'Customized Export Packaging',
                            'desc' => 'Food-grade PP bags, vacuum packs, multi-wall paper bags, or bulk jumbo totes with custom buyer branding and moisture barrier liners.',
                        ],
                        [
                            'step' => '04',
                            'title' => 'Container Loading & Port Clearance',
                            'desc' => 'Fumigation, pre-shipment inspection, phytosanitary certification, and prompt clearance through Kandla, Mundra, or Pipavav ports.',
                        ],
                    ],
                ],
            ],
            [
                'section_key' => 'contact_verified',
                'title' => 'Connect with Shiv Aaradhana',
                'subtitle' => 'Registered Office & Export Operations',
                'content' => 'We welcome inquiries from commercial importers, distributors, food processors, spice blenders, and textile mills worldwide. Contact our export desk for product specifications, laboratory certificates, and container load quotations.',
                'payload' => [
                    'company_name' => 'Shiv Aaradhana Private Limited',
                    'phone' => '+91 84878 78721',
                    'phone_raw' => '+918487878721',
                    'email' => 'info.shivaaradhana@gmail.com',
                    'address' => '1st Floor, Sahkar Complex, Movaiya Circle, Rajkot-Jamnagar Highway, Taluka Paddhari, District Rajkot, Gujarat 360110, India.',
                    'port_proximity' => 'Mundra Port (240 km), Kandla Port (200 km), Pipavav Port (230 km)',
                    'business_hours' => 'Monday – Saturday: 9:00 AM – 7:00 PM (IST / UTC+5:30)',
                ],
            ],
        ];

        foreach ($sections as $sec) {
            CmsSection::updateOrCreate(
                ['section_key' => $sec['section_key']],
                $sec
            );
        }
    }
}
