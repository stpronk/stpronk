<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('todos')) {
            Schema::table('todos', function (Blueprint $table) {
                if (! Schema::hasColumn('todos', 'due_date')) {
                    $table->date('due_date')->nullable()->after('title');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('todos')) {
            Schema::table('todos', function (Blueprint $table) {
                if (Schema::hasColumn('todos', 'due_date')) {
                    $table->dropColumn('due_date');
                }
            });
        }
    }
};
