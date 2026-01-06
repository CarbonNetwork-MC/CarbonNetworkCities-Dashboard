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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('internal_id', 100)->unique();
            $table->string('name', 100);
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('item_categories')->onDelete('restrict');
            $table->string('material', 50);
            $table->json('data')->nullable();
            $table->char('player_uuid', 36)->nullable()->comment('Used when the item is made via Minecraft');
            $table->foreign('player_uuid')->references('uuid')->on('players')->onDelete('cascade');
            $table->char('user_uuid', 36)->nullable()->comment('Used when the item is made via the website');
            $table->foreign('user_uuid')->references('uuid')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
