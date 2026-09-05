<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountriesSeeder extends Seeder
{
    private const string FLAG_CODE_PREFIX = '\ue';

    private const array COUNTRIES = [
        [
            'name' => 'Netherlands',
            'iso' => 'NL',
            'flag_code' => self::FLAG_CODE_PREFIX . '230',
            'headdb_id' => 17422
        ],
        [
            'name' => 'Norway',
            'iso' => 'NO',
            'flag_code' => self::FLAG_CODE_PREFIX . '231',
            'headdb_id' => 27587
        ],
        [
            'name' => 'United States of America',
            'iso' => 'US',
            'flag_code' => self::FLAG_CODE_PREFIX . '232',
            'headdb_id' => 890
        ]
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::COUNTRIES as $country) {
            Country::query()->updateOrCreate(
                [
                    'iso' => $country['iso']
                ],
                [
                    'name' => $country['name'],
                    'flag_code' => $country['flag_code'],
                    'headdb_id' => $country['headdb_id']
                ]
            );
        }
    }
}
