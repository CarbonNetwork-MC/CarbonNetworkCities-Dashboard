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
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('from_company_id')->nullable();
            $table->unsignedBigInteger('to_company_id')->nullable();
            $table->string('from_company_name')->nullable();
            $table->string('to_company_name')->nullable();

            $table->unsignedBigInteger('from_personal_id')->nullable();
            $table->unsignedBigInteger('to_personal_id')->nullable();
            $table->string('from_player_name')->nullable();
            $table->string('to_player_name')->nullable();

            $table->decimal('amount', 15, 2);
            $table->string('currency', 3);
            $table->text('description')->nullable();

            $table->enum('transaction_type', [
                'withdraw',
                'deposit',
                'personal_to_company',
                'company_to_personal',
                'company_to_company',
                'personal_to_personal'
            ]);

            $table->timestamps();

            /* -------------------------------------------------------------
            * Foreign keys
            * ------------------------------------------------------------- */
            $table->foreign('from_company_id')->references('id')->on('company_bankaccounts')->onDelete('set null');
            $table->foreign('to_company_id')->references('id')->on('company_bankaccounts')->onDelete('set null');
            $table->foreign('from_personal_id')->references('id')->on('personal_bankaccounts')->onDelete('set null');
            $table->foreign('to_personal_id')->references('id')->on('personal_bankaccounts')->onDelete('set null');

            /* -------------------------------------------------------------
            * Indexes for transaction lookup
            * ------------------------------------------------------------- */
            $table->index(['from_personal_id', 'created_at'], 'idx_tx_from_personal_created');
            $table->index(['to_personal_id', 'created_at'], 'idx_tx_to_personal_created');

            $table->index(['from_company_id', 'created_at'], 'idx_tx_from_company_created');
            $table->index(['to_company_id', 'created_at'], 'idx_tx_to_company_created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_transactions');
    }
};
