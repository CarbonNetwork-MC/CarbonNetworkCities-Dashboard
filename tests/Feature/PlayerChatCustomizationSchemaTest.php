<?php

use App\Models\ChatColor;
use App\Models\Player;
use App\Models\PlayerChatColor;
use App\Models\PlayerPrefix;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

function createChatCustomizationTestPlayer(): Player
{
    return Player::query()->create([
        'uuid' => (string) Str::uuid(),
        'username' => 'ChatTester',
    ]);
}

it('defines the player chat customization columns', function () {
    expect(Schema::hasColumns('player_prefixes', ['id', 'player_uuid', 'prefix', 'selected']))->toBeTrue()
        ->and(Schema::hasColumns('chat_colors', ['id', 'name', 'open_tag', 'close_tag', 'is_bold', 'hex']))->toBeTrue()
        ->and(Schema::hasColumns('player_chat_colors', ['id', 'player_uuid', 'color_id', 'type', 'selected']))->toBeTrue();
});

it('allows multiple prefixes but rejects duplicate ownership', function () {
    $player = createChatCustomizationTestPlayer();
    $player->prefixes()->createMany([
        ['prefix' => 'Tourist', 'selected' => true],
        ['prefix' => 'Citizen', 'selected' => false],
    ]);

    expect($player->prefixes)->toHaveCount(2)
        ->and($player->prefixes->first()->selected)->toBeBool();

    expect(fn () => PlayerPrefix::query()->create([
        'player_uuid' => $player->uuid,
        'prefix' => 'Tourist',
    ]))->toThrow(QueryException::class);
});

it('rejects duplicate color unlocks for one category', function () {
    $player = createChatCustomizationTestPlayer();
    $color = ChatColor::query()->create([
        'name' => 'test_white',
        'open_tag' => '<white>',
        'close_tag' => '</white>',
    ]);
    $player->chatColors()->create([
        'color_id' => $color->id,
        'type' => 'chat',
        'selected' => true,
    ]);

    expect(fn () => PlayerChatColor::query()->create([
        'player_uuid' => $player->uuid,
        'color_id' => $color->id,
        'type' => 'chat',
    ]))->toThrow(QueryException::class)
        ->and($color->playerSelections)->toHaveCount(1);
});

it('cascades customization rows when a player is deleted', function () {
    $player = createChatCustomizationTestPlayer();
    $prefix = $player->prefixes()->create(['prefix' => 'Citizen']);
    $color = ChatColor::query()->create([
        'name' => 'cascade_white',
        'open_tag' => '<white>',
        'close_tag' => '</white>',
    ]);
    $selection = $player->chatColors()->create(['color_id' => $color->id, 'type' => 'level']);

    $player->delete();

    expect(PlayerPrefix::query()->find($prefix->id))->toBeNull()
        ->and(PlayerChatColor::query()->find($selection->id))->toBeNull();
});
