<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguagesSeeder extends Seeder
{
    private const array LANGUAGES = [
        [
            'name' => 'English',
            'iso' => 'en',
            'locale' => 'en_US',
            'headdb_id' => 890,
        ],
        [
            'name' => 'Dutch',
            'iso' => 'nl',
            'locale' => 'nl_NL',
            'headdb_id' => 17422,
        ]
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::LANGUAGES as $language) {
            Language::query()->updateOrCreate(
                [
                    'iso' => $language['iso']
                ],
                [
                    'name' => $language['name'],
                    'locale' => $language['locale'],
                    'headdb_id' => $language['headdb_id']
                ]
            );
        }
    }
}
