<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create or update Admin User
        User::updateOrCreate(
            ['email' => 'admin@cafeshop.com'],
            [
                'name' => 'Cafe Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Create or update Normal User
        User::updateOrCreate(
            ['email' => 'user@cafeshop.com'],
            [
                'name' => 'Normal Customer',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );
    }
}
