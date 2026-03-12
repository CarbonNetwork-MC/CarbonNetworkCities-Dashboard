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
        Schema::create('employee_salary_updates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('salary_id');
            $table->char('player_uuid', 36);
            $table->unsignedBigInteger('sale_id')->nullable();
            $table->unsignedBigInteger('tip_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->timestamps();

            /* -------------------------------------------------------------
            * Foreign keys
            * ------------------------------------------------------------- */
            $table->foreign('salary_id')->references('id')->on('employee_salaries')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('player_uuid')->references('uuid')->on('players')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sale_id')->references('id')->on('company_sales')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('tip_id')->references('id')->on('company_tips')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_salary_updates');
    }
};
