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
        Schema::create('item_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('icon_material', 50);
            $table->char('player_uuid', 36)->nullable()->comment('Used when the item is made via Minecraft');
            $table->foreign('player_uuid')->references('uuid')->on('players')->onDelete('cascade');
            $table->char('user_uuid', 36)->nullable()->comment('Used when the item is made via the website');
            $table->foreign('user_uuid')->references('uuid')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_categories');
    }
};
