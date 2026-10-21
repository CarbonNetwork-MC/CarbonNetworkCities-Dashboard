<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_clock', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->primary();
            $table->dateTime('real_anchor_at', precision: 6);
            $table->dateTime('game_anchor_at', precision: 6);
            $table->decimal('time_scale', 12, 6);
            $table->string('timezone', 63);
            $table->unsignedBigInteger('version');
            $table->timestamps(precision: 6);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_clock');
    }
};
