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
        Schema::create('account_link_tokens', function (Blueprint $table) {
            $table->id();
            $table->char('player_uuid', 36);
            $table->foreign('player_uuid')->references('uuid')->on('players')->onDelete('cascade');
            $table->char('user_uuid', 36);
            $table->foreign('user_uuid')->references('uuid')->on('users')->onDelete('cascade');
            $table->string('token', 64)->unique();
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_hashes');
    }
};
