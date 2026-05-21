<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'monthly_payment_status')) {
                $table->string('monthly_payment_status')->default('Unpaid')->after('role');
            }

            if (! Schema::hasColumn('users', 'subscription_valid_until')) {
                $table->date('subscription_valid_until')->nullable()->after('monthly_payment_status');
            }

            if (! Schema::hasColumn('users', 'last_transaction_id')) {
                $table->string('last_transaction_id')->nullable()->after('subscription_valid_until');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = array_filter([
                Schema::hasColumn('users', 'last_transaction_id') ? 'last_transaction_id' : null,
                Schema::hasColumn('users', 'subscription_valid_until') ? 'subscription_valid_until' : null,
                Schema::hasColumn('users', 'monthly_payment_status') ? 'monthly_payment_status' : null,
            ]);

            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
