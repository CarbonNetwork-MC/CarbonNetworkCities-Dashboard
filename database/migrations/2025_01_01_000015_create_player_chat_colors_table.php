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
        Schema::create('player_chat_colors', function (Blueprint $table) {
            $table->id();
            $table->char('player_uuid', 36);
            $table->foreign('player_uuid')->references('uuid')->on('players')->onDelete('cascade');
            $table->json('level');
            $table->string('level_selected', 50)->default('<white>');
            $table->json('prefix');
            $table->string('prefix_selected', 50)->default('<white>');
            $table->json('chat');
            $table->string('chat_selected', 50)->default('<gray>');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_chat_colors');
    }
};
