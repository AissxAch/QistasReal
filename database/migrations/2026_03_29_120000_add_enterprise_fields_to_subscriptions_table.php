<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('contract_number')->nullable()->after('currency');
            $table->timestamp('contract_starts_at')->nullable()->after('contract_number');
            $table->timestamp('contract_ends_at')->nullable()->after('contract_starts_at');
            $table->unsignedInteger('user_limit')->nullable()->after('contract_ends_at');
            $table->string('billing_cycle')->nullable()->after('user_limit');
            $table->string('enterprise_account_name')->nullable()->after('billing_cycle');
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'contract_number',
                'contract_starts_at',
                'contract_ends_at',
                'user_limit',
                'billing_cycle',
                'enterprise_account_name',
            ]);
        });
    }
};
