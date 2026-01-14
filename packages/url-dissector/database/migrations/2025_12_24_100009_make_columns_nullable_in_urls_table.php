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
            $table->foreignId('host_id')->nullable()->change();
            $table->foreignId('path_id')->nullable()->change();
            $table->string('scheme', 20)->nullable()->change();
        });
    }

    public function down(): void
    {
        $tablePrefix = config('url-dissector.table_prefix');

        Schema::table($tablePrefix . 'urls', function (Blueprint $table) {
            $table->foreignId('host_id')->nullable(false)->change();
            $table->foreignId('path_id')->nullable(false)->change();
            $table->string('scheme', 20)->nullable(false)->change();
        });
    }
};
