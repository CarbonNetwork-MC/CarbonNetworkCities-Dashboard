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
        Schema::create('fridges', function (Blueprint $table) {
            $table->id();
            $table->string('world_id', 50);
            $table->string('plot_id', 32);
            $table->enum('type', ['single', 'double']);
            $table->integer('min_x');
            $table->integer('min_y');
            $table->integer('min_z');
            $table->integer('max_x');
            $table->integer('max_y');
            $table->integer('max_z');
            $table->timestamps();

            /* -------------------------------------------------------------
            * Foreign keys
            * ------------------------------------------------------------- */
            $table->foreign('plot_id')->references('plot_id')->on('plots')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fridges');
    }
};
