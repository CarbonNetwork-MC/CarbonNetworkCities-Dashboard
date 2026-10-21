<?php

use App\Models\GameClock;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

it('creates the authoritative singleton game clock definition', function () {
    expect(Schema::hasColumns('game_clock', [
        'id', 'real_anchor_at', 'game_anchor_at', 'time_scale',
        'timezone', 'version', 'created_at', 'updated_at',
    ]))->toBeTrue();

    $clock = GameClock::query()->findOrFail(1);

    expect($clock->time_scale)->toBe('1.000000')
        ->and($clock->timezone)->toBe('Europe/Amsterdam')
        ->and($clock->version)->toBe(1)
        ->and($clock->real_anchor_at->toISOString())
        ->toBe($clock->game_anchor_at->toISOString());
});

it('enforces one row for the singleton game clock id', function () {
    expect(fn () => DB::table('game_clock')->insert([
        'id' => 1,
        'real_anchor_at' => now('UTC'),
        'game_anchor_at' => now('UTC'),
        'time_scale' => '2.000000',
        'timezone' => 'UTC',
        'version' => 2,
        'created_at' => now('UTC'),
        'updated_at' => now('UTC'),
    ]))->toThrow(QueryException::class);
});
