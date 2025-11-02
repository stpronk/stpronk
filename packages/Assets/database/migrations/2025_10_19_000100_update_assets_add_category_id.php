<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add the new nullable foreign key first
        Schema::table('assets', function (Blueprint $table) {
            if (!Schema::hasColumn('assets', 'category_id')) {
                $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            }
        });

        // Backfill category_id from existing string column if present
        if (Schema::hasColumn('assets', 'category')) {
            // Get distinct existing categories
            $categories = DB::table('assets')
                ->select('category')
                ->whereNotNull('category')
                ->distinct()
                ->pluck('category')
                ->filter()
                ->values();

            // Insert categories that don't exist yet
            foreach ($categories as $name) {
                $existingId = DB::table('categories')->where('name', $name)->value('id');
                if (!$existingId) {
                    DB::table('categories')->insert([
                        'name' => $name,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Map assets to category_id
            $pairs = DB::table('categories')->whereIn('name', $categories)->pluck('id', 'name');
            DB::table('assets')->whereNotNull('category')->orderBy('id')->chunkById(500, function ($rows) use ($pairs) {
                foreach ($rows as $row) {
                    $id = $pairs[$row->category] ?? null;
                    if ($id) {
                        DB::table('assets')->where('id', $row->id)->update(['category_id' => $id]);
                    }
                }
            });

            // Finally, drop the old string column
            Schema::table('assets', function (Blueprint $table) {
                if (Schema::hasColumn('assets', 'category')) {
                    $table->dropColumn('category');
                }
            });
        }
    }

    public function down(): void
    {
        // Recreate category string column (nullable) and backfill from relation, then drop fk
        Schema::table('assets', function (Blueprint $table) {
            if (!Schema::hasColumn('assets', 'category')) {
                $table->string('category')->nullable();
            }
        });

        // Backfill from category relation if present
        if (Schema::hasColumn('assets', 'category_id')) {
            $pairs = DB::table('categories')->pluck('name', 'id');
            DB::table('assets')->whereNotNull('category_id')->orderBy('id')->chunkById(500, function ($rows) use ($pairs) {
                foreach ($rows as $row) {
                    $name = $pairs[$row->category_id] ?? null;
                    if ($name) {
                        DB::table('assets')->where('id', $row->id)->update(['category' => $name]);
                    }
                }
            });
        }

        // Drop the foreign key column
        Schema::table('assets', function (Blueprint $table) {
            if (Schema::hasColumn('assets', 'category_id')) {
                // Laravel will auto-drop the FK when dropping the column in most drivers
                $table->dropConstrainedForeignId('category_id');
            }
        });
    }
};
