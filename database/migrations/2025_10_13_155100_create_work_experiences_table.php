<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('work_experiences', function (Blueprint $table) {
            $table->id();
            $table->string('job_title');
            $table->string('organisation');
            $table->string('organisation_website')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->text('description');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->index('sort_order');
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_experiences');
    }
};
