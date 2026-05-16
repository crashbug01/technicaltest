<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin (Yang input data)
        User::create([
            'name' => 'Admin Nickel',
            'email' => 'admin@nikel.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Approver 1 (Atasan Langsung)
        User::create([
            'name' => 'Manager Operasional',
            'email' => 'approver1@nikel.com',
            'password' => Hash::make('password'),
            'role' => 'approver',
        ]);

        // Approver 2 (Kepala Cabang)
        User::create([
            'name' => 'General Manager',
            'email' => 'approver2@nikel.com',
            'password' => Hash::make('password'),
            'role' => 'approver',
        ]);
    }
}
