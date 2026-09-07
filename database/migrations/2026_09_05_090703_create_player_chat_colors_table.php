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
            $table->char('player_uuid', 36)->unique();
            $table->unsignedBigInteger('color_id');
            $table->enum('type', ['chat', 'prefix', 'level', 'name']);
            $table->boolean('selected')->default(false);
            $table->timestamps();

            $table->foreign('player_uuid')->references('uuid')->on('players')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('color_id')->references('id')->on('chat_colors')->onUpdate('cascade')->onDelete('cascade');
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
