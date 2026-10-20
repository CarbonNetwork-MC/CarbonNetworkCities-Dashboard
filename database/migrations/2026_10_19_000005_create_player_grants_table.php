<?php

use App\Enums\PlayerGrantType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('player_grants', function (Blueprint $table) {
            $table->id();
            $table->char('player_uuid', 36);
            $table->enum('grant_type', array_column(PlayerGrantType::cases(), 'value'));
            $table->unsignedBigInteger('item_id')->nullable();
            $table->unsignedInteger('quantity');
            $table->json('data')->nullable();
            $table->string('idempotency_key', 191);
            $table->timestamp('claimed_at')->nullable();
            $table->timestamps();

            $table->foreign('item_id')
                ->references('id')
                ->on('items')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreign('player_uuid')
                ->references('uuid')
                ->on('players')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->unique(
                ['player_uuid', 'idempotency_key'],
                'player_grants_player_idempotency_unique'
            );
            $table->index(['player_uuid', 'claimed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('player_grants');
    }
};
