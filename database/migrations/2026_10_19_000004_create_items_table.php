<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('internal_id', 100)->unique();
            $table->unsignedBigInteger('category_id');
            $table->string('display_name', 100);
            $table->string('material', 100);
            $table->json('data')->nullable();
            $table->char('created_by_player_uuid', 36)->nullable();
            $table->uuid('created_by_user_uuid')->nullable();
            $table->timestamps();

            $table->foreign('category_id')
                ->references('id')
                ->on('item_categories')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreign('created_by_player_uuid')
                ->references('uuid')
                ->on('players')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreign('created_by_user_uuid')
                ->references('uuid')
                ->on('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
