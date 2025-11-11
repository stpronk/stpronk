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
        if (Schema::hasTable('assets') && Schema::hasColumn('assets', 'category_id')) {
            Schema::table('assets', function (Blueprint $table) {
                // Note: renaming columns may require doctrine/dbal.
                $table->renameColumn('category_id', 'asset_category_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('assets') && Schema::hasColumn('assets', 'asset_category_id')) {
            Schema::table('assets', function (Blueprint $table) {
                $table->renameColumn('asset_category_id', 'category_id');
            });
        }
    }
};
