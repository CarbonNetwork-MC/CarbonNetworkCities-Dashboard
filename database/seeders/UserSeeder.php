<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private const array USERS = [
        [
            'name' => 'admin',
            'email' => 'admin@carbonnetwork.dev',
            'password' => 'test',
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

            // Create a personal access token for the user
            // $token = $user->createToken('minecraft-server', ['player-location:read'])->plainTextToken;
            // $this->command->info("User '{$user->name}' created with token '{$token}'.");
        }
    }
}
