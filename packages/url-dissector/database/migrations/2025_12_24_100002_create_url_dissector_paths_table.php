<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(config('url-dissector.table_prefix') . 'paths', function (Blueprint $table) {
            $table->id();
            $table->text('full_path');
            $table->string('hash')->unique()->index();
            $table->integer('depth');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('url-dissector.table_prefix') . 'paths');
    }
};
