<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ocr_scan_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('law_firm_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('file_path');           // stored path in storage/app/
            $table->string('original_name')->nullable();
            $table->string('mime_type')->nullable();
            // status: pending → processing → done | failed
            $table->string('status')->default('pending');
            $table->json('result')->nullable();    // extracted JSON from Python OCR
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ocr_scan_jobs');
    }
};
