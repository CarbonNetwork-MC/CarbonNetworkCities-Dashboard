<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fridges', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->enum('appliance_type', ['refrigerator', 'freezer', 'combined']);
            $table->unsignedTinyInteger('refrigerator_rows')->default(6);
            $table->unsignedTinyInteger('freezer_rows')->default(3);
            $table->string('world', 100);
            $table->integer('x');
            $table->integer('y');
            $table->integer('z');
            $table->uuid('owner_player_uuid')->nullable();
            $table->unsignedBigInteger('owner_plot_id')->nullable();
            $table->unsignedSmallInteger('inventory_version')->default(1);
            $table->unsignedBigInteger('revision')->default(1);
            $table->timestamps();

            $table->unique(['world', 'x', 'y', 'z']);
            $table->index('owner_plot_id');
            $table->foreign('owner_player_uuid')
                ->references('uuid')
                ->on('players')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fridges');
    }
};
