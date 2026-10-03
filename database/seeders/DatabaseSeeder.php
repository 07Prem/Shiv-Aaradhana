<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin user
        User::updateOrCreate(
            ['email' => 'admin@shivaaradhana.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('ShivAaradhana@2026!'),
                'role' => User::ROLE_SUPER_ADMIN,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create Catalog Manager user
        User::updateOrCreate(
            ['email' => 'catalog@shivaaradhana.com'],
            [
                'name' => 'Catalog Manager',
                'password' => Hash::make('ShivAaradhana@2026!'),
                'role' => User::ROLE_CATALOG_MANAGER,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create Inquiry Manager user
        User::updateOrCreate(
            ['email' => 'inquiry@shivaaradhana.com'],
            [
                'name' => 'Inquiry Desk Officer',
                'password' => Hash::make('ShivAaradhana@2026!'),
                'role' => User::ROLE_INQUIRY_MANAGER,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Run CMS Seeder
        $this->call(CmsSeeder::class);

        // Run Catalog Seeder
        $this->call(CatalogSeeder::class);
    }
}
