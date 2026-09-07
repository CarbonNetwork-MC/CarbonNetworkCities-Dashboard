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
        Schema::create('chat_colors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64);
            $table->string('open_tag', 32);
            $table->string('close_tag', 32);
            $table->boolean('is_bold')->default(false);
            $table->char('hex', 7)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_colors');
    }
};
