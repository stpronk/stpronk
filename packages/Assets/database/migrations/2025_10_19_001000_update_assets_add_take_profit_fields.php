<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            if (!Schema::hasColumn('assets', 'status')) {
                $table->string('status')->default('active')->index();
            }
            if (!Schema::hasColumn('assets', 'take_profit_cents')) {
                $table->unsignedInteger('take_profit_cents')->nullable()->after('price_cents');
            }
            if (!Schema::hasColumn('assets', 'closed_at')) {
                $table->timestamp('closed_at')->nullable()->after('take_profit_cents');
            }
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            if (Schema::hasColumn('assets', 'closed_at')) {
                $table->dropColumn('closed_at');
            }
            if (Schema::hasColumn('assets', 'take_profit_cents')) {
                $table->dropColumn('take_profit_cents');
            }
            if (Schema::hasColumn('assets', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
