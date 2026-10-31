<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fridge_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('fridge_uuid');
            $table->enum('compartment', ['refrigerator', 'freezer']);
            $table->unsignedTinyInteger('slot');
            $table->mediumText('item_data');

            $table->unique(['fridge_uuid', 'compartment', 'slot']);
            $table->foreign('fridge_uuid')
                ->references('uuid')
                ->on('fridges')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fridge_items');
    }
};
