<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('owner', 'lawyer', 'assistant', 'admin') NOT NULL DEFAULT 'lawyer'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('owner', 'lawyer', 'assistant') NOT NULL DEFAULT 'lawyer'");
    }
};
