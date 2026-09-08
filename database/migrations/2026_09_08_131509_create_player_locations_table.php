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
        Schema::create('player_locations', function (Blueprint $table) {
            $table->char('player_uuid', 36)->primary();
            $table->string('server_name');
            $table->string('world');
            $table->float('x');
            $table->float('y');
            $table->float('z');
            $table->float('yaw');
            $table->float('pitch');
            $table->timestamps();

            $table->foreign('player_uuid')->references('uuid')->on('players')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_locations');
    }
};
