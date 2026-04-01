<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::table('legal_cases', function (Blueprint $table) {
            if (!Schema::hasColumn('legal_cases', 'start_date')) {
                $table->date('start_date')->nullable()->after('description');
            }

            if (!Schema::hasColumn('legal_cases', 'next_session_date')) {
                $table->date('next_session_date')->nullable()->after('start_date');
            }

            $table->dropUnique('legal_cases_case_number_unique');
            $table->unique(['law_firm_id', 'case_number'], 'legal_cases_firm_case_number_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('legal_cases', function (Blueprint $table) {
            if (Schema::hasColumn('legal_cases', 'start_date')) {
                $table->dropColumn('start_date');
            }

            if (Schema::hasColumn('legal_cases', 'next_session_date')) {
                $table->dropColumn('next_session_date');
            }

            $table->dropUnique('legal_cases_firm_case_number_unique');
            $table->unique('case_number', 'legal_cases_case_number_unique');
        });
    }
};
