<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeder Admin
        User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin', 
            'is_active' => true,
        ]);
        // Seeder User
        User::create([
            'username' => 'user',
            'email' => 'user@gmail.com',
            'password'=> Hash::make('user1234'),
            'role' => 'user',
            'is_active' => false,
        ]);
        User::create([
            'username' => 'admin2',
            'email' => 'admin2@gmail.com',
            'password' => Hash::make('adminbaru'),
            'role' => 'admin',
            'is_active' => false,
        ]);
    }
}
