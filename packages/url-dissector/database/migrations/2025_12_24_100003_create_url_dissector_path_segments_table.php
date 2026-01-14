<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tablePrefix = config('url-dissector.table_prefix');

        Schema::create($tablePrefix . 'path_segments', function (Blueprint $table) use ($tablePrefix) {
            $table->id();
            $table->foreignId('path_id')->constrained($tablePrefix . 'paths')->cascadeOnDelete();
            $table->string('segment')->index();
            $table->integer('depth');
            $table->integer('order');
            $table->foreignId('parent_segment_id')->nullable()->constrained($tablePrefix . 'path_segments')->nullOnDelete();

            $table->index(['path_id', 'depth']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('url-dissector.table_prefix') . 'path_segments');
    }
};
