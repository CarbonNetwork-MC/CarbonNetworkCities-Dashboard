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
        Schema::create('tickets', function (Blueprint $table) {
            $table->char('ticket_uuid', 36)->primary();
            $table->char('player_uuid', 36)->nullable();
            $table->integer('slot');
            $table->string('destination', 64);
            $table->timestamp('departure_time');
            $table->timestamps();
            
            /* -------------------------------------------------------------
            * Foreign keys
            * ------------------------------------------------------------- */
            $table->foreign('player_uuid')->references('uuid')->on('players')->onUpdate('cascade')->onDelete('set null');

            /* -------------------------------------------------------------
            * Indexes
            * ------------------------------------------------------------- */
            $table->index('player_uuid');
            $table->index('departure_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
