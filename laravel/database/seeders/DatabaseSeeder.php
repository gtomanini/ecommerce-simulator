<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Idempotent: if the catalog is already seeded (categories exist),
     * it returns early. This makes it safe to run on every production
     * boot without creating duplicate data.
     */
    public function run(): void
    {
        // Admin user for the Filament panel (/admin). Ensured on every run,
        // even after the catalog is already seeded.
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => Hash::make('password'), 'is_admin' => true]
        );

        if (Category::exists()) {
            return;
        }

        // Plain create (no factory) so seeding works in production where
        // Faker is a dev-only dependency and is not installed.
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => Hash::make('password')]
        );

        $this->call([
            CategorySeeder::class,
            ShippingMethodSeeder::class,
            AchievementSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
