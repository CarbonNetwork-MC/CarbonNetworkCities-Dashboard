<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('player_inventories', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->unsignedSmallInteger('payload_version');
            $table->unsignedBigInteger('revision');
            $table->json('data');
            $table->timestamps();

            $table->foreign('uuid')->references('uuid')->on('players')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_inventories');
    }
};
