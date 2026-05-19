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
        Schema::create('admin_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('department')->nullable()->default('Waste Management Operations');
            $table->string('access_level')->nullable()->default('Standard Admin');
            $table->timestamps();
        });

        Schema::create('collector_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('assigned_vehicle')->nullable();
            $table->string('license_number')->nullable();
            $table->string('shift_preference')->nullable()->default('Morning Shift');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collector_profiles');
        Schema::dropIfExists('admin_profiles');
    }
};
