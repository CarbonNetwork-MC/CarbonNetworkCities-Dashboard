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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->char('uuid', 36)->unique();
            $table->string('username', 16);
            $table->integer('level')->default(1);
            $table->unsignedBigInteger('nationality')->nullable();
            $table->boolean('onboarding')->default(false);
            $table->integer('onboarding_step')->default(1);
            $table->unsignedBigInteger('selected_language')->nullable();
            $table->integer('playtime')->default(0);
            $table->timestamp('updated_playtime_at')->default(now())->comment('Used in the playtime command, so players can\'t spam the command.');
            $table->unsignedBigInteger('last_region_id')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->timestamp('last_logout')->nullable();
            $table->timestamp('deletion_pending_at')->nullable();
            $table->timestamps();

            /* -------------------------------------------------------------
            * Foreign keys
            * ------------------------------------------------------------- */
            $table->foreign('nationality')->references('id')->on('countries')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('selected_language')->references('id')->on('languages')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('last_region_id')->references('id')->on('city_regions')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
