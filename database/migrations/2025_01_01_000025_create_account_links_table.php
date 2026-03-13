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
        Schema::create('account_links', function (Blueprint $table) {
            $table->id();
            $table->char('player_uuid', 36);
            $table->char('user_uuid', 36);
            $table->boolean('is_linked')->default(false);
            $table->timestamp('linked_at')->nullable();
            $table->timestamps();

            /* -------------------------------------------------------------
            * Foreign keys
            * ------------------------------------------------------------- */
            $table->foreign('player_uuid')->references('uuid')->on('players')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_uuid')->references('uuid')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_links');
    }
};
