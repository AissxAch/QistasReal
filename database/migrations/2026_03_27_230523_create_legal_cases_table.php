<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legal_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('law_firm_id')->constrained()->onDelete('cascade');
            $table->string('case_number')->unique();
            $table->string('title')->nullable();
            $table->string('court');
            $table->string('case_type'); // مدنية، جزائية، تجارية، إدارية
            $table->string('degree'); // ابتدائي، استئناف، نقض، تنفيذ
            $table->string('status')->default('active'); // active, suspended, closed, archived
            $table->string('priority')->default('medium'); // high, medium, low
            $table->date('start_date')->nullable();
            $table->decimal('fees_total', 10, 2)->default(0);
            $table->decimal('fees_paid', 10, 2)->default(0);
            $table->decimal('fees_remaining', 10, 2)->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legal_cases');
    }
};