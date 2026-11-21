<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->consolidateChatColors();
        $this->removeDuplicateRows('player_prefixes', fn ($row) => $row->player_uuid."\0".$row->prefix);
        $this->removeDuplicateRows('player_chat_colors', fn ($row) => $row->player_uuid."\0".$row->color_id."\0".$row->type);
        $this->keepOneSelectedPerGroup('player_prefixes', fn ($row) => $row->player_uuid);
        $this->keepOneSelectedPerGroup('player_chat_colors', fn ($row) => $row->player_uuid."\0".$row->type);

        Schema::table('player_prefixes', function (Blueprint $table) {
            $table->unique(['player_uuid', 'prefix']);
            $table->index(['player_uuid', 'selected']);
        });
        Schema::table('player_prefixes', function (Blueprint $table) {
            $table->dropUnique(['player_uuid']);
        });
        Schema::table('chat_colors', function (Blueprint $table) {
            $table->unique('name');
        });
        Schema::table('player_chat_colors', function (Blueprint $table) {
            $table->unique(['player_uuid', 'color_id', 'type']);
            $table->index(['player_uuid', 'type', 'selected']);
        });
    }

    public function down(): void
    {
        Schema::table('player_chat_colors', function (Blueprint $table) {
            $table->dropIndex(['player_uuid', 'type', 'selected']);
            $table->dropUnique(['player_uuid', 'color_id', 'type']);
        });
        Schema::table('chat_colors', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });
        $this->removeDuplicateRows('player_prefixes', fn ($row) => $row->player_uuid);
        Schema::table('player_prefixes', function (Blueprint $table) {
            $table->unique('player_uuid');
        });
        Schema::table('player_prefixes', function (Blueprint $table) {
            $table->dropIndex(['player_uuid', 'selected']);
            $table->dropUnique(['player_uuid', 'prefix']);
        });
    }

    private function removeDuplicateRows(string $table, callable $key): void
    {
        $seen = [];
        DB::table($table)->orderByDesc('selected')->orderBy('id')->get()->each(function ($row) use ($table, $key, &$seen): void {
            $value = $key($row);
            if (isset($seen[$value])) {
                DB::table($table)->where('id', $row->id)->delete();

                return;
            }
            $seen[$value] = true;
        });
    }

    private function keepOneSelectedPerGroup(string $table, callable $group): void
    {
        $selected = [];
        DB::table($table)->where('selected', true)->orderBy('id')->get()->each(function ($row) use ($table, $group, &$selected): void {
            $value = $group($row);
            if (isset($selected[$value])) {
                DB::table($table)->where('id', $row->id)->update(['selected' => false]);

                return;
            }
            $selected[$value] = true;
        });
    }

    private function consolidateChatColors(): void
    {
        DB::table('chat_colors')->orderBy('id')->get()->groupBy('name')->each(function ($colors): void {
            $canonical = $colors->first();
            foreach ($colors->skip(1) as $duplicate) {
                DB::table('player_chat_colors')->where('color_id', $duplicate->id)->update(['color_id' => $canonical->id]);
                DB::table('chat_colors')->where('id', $duplicate->id)->delete();
            }
        });
    }
};
