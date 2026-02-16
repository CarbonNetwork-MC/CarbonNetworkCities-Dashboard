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
        Schema::create('company_sale_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_sale_id');
            $table->unsignedBigInteger('item_id')->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('price', 15, 2)->default(0);
            $table->timestamps();

            /* -------------------------------------------------------------
            * Foreign keys
            * ------------------------------------------------------------- */
            $table->foreign('company_sale_id')->references('id')->on('company_sales')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('company_items')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_sale_items');
    }
};
