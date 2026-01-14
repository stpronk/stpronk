<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tablePrefix = config('url-dissector.table_prefix');

        Schema::table($tablePrefix . 'urls', function (Blueprint $table) {
            $table->renameColumn('original_url', 'normalized_url');
        });
    }

    public function down(): void
    {
        $tablePrefix = config('url-dissector.table_prefix');

        Schema::table($tablePrefix . 'urls', function (Blueprint $table) {
            $table->renameColumn('normalized_url', 'original_url');
        });
    }
};
