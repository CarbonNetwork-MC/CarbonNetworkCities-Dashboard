<?php

use App\Enums\FridgeApplianceType;
use App\Enums\FridgeCompartment;
use App\Models\Fridge;
use App\Models\Player;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

it('creates the cold storage tables and required columns', function () {
    expect(Schema::hasColumns('fridges', [
        'uuid', 'appliance_type', 'refrigerator_rows', 'freezer_rows',
        'world', 'x', 'y', 'z', 'owner_player_uuid',
        'owner_plot_id', 'inventory_version', 'revision', 'created_at', 'updated_at',
    ]))->toBeTrue()
        ->and(Schema::hasColumns('fridge_items', [
            'id', 'fridge_uuid', 'compartment', 'slot', 'item_data',
        ]))->toBeTrue()
        ->and(Schema::hasColumns('fridge_interaction_anchors', [
            'id', 'fridge_uuid', 'world', 'x', 'y', 'z',
        ]))->toBeTrue();
});

it('stores both compartments for a combined appliance', function () {
    $player = Player::query()->create([
        'uuid' => (string) Str::uuid(),
        'username' => 'ColdStorageOwner',
    ]);
    $fridge = Fridge::query()->create([
        'uuid' => (string) Str::uuid(),
        'appliance_type' => FridgeApplianceType::Combined,
        'refrigerator_rows' => 5,
        'freezer_rows' => 2,
        'world' => 'world',
        'x' => 10,
        'y' => 64,
        'z' => 20,
        'owner_player_uuid' => $player->uuid,
    ]);

    $fridge->items()->createMany([
        ['compartment' => FridgeCompartment::Refrigerator, 'slot' => 0, 'item_data' => 'first'],
        ['compartment' => FridgeCompartment::Freezer, 'slot' => 0, 'item_data' => 'second'],
    ]);
    $fridge->interactionAnchors()->create([
        'world' => 'world', 'x' => 11, 'y' => 64, 'z' => 20,
    ]);

    expect($fridge->fresh()->appliance_type)->toBe(FridgeApplianceType::Combined)
        ->and($fridge->fresh()->refrigerator_rows)->toBe(5)
        ->and($fridge->fresh()->freezer_rows)->toBe(2)
        ->and($fridge->items()->count())->toBe(2)
        ->and($fridge->interactionAnchors()->count())->toBe(1)
        ->and($player->fridges()->firstOrFail()->is($fridge))->toBeTrue();

    expect(fn () => $fridge->items()->create([
        'compartment' => FridgeCompartment::Refrigerator,
        'slot' => 0,
        'item_data' => 'duplicate',
    ]))->toThrow(QueryException::class);
});
