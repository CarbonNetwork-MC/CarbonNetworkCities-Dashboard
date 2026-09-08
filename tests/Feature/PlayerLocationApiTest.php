<?php

use App\Models\Player;
use App\Models\PlayerLocation;
use App\Models\User;
use Illuminate\Support\Str;

it('rejects unauthenticated player location requests', function () {
    $this->getJson('/api/player-location/'.Str::uuid())
        ->assertUnauthorized();
});

it('rejects tokens without the player location read ability', function () {
    $user = User::factory()->create(['uuid' => Str::uuid()]);
    $token = $user->createToken('test-server', ['other:read'])->plainTextToken;

    $this->withToken($token)
        ->getJson('/api/player-location/'.Str::uuid())
        ->assertForbidden();
});

it('returns a player location to an authorized server', function () {
    $uuid = (string) Str::uuid();
    $user = User::factory()->create(['uuid' => Str::uuid()]);
    $token = $user->createToken('test-server', ['player-location:read'])->plainTextToken;

    Player::query()->create([
        'uuid' => $uuid,
        'username' => 'TestPlayer',
    ]);

    PlayerLocation::query()->create([
        'player_uuid' => $uuid,
        'server_name' => 'cities-1',
        'world' => 'world',
        'x' => 1,
        'y' => 64,
        'z' => 1,
        'yaw' => 0,
        'pitch' => 0,
    ]);

    $this->withToken($token)
        ->getJson('/api/player-location/'.$uuid)
        ->assertOk()
        ->assertExactJson(['server_name' => 'cities-1']);
});

it('returns not found when an authorized server requests an unknown player', function () {
    $user = User::factory()->create(['uuid' => Str::uuid()]);
    $token = $user->createToken('test-server', ['player-location:read'])->plainTextToken;

    $this->withToken($token)
        ->getJson('/api/player-location/'.Str::uuid())
        ->assertNotFound()
        ->assertExactJson(['message' => 'Player location not found']);
});
