<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\ItemCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $utilityCategory = ItemCategory::query()->updateOrCreate(
            ['internal_key' => 'utility'],
            ['display_name' => 'Utility', 'icon_material' => 'PAPER']
        );
        $foodCategory = ItemCategory::query()->updateOrCreate(
            ['internal_key' => 'food'],
            ['display_name' => 'Food', 'icon_material' => 'COOKED_BEEF']
        );

        Item::query()->updateOrCreate(
            ['internal_id' => 'carbonnetwork:credit_card'],
            [
                'definition_version' => 1,
                'enabled' => true,
                'category_id' => $utilityCategory->getKey(),
                'display_name' => '<yellow>Credit Card',
                'material' => 'PAPER',
                'data' => [
                    'account_reference' => 'personal_bank_account_id',
                ],
            ]
        );
        $spoiledFood = Item::query()->updateOrCreate(
            ['internal_id' => 'carbonnetwork:spoiled_food'],
            [
                'definition_version' => 1,
                'enabled' => true,
                'category_id' => $foodCategory->getKey(),
                'display_name' => '<red><bold>Spoiled Food',
                'material' => 'ROTTEN_FLESH',
                'data' => [
                    'lore' => ['<red>This food has spoiled.'],
                ],
            ]
        );
        Item::query()->updateOrCreate(
            ['internal_id' => 'carbonnetwork:starter_food'],
            [
                'definition_version' => 1,
                'enabled' => true,
                'category_id' => $foodCategory->getKey(),
                'display_name' => '<green>Starter Food',
                'material' => 'COOKED_BEEF',
                'data' => [
                    'food' => [
                        'shelf_life_seconds' => 86400,
                        'normal_decay_rate' => 1.0,
                        'refrigerated_decay_rate' => 0.25,
                        'frozen_decay_rate' => 0.05,
                        'spoiled_item_definition_id' => $spoiledFood->getKey(),
                    ],
                ],
            ]
        );
        Item::query()->updateOrCreate(
            ['internal_id' => 'carbonnetwork:test_food'],
            [
                'definition_version' => 1,
                'enabled' => true,
                'category_id' => $foodCategory->getKey(),
                'display_name' => '<green>Test Food',
                'material' => 'GOLDEN_APPLE',
                'data' => [
                    'food' => [
                        'shelf_life_seconds' => 30,
                        'normal_decay_rate' => 1.0,
                        'refrigerated_decay_rate' => 0.25,
                        'frozen_decay_rate' => 0.05,
                        'spoiled_item_definition_id' => $spoiledFood->getKey(),
                    ],
                ],
            ]
        );
    }
}
