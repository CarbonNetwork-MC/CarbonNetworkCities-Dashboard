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
        Schema::table('plots', function (Blueprint $table) {
            $table->decimal('min_x', 8, 2)->change();
            $table->decimal('min_y', 8, 2)->change();
            $table->decimal('min_z', 8, 2)->change();
            $table->decimal('max_x', 8, 2)->change();
            $table->decimal('max_y', 8, 2)->change();
            $table->decimal('max_z', 8, 2)->change();
            $table->decimal('tp_x', 8, 2)->change();
            $table->decimal('tp_y', 8, 2)->change();
            $table->decimal('tp_z', 8, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plots', function (Blueprint $table) {
            $table->integer('min_x')->change();
            $table->integer('min_y')->change();
            $table->integer('min_z')->change();
            $table->integer('max_x')->change();
            $table->integer('max_y')->change();
            $table->integer('max_z')->change();
            $table->integer('tp_x')->change();
            $table->integer('tp_y')->change();
            $table->integer('tp_z')->change();
        });
    }
};
