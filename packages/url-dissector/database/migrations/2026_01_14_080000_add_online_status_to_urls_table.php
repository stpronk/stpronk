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
            $table->boolean('is_online')->nullable()->after('is_valid');
            $table->timestamp('last_checked_at')->nullable()->after('parsed_at');
        });
    }

    public function down(): void
    {
        $tablePrefix = config('url-dissector.table_prefix');

        Schema::table($tablePrefix . 'urls', function (Blueprint $table) {
            $table->dropColumn(['is_online', 'last_checked_at']);
        });
    }
};
