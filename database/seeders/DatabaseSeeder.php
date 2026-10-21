<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            GameClockSeeder::class,
            CountriesSeeder::class,
            LanguagesSeeder::class,
            UserSeeder::class,
            ChatColorsSeeder::class,
            PlayerSeeder::class,
            OnboardingStatesSeeder::class,
            ItemCatalogSeeder::class,
        ]);

        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
