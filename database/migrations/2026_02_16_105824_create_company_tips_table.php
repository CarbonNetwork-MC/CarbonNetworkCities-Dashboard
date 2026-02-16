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
        Schema::create('company_tips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->decimal('amount', 15, 2);
            $table->char('customer_uuid', 36)->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->timestamps();

            /* -------------------------------------------------------------
            * Foreign keys
            * ------------------------------------------------------------- */
            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('customer_uuid')->references('uuid')->on('players')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('employee_id')->references('id')->on('employees')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_tips');
    }
};
