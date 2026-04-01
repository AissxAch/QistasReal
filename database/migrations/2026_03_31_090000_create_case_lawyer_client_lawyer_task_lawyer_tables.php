<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_lawyer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('legal_cases')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['case_id', 'user_id'], 'case_lawyer_unique');
        });

        Schema::create('client_lawyer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['client_id', 'user_id'], 'client_lawyer_unique');
        });

        Schema::create('task_lawyer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['task_id', 'user_id'], 'task_lawyer_unique');
        });

        DB::table('legal_cases')
            ->whereNotNull('assigned_lawyer_id')
            ->orderBy('id')
            ->select(['id', 'assigned_lawyer_id'])
            ->get()
            ->each(function ($row) {
                DB::table('case_lawyer')->insertOrIgnore([
                    'case_id' => $row->id,
                    'user_id' => $row->assigned_lawyer_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });

        DB::table('tasks')
            ->whereNotNull('assigned_to')
            ->orderBy('id')
            ->select(['id', 'assigned_to'])
            ->get()
            ->each(function ($row) {
                DB::table('task_lawyer')->insertOrIgnore([
                    'task_id' => $row->id,
                    'user_id' => $row->assigned_to,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_lawyer');
        Schema::dropIfExists('client_lawyer');
        Schema::dropIfExists('case_lawyer');
    }
};
