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
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@demo.com',
            'role' => UserRole::Admin,
        ]);

        User::factory()->create([
            'name' => 'Manager User',
            'email' => 'manager@demo.com',
            'role' => UserRole::Manager,
        ]);

        User::factory()->create([
            'name' => 'Staff User',
            'email' => 'staff@demo.com',
            'role' => UserRole::Staff,
        ]);

        User::factory()->create([
            'name' => 'Staff User 2',
            'email' => 'staff2@demo.com',
            'role' => UserRole::Staff,
        ]);
    }
}
