<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tablePrefix = config('url-dissector.table_prefix');

        Schema::create($tablePrefix . 'urlables', function (Blueprint $table) use ($tablePrefix) {
            $table->id();
            $table->foreignId('url_id')->constrained($tablePrefix . 'urls')->cascadeOnDelete();
            $table->morphs('urlable');
            $table->timestamps();

            $table->index(['url_id', 'urlable_type', 'urlable_id'], 'url_dissector_urlables_index');
        });

        Schema::table($tablePrefix . 'urls', function (Blueprint $table) {
            $table->dropMorphs('urlable');
            $table->unique('original_url');
        });
    }

    public function down(): void
    {
        $tablePrefix = config('url-dissector.table_prefix');

        Schema::table($tablePrefix . 'urls', function (Blueprint $table) {
            $table->nullableMorphs('urlable');
            $table->dropUnique(['original_url']);
        });

        Schema::dropIfExists($tablePrefix . 'urlables');
    }
};
