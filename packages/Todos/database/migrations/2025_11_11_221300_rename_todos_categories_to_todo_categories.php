<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('todos_categories') && ! Schema::hasTable('todo_categories')) {
            Schema::rename('todos_categories', 'todo_categories');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('todo_categories') && ! Schema::hasTable('todos_categories')) {
            Schema::rename('todo_categories', 'todos_categories');
        }
    }
};
