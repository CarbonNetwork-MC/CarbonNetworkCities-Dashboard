<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class GameClockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('game_clock')->insert([
            'id' => 1,
            'real_anchor_at' => '2000-01-01 00:00:00.000000',
            'game_anchor_at' => '2000-01-01 00:00:00.000000',
            'time_scale' => '1.000000',
            'timezone' => 'Europe/Amsterdam',
            'version' => 1,
            'created_at' => now('UTC'),
            'updated_at' => now('UTC'),
        ]);
    }
}
