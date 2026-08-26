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
        Schema::create('wholesaler_employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wholesaler_id');
            $table->char('player_uuid', 36);
            $table->enum('role', ['employee', 'manager']);
            $table->timestamps();

            /* -------------------------------------------------------------
            * Foreign keys
            * ------------------------------------------------------------- */
            $table->foreign('wholesaler_id')->references('id')->on('wholesalers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('player_uuid')->references('uuid')->on('players')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wholesaler_employees');
    }
};
