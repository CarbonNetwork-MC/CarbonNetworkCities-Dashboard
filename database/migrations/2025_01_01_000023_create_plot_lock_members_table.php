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
        Schema::create('plot_lock_members', function (Blueprint $table) {
            $table->id();
            $table->char('player_uuid', 36);
            $table->timestamps();

            /* -------------------------------------------------------------
            * Foreign keys
            * ------------------------------------------------------------- */
            $table->foreignId('plot_lock_id')->references('id')->on('plot_locks')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('player_uuid')->references('uuid')->on('players')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plot_lock_members');
    }
};
