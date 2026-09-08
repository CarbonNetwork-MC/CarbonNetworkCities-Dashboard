<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private const array USERS = [
        [
            'name' => 'Cities Gamemode',
            'email' => 'cities@carbonnetwork.dev',
            'password' => 'CZ!bXbrkN0A8YCUkTv4WESAf#pfv0xB#MMz*6Yfx#z3*Uv&dexHkvnQN8R4sXM8NjA*j86m689DdYRnhCmcf5g3m#EZGFhz@?Dr@Fr#MFQX0v4YbZamS0!vxU?P#n7vR',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command->error('UserSeeder should not be run in production environment.');
            return;
        }

        foreach (self::USERS as $userData) {
            $user = User::query()->updateOrCreate(
                [
                    'email' => $userData['email']
                ],
                [
                    'uuid' => \Str::uuid(),
                    'name' => $userData['name'],
                    'password' => bcrypt($userData['password']),
                ]
            );

            $token = $user->createToken('minecraft-server', ['player-location:read'])->plainTextToken;

            $this->command->info("User '{$user->name}' created with token '{$token}'.");
        }
    }
}
