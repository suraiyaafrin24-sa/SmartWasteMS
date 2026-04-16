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
        Schema::create('duty_rosters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collector_id')->constrained('users')->onDelete('cascade');
            $table->string('sector');
            $table->string('road')->nullable();
            $table->date('date');
            $table->string('shift'); // Morning Shift / Night Shift
            $table->string('status')->default('Active'); // Active, Completed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('duty_rosters');
    }
};
