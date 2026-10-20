<?php

use App\Enums\BankTransactionType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('personal_bank_account_id');
            $table->enum('type', array_column(BankTransactionType::cases(), 'value'));
            $table->decimal('amount', 19, 2)->unsigned();
            $table->decimal('balance_after', 19, 2)->unsigned();
            $table->string('idempotency_key', 191);
            $table->string('description')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();

            $table->foreign('personal_bank_account_id')
                ->references('id')
                ->on('personal_bank_accounts')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->unique(
                ['personal_bank_account_id', 'idempotency_key'],
                'bank_transactions_account_idempotency_unique'
            );
            $table->index(['personal_bank_account_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_transactions');
    }
};
