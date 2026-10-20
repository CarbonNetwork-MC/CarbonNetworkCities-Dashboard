<?php

use App\Enums\BankAccountType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personal_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->char('player_uuid', 36);
            $table->enum('type', array_column(BankAccountType::cases(), 'value'));
            $table->char('currency', 3);
            $table->decimal('balance', 19, 2)->unsigned()->default(0);
            $table->timestamps();

            $table->foreign('player_uuid')
                ->references('uuid')
                ->on('players')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->index(['player_uuid', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_bank_accounts');
    }
};
