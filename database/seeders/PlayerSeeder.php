<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Language;
use App\Models\Player;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    private const array PLAYERS = [
        [
            'uuid' => 'ce3e90f0-96b9-4df5-a58d-68765ef5b1be',
            'username' => 'JoxyYT',
            'level' => 100,
        ],
        [
            'uuid' => '4c173706-e0c5-4cb3-8db7-280640974d04',
            'username' => 'telefooncentrale',
            'level' => 1,
        ]
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command->error('PlayerSeeder should not be run in production environment.');
            return;
        }

        $country = Country::where('name', 'Netherlands')->first();
        $language = Language::where('name', 'English')->first();

        if (!$country || !$language) {
            $this->command->error('Country or Language not found. Please make sure they exist in the database.');
            return;
        }

        foreach (self::PLAYERS as $playerData) {
            Player::query()->updateOrCreate(
                [
                    'uuid' => $playerData['uuid']
                ],
                [
                    'username' => $playerData['username'],
                    'level' => $playerData['level'],
                    'country_id' => $country->id,
                    'language_id' => $language->id,
                ]
            );
        }
    }
}
