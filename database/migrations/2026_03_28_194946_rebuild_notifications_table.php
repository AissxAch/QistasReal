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
        Schema::table('notifications', function (Blueprint $table) {
            $table->foreignId('law_firm_id')->after('id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->after('law_firm_id')->constrained()->onDelete('cascade');
            $table->string('type')->after('user_id');
            $table->string('title')->after('type');
            $table->text('body')->nullable()->after('title');
            $table->json('data')->nullable()->after('body');
            $table->timestamp('read_at')->nullable()->after('data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('law_firm_id');
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn(['type','title','body','data','read_at']);
        });
    }
};
