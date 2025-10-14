<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('homepage_contents', function (Blueprint $table) {
            $table->id();
            $table->string('hero_title')->nullable();
            $table->text('hero_subtitle')->nullable();
            $table->string('hero_cta_experience')->nullable();
            $table->string('hero_cta_contact')->nullable();
            $table->text('sytatsu_text')->nullable();
            $table->string('about_title')->nullable();
            $table->text('about_paragraph')->nullable();
            $table->string('skills_title')->nullable();
            $table->string('experience_title')->nullable();
            $table->string('contact_title')->nullable();
            $table->string('footer_text')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_contents');
    }
};
