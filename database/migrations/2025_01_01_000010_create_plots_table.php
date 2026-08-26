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
        Schema::create('plots', function (Blueprint $table) {
            $table->id();
            $table->string('plot_id', 32)->unique();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->string('city', 50);
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('world_id', 50);
            $table->unsignedBigInteger('company_id')->nullable();
            $table->char('owner_uuid', 36)->nullable();
            $table->integer('min_x');
            $table->integer('min_y');
            $table->integer('min_z');
            $table->integer('max_x');
            $table->integer('max_y');
            $table->integer('max_z');
            $table->boolean('for_sale')->default(false);
            $table->integer('price')->nullable();
            $table->enum('type', ['house', 'store', 'farm', 'factory', 'office', 'other']);
            $table->integer('tp_x')->nullable();
            $table->integer('tp_y')->nullable();
            $table->integer('tp_z')->nullable();
            $table->float('tp_yaw')->nullable();
            $table->float('tp_pitch')->nullable();
            $table->timestamps();

            /* -------------------------------------------------------------
            * Foreign keys
            * ------------------------------------------------------------- */
            $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('owner_uuid')->references('uuid')->on('players')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plots');
    }
};
