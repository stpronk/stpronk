<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            // Store priority as an ENUM to enforce allowed values.
            // Not nullable, no DB default (form sets default to 'low').
            $table->enum('priority', ['low', 'medium', 'high', 'extreme'])
                ->after('title')
                ->nullable(false);
        });
    }

    public function down(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            $table->dropColumn('priority');
        });
    }
};
