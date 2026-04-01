<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('legal_cases', function (Blueprint $table) {
            if (!Schema::hasColumn('legal_cases', 'created_by_user_id')) {
                $table->foreignId('created_by_user_id')
                    ->nullable()
                    ->after('law_firm_id')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('legal_cases', 'assigned_lawyer_id')) {
                $table->foreignId('assigned_lawyer_id')
                    ->nullable()
                    ->after('created_by_user_id')
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('legal_cases', function (Blueprint $table) {
            if (Schema::hasColumn('legal_cases', 'assigned_lawyer_id')) {
                $table->dropConstrainedForeignId('assigned_lawyer_id');
            }

            if (Schema::hasColumn('legal_cases', 'created_by_user_id')) {
                $table->dropConstrainedForeignId('created_by_user_id');
            }
        });
    }
};
