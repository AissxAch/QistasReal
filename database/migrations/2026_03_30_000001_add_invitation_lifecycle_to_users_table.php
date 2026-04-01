<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('invited_by_user_id')->nullable()->after('law_firm_id')->constrained('users')->nullOnDelete();
            $table->timestamp('invited_at')->nullable()->after('specialty');
            $table->timestamp('invitation_expires_at')->nullable()->after('invited_at');
            $table->timestamp('activated_at')->nullable()->after('invitation_expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('invited_by_user_id');
            $table->dropColumn(['invited_at', 'invitation_expires_at', 'activated_at']);
        });
    }
};