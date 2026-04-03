<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('law_firm_id')->constrained()->onDelete('cascade');
            $table->string('plan'); // solo, office, enterprise
            $table->string('status')->default('active'); // active, expired, cancelled
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->decimal('amount', 8, 2);
            $table->string('currency')->default('DZD');
            $table->string('contract_number')->nullable();
            $table->timestamp('contract_starts_at')->nullable();
            $table->timestamp('contract_ends_at')->nullable();
            $table->unsignedInteger('user_limit')->nullable();
            $table->string('billing_cycle')->nullable();
            $table->string('enterprise_account_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};