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
        Schema::table('wholesale_items', function (Blueprint $table) {
            $table->unsignedBigInteger('wholesaler_id')->after('id')->nullable();

            /* -------------------------------------------------------------
            * Foreign keys
            * ------------------------------------------------------------- */
            $table->foreign('wholesaler_id')->references('id')->on('wholesalers')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wholesale_items', function (Blueprint $table) {
            $table->dropForeign(['wholesaler_id']);
            $table->dropColumn('wholesaler_id');
        });
    }
};
