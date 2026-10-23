<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->boolean('is_spam')->default(false)->index()->after('message');
            $table->string('spam_reason')->nullable()->after('is_spam');
            $table->string('ip_address', 45)->nullable()->after('spam_reason');
            $table->text('user_agent')->nullable()->after('ip_address');
        });
    }

    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropIndex(['is_spam']);
            $table->dropColumn(['is_spam', 'spam_reason', 'ip_address', 'user_agent']);
        });
    }
};
