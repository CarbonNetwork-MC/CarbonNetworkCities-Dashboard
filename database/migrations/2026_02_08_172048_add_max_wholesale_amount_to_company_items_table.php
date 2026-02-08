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
        Schema::table('company_items', function (Blueprint $table) {
            $table->integer(column: 'max_wholesale_amount')->default(192)->after(column: 'base_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_items', function (Blueprint $table) {
            $table->dropColumn('max_wholesale_amount');
        });
    }
};
