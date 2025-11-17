<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shareables', function (Blueprint $table) {
            $table->id();
            $table->morphs('shareable');
            $table->unsignedInteger('shared_with');
            $table->unsignedInteger('shared_by');
            $table->dateTime('shared_at');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('shared_with')
                ->references('id')
                ->on('users');

            $table->foreign('shared_by')
                ->references('id')
                ->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shareables');
    }
};
