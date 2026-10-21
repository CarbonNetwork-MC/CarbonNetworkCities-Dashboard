<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\ItemCategory;
use Illuminate\Database\Seeder;

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
                'display_name' => 'Credit Card',
                'material' => 'PAPER',
                'data' => [
                    'account_reference' => 'personal_bank_account_id',
                ],
            ]
        );
        Item::query()->updateOrCreate(
            ['internal_id' => 'carbonnetwork:starter_food'],
            [
                'definition_version' => 1,
                'enabled' => true,
                'category_id' => $foodCategory->getKey(),
                'display_name' => 'Starter Food',
                'material' => 'COOKED_BEEF',
                'data' => [
                    'food' => [
                        'expiration_enabled' => false,
                    ],
                ],
            ]
        );
    }
}
