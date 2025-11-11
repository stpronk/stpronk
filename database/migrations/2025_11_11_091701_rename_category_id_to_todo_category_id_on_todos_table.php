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
        if (Schema::hasTable('todos') && Schema::hasColumn('todos', 'category_id')) {
            Schema::table('todos', function (Blueprint $table) {
                // Note: renaming columns may require doctrine/dbal.
                $table->renameColumn('category_id', 'todo_category_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('todos') && Schema::hasColumn('todos', 'todo_category_id')) {
            Schema::table('todos', function (Blueprint $table) {
                $table->renameColumn('todo_category_id', 'category_id');
            });
        }
    }
};
