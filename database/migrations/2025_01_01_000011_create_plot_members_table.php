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
        Schema::create('plot_members', function (Blueprint $table) {
            $table->id();
            $table->string('plot_id', 32);
            $table->char('player_uuid', 36);
            $table->string('username', 16);
            $table->enum('role', ['owner', 'admin', 'member'])->default('member');
            $table->timestamps();

            /* -------------------------------------------------------------
            * Foreign keys
            * ------------------------------------------------------------- */
            $table->foreign('plot_id')->references('plot_id')->on('plots')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('player_uuid')->references('uuid')->on('players')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plot_members');
    }
};
