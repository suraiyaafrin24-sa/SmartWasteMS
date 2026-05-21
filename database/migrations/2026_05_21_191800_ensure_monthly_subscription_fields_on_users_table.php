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
        //
    }
};
