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
        Schema::create('item_groups', function (Blueprint $table) {
            $table->id();
            $table->string('coc_type', 8);
            $table->unsignedBigInteger('item_id');
            $table->boolean('sellable')->default(true);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('coc_type')->references('name')->on('coc_types')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_groups');
    }
};
