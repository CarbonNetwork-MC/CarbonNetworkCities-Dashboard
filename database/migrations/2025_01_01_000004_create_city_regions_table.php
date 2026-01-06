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
        Schema::create('city_regions', function (Blueprint $table) {
            $table->id();
            $table->string('internal_name')->unique();
            $table->string('display_name');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('city', 100)->nullable();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null');
            $table->string('world_id');
            $table->integer('min_x');
            $table->integer('min_y');
            $table->integer('min_z');
            $table->integer('max_x');
            $table->integer('max_y');
            $table->integer('max_z');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('city_regions');
    }
};
