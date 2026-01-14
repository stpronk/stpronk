<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tablePrefix = config('url-dissector.table_prefix');
        $tableName = $tablePrefix . 'urls';

        Schema::table($tableName, function (Blueprint $table) {
            $table->string('is_online')->nullable()->change();
        });

        // Convert existing boolean values to enum strings
        DB::table($tableName)->where('is_online', '1')->update(['is_online' => 'online']);
        DB::table($tableName)->where('is_online', '0')->update(['is_online' => 'offline']);
        DB::table($tableName)->whereNull('is_online')->update(['is_online' => 'unknown']);
    }

    public function down(): void
    {
        $tablePrefix = config('url-dissector.table_prefix');
        $tableName = $tablePrefix . 'urls';

        // Convert enum strings back to boolean values
        DB::table($tableName)->where('is_online', 'online')->update(['is_online' => '1']);
        DB::table($tableName)->where('is_online', 'offline')->update(['is_online' => '0']);
        DB::table($tableName)->where('is_online', 'unknown')->update(['is_online' => null]);

        Schema::table($tableName, function (Blueprint $table) {
            $table->boolean('is_online')->nullable()->change();
        });
    }
};
