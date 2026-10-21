<?php

use App\Enums\BankAccountType;
use App\Enums\BankTransactionType;
use App\Enums\PlayerGrantType;
use App\Models\BankTransaction;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\Player;
use App\Models\PlayerGrant;
use Database\Seeders\ItemCatalogSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

function createSchemaTestPlayer(): Player
{
    return Player::query()->create([
        'uuid' => (string) Str::uuid(),
        'username' => 'SchemaTester',
    ]);
}

it('creates the C5 tables and required columns', function () {
    expect(Schema::hasColumns('personal_bank_accounts', [
        'id', 'player_uuid', 'type', 'currency', 'balance', 'created_at', 'updated_at',
    ]))->toBeTrue()
        ->and(Schema::hasColumns('bank_transactions', [
            'id', 'personal_bank_account_id', 'type', 'amount', 'balance_after',
            'idempotency_key', 'description', 'data', 'created_at', 'updated_at',
        ]))->toBeTrue()
        ->and(Schema::hasColumns('item_categories', [
            'id', 'internal_key', 'display_name', 'icon_material',
            'created_by_player_uuid', 'created_by_user_uuid', 'created_at', 'updated_at',
        ]))->toBeTrue()
        ->and(Schema::hasColumns('items', [
            'id', 'internal_id', 'category_id', 'display_name', 'material',
            'enabled', 'definition_version', 'data',
            'created_by_player_uuid', 'created_by_user_uuid', 'created_at', 'updated_at',
        ]))->toBeTrue()
        ->and(Schema::hasColumns('player_grants', [
            'id', 'player_uuid', 'grant_type', 'item_id', 'quantity', 'data',
            'idempotency_key', 'claimed_at', 'created_at', 'updated_at',
        ]))->toBeTrue();
});

it('allows multiple checking and savings accounts for one player', function () {
    $player = createSchemaTestPlayer();

    foreach ([BankAccountType::Checking, BankAccountType::Checking, BankAccountType::Savings] as $type) {
        $player->personalBankAccounts()->create([
            'type' => $type,
            'currency' => 'EUR',
            'balance' => '0.00',
        ]);
    }

    expect($player->personalBankAccounts()->count())->toBe(3)
        ->and($player->personalBankAccounts()->where('type', 'checking')->count())->toBe(2);
});

it('stores an idempotent opening balance ledger entry', function () {
    $account = createSchemaTestPlayer()->personalBankAccounts()->create([
        'type' => BankAccountType::Checking,
        'currency' => 'EUR',
        'balance' => '5000.00',
    ]);

    $transaction = $account->transactions()->create([
        'type' => BankTransactionType::OpeningBalance,
        'amount' => '5000.00',
        'balance_after' => '5000.00',
        'idempotency_key' => 'onboarding:opening_balance:v1',
    ]);

    expect($transaction->type)->toBe(BankTransactionType::OpeningBalance)
        ->and($transaction->amount)->toBe('5000.00')
        ->and($transaction->personalBankAccount->is($account))->toBeTrue();

    expect(fn () => BankTransaction::query()->create([
        'personal_bank_account_id' => $account->getKey(),
        'type' => BankTransactionType::OpeningBalance,
        'amount' => '5000.00',
        'balance_after' => '5000.00',
        'idempotency_key' => 'onboarding:opening_balance:v1',
    ]))->toThrow(QueryException::class);
});

it('seeds immutable starter item definitions with non-expiring food', function () {
    $this->seed(ItemCatalogSeeder::class);

    $food = Item::query()->where('internal_id', 'carbonnetwork:starter_food')->firstOrFail();
    $creditCard = Item::query()->where('internal_id', 'carbonnetwork:credit_card')->firstOrFail();

    expect($food->material)->toBe('COOKED_BEEF')
        ->and($food->enabled)->toBeTrue()
        ->and($food->definition_version)->toBe(1)
        ->and($food->data)->toMatchArray([
            'food' => ['expiration_enabled' => false],
        ])
        ->and($creditCard->data)->toMatchArray([
            'account_reference' => 'personal_bank_account_id',
        ])
        ->and($creditCard->enabled)->toBeTrue()
        ->and($creditCard->definition_version)->toBe(1);

    $food->internal_id = 'carbonnetwork:renamed_food';
    expect(fn () => $food->save())->toThrow(LogicException::class);

    $category = ItemCategory::query()->where('internal_key', 'food')->firstOrFail();
    $category->internal_key = 'renamed_food';
    expect(fn () => $category->save())->toThrow(LogicException::class);
});

it('allows repeat item grants with distinct idempotency keys but rejects duplicate operations', function () {
    $this->seed(ItemCatalogSeeder::class);
    $player = createSchemaTestPlayer();
    $food = Item::query()->where('internal_id', 'carbonnetwork:starter_food')->firstOrFail();

    foreach (['onboarding:starter_food:v1', 'event:starter_food:v1'] as $idempotencyKey) {
        $player->grants()->create([
            'grant_type' => PlayerGrantType::Item,
            'item_id' => $food->getKey(),
            'quantity' => 32,
            'idempotency_key' => $idempotencyKey,
        ]);
    }

    expect(PlayerGrant::query()->whereBelongsTo($player)->count())->toBe(2)
        ->and($food->playerGrants()->count())->toBe(2);

    expect(fn () => PlayerGrant::query()->create([
        'player_uuid' => $player->uuid,
        'grant_type' => PlayerGrantType::Item,
        'item_id' => $food->getKey(),
        'quantity' => 1,
        'idempotency_key' => 'onboarding:starter_food:v1',
    ]))->toThrow(QueryException::class);
});

it('defines the approved starter account and grants', function () {
    expect(config('cities.starter.account'))->toMatchArray([
        'type' => 'checking',
        'currency' => 'EUR',
        'starting_balance' => '5000.00',
    ])->and(config('cities.starter.grants.credit_card'))->toMatchArray([
        'grant_type' => 'item',
        'item_internal_id' => 'carbonnetwork:credit_card',
        'quantity' => 1,
    ])->and(config('cities.starter.grants.food'))->toMatchArray([
        'grant_type' => 'item',
        'item_internal_id' => 'carbonnetwork:starter_food',
        'quantity' => 32,
    ]);
});
