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
        Schema::create('collection_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('collector_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type'); // Recyclable, General Waste, etc.
            $table->date('scheduled_date');
            $table->time('scheduled_time')->nullable();
            $table->string('status')->default('Pending'); // Pending, Scheduled, Completed, Cancelled
            $table->decimal('weight', 8, 2)->nullable(); // In kg
            $table->text('details')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_requests');
    }
};
