<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_client', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('legal_cases')->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['plaintiff', 'defendant', 'witness', 'other'])->default('plaintiff');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_client');
    }
};