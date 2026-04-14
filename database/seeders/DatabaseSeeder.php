<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Waste Collector 1',
            'email' => 'collector@gmail.com',
            'role' => 'waste_collector',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@gmail.com',
            'role' => 'user',
            'password' => bcrypt('password'),
        ]);
    }
}
