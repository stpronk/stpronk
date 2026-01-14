<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tablePrefix = config('url-dissector.table_prefix');

        Schema::create($tablePrefix . 'urls', function (Blueprint $table) use ($tablePrefix) {
            $table->id();
            $table->text('original_url');
            $table->foreignId('host_id')->constrained($tablePrefix . 'hosts')->cascadeOnDelete();
            $table->foreignId('path_id')->constrained($tablePrefix . 'paths')->cascadeOnDelete();
            $table->string('scheme', 20);
            $table->integer('port')->nullable();
            $table->text('query_string')->nullable();
            $table->string('fragment')->nullable();
            $table->nullableMorphs('urlable');
            $table->json('metadata')->nullable();
            $table->timestamp('parsed_at')->nullable();
            $table->timestamps();

            $table->index(['host_id', 'path_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('url-dissector.table_prefix') . 'urls');
    }
};
