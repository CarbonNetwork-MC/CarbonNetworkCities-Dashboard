<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('onboarding_players', function (Blueprint $table) {
            $table->uuid('player_uuid')->primary();
            $table->string('username', 16);
            $table->string('state', 32)->index();
            $table->unsignedBigInteger('language_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->timestamps();

            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('country_id')->references('id')->on('countries')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('onboarding_players');
    }
};
