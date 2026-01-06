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
        Schema::create('player_past_usernames', function (Blueprint $table) {
            $table->id();
            $table->char('player_uuid', 36);
            $table->foreign('player_uuid')->references('uuid')->on('players')->onDelete('cascade');
            $table->string('username', 16);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_past_usernames');
    }
};
