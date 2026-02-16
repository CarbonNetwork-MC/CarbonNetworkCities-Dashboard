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
        Schema::create('company_sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('item_id')->nullable();
            $table->integer('year');
            $table->integer('week');
            $table->integer('quantity')->default(0);
            $table->decimal('total_revenue', 15, 2)->default(0);
            $table->char('customer_uuid', 36)->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->timestamps();

            /* -------------------------------------------------------------
            * Foreign keys
            * ------------------------------------------------------------- */
            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('company_items')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('customer_uuid')->references('uuid')->on('players')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('employee_id')->references('id')->on('employees')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_sales');
    }
};
