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
        Schema::create('company_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('item_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('type');
            $table->enum('level', ['critical', 'warning', 'info']);
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            /* -------------------------------------------------------------
            * Foreign keys
            * ------------------------------------------------------------- */
            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('company_items')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('order_id')->references('id')->on('wholesale_orders')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_notifications');
    }
};
