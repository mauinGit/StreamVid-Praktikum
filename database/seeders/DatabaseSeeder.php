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
        // Create Admin
        User::create([
            'name' => 'Admin StreamVid',
            'email' => 'admin@streamvid.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Regular User
        User::create([
            'name' => 'User Demo',
            'email' => 'user@streamvid.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Seed Films
        $this->call(FilmSeeder::class);
    }
}
