<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('categories') && ! Schema::hasTable('asset_categories')) {
            Schema::rename('categories', 'asset_categories');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('asset_categories') && ! Schema::hasTable('categories')) {
            Schema::rename('asset_categories', 'categories');
        }
    }
};
