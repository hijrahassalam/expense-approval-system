<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@demo.com'],
            ['name' => 'Admin User', 'role' => UserRole::Admin, 'password' => bcrypt('password')],
        );

        User::firstOrCreate(
            ['email' => 'manager@demo.com'],
            ['name' => 'Manager User', 'role' => UserRole::Manager, 'password' => bcrypt('password')],
        );

        User::firstOrCreate(
            ['email' => 'staff@demo.com'],
            ['name' => 'Staff User', 'role' => UserRole::Staff, 'password' => bcrypt('password')],
        );

        User::firstOrCreate(
            ['email' => 'staff2@demo.com'],
            ['name' => 'Staff User 2', 'role' => UserRole::Staff, 'password' => bcrypt('password')],
        );
    }
}
