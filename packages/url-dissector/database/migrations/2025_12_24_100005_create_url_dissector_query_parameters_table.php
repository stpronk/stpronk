<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tablePrefix = config('url-dissector.table_prefix');

        Schema::create($tablePrefix . 'query_parameters', function (Blueprint $table) use ($tablePrefix) {
            $table->id();
            $table->foreignId('url_id')->constrained($tablePrefix . 'urls')->cascadeOnDelete();
            $table->string('key')->index();
            $table->text('value')->nullable();
            $table->integer('order');

            $table->index(['url_id', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('url-dissector.table_prefix') . 'query_parameters');
    }
};
