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
        Schema::create('wholesale_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->char('customer_uuid', 36)->nullable();
            $table->boolean('collected')->default(false);
            $table->char('collected_by', 36)->nullable();
            $table->boolean('completed')->default(false);
            $table->char('completed_by', 36)->nullable();
            $table->decimal('total', 10, 2);
            $table->timestamps();
            
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('customer_uuid')->references('uuid')->on('players')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('collected_by')->references('uuid')->on('players')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('completed_by')->references('uuid')->on('players')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wholesale_orders');
    }
};
