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
        Schema::table('company_stock', function (Blueprint $table) {
            $table->integer('preferred_stock_level')->default(100)->after('quantity');
            $table->integer('warning_threshold')->default(50)->after('preferred_stock_level');
            $table->integer('critical_threshold')->default(20)->after('warning_threshold');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_stock', function (Blueprint $table) {
            $table->dropColumn('preferred_stock_level');
            $table->dropColumn('warning_threshold');
            $table->dropColumn('critical_threshold');
        });
    }
};
