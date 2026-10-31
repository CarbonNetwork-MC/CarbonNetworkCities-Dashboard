<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fridge_interaction_anchors', function (Blueprint $table) {
            $table->id();
            $table->uuid('fridge_uuid');
            $table->string('world', 100);
            $table->integer('x');
            $table->integer('y');
            $table->integer('z');

            $table->unique(['world', 'x', 'y', 'z']);
            $table->index('fridge_uuid');
            $table->foreign('fridge_uuid')
                ->references('uuid')
                ->on('fridges')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fridge_interaction_anchors');
    }
};
