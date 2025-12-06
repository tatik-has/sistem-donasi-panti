<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Panti',
            'email' => 'admin@panti.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'no_telepon' => '081234567890',
            'alamat' => 'Jl. Panti Asuhan No. 123',
        ]);

    }
}