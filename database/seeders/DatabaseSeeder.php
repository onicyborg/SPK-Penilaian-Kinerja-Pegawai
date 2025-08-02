<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call UserSeeder to create default users
        $this->call([
            UserSeeder::class,
            EmployeeSeeder::class,
        ]);
    }
}
