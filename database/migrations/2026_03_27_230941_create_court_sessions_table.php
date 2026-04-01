<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('court_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('law_firm_id')->constrained()->onDelete('cascade');
            $table->foreignId('case_id')->constrained('legal_cases')->onDelete('cascade');
            $table->date('session_date');
            $table->time('session_time');
            $table->string('court');
            $table->string('room')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('scheduled');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('court_sessions');
    }
};