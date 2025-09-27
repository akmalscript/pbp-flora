<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password123', // jangan simpan password plain
            'phone' => '081234567890',
            'role' => 1, // misal 1 = admin
            'active' => 1,
        ]);

        User::create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => 'password123',
            'phone' => '081234567890',
            'role' => 0,
            'active' => 1,
        ]);
    }
}
