<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@spk-kinerja.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // Optional: Create additional test user
        User::create([
            'name' => 'HR Manager',
            'username' => 'hrmanager',
            'email' => 'hr@spk-kinerja.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
    }
}
