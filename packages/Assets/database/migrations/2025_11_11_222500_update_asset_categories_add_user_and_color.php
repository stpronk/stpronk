<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('asset_categories', function (Blueprint $table) {
            // Add columns
            $table->string('color')->default('Amber')->after('name');
            $table->foreignId('user_id')->nullable()->after('color')->constrained('users');
        });

        // Backfill existing rows with user_id = 1 as per current dataset assumption
        DB::table('asset_categories')->whereNull('user_id')->update(['user_id' => 1]);

        Schema::table('asset_categories', function (Blueprint $table) {
            // Enforce NOT NULL on user_id
            $table->foreignId('user_id')->nullable(false)->change();

            // Drop the old global unique index on name, if it exists
            if (Schema::hasIndex('asset_categories', 'asset_categories_name_unique')) {
                $table->dropUnique('asset_categories_name_unique');
            }

            // Add composite unique for user_id + name
            $table->unique(['user_id', 'name'], 'asset_categories_user_id_name_unique');
        });
    }

    public function down(): void
    {
        Schema::table('asset_categories', function (Blueprint $table) {
            // Drop composite unique
            $table->dropUnique('asset_categories_user_id_name_unique');

            // Restore original unique on name
            $table->unique('name');
        });

        Schema::table('asset_categories', function (Blueprint $table) {
            // Drop foreign and columns
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn('color');
        });
    }
};
