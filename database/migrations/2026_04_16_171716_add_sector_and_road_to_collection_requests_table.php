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
        Schema::table('collection_requests', function (Blueprint $table) {
            $table->string('sector')->nullable()->after('zone');
            $table->string('road')->nullable()->after('sector');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collection_requests', function (Blueprint $table) {
            $table->dropColumn(['sector', 'road']);
        });
    }
};
