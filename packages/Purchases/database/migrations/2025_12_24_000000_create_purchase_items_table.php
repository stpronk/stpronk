<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_items', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('name');
            $blueprint->string('url')->nullable();
            $blueprint->decimal('price', 10, 2)->nullable();
            $blueprint->string('status');
            $blueprint->string('priority');
            $blueprint->foreignId('user_id')->constrained()->cascadeOnDelete();
            $blueprint->foreignId('todo_id')->nullable()->constrained()->nullOnDelete();
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
