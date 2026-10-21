<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->boolean('enabled')->after('internal_id')->default(true)->index();
            $table->unsignedSmallInteger('definition_version')->after('id')->default(1);
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropIndex(['enabled']);
            $table->dropColumn(['enabled', 'definition_version']);
        });
    }
};
