<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Mahasiswa
        User::create([
            'username' => 'mahasiswa01',
            'email'    => 'mahasiswa01@gmail.com',
            'password' => Hash::make('password123'),
            'role'     => 'mahasiswa',
        ]);

        // Admin Prodi
        User::create([
            'username' => 'adminprodi',
            'email'    => 'prodi@gmail.com',
            'password' => Hash::make('password123'),
            'role'     => 'admin_prodi',
        ]);

        // Admin Fakultas
        User::create([
            'username' => 'adminfakultas',
            'email'    => 'fakultas@gmail.com',
            'password' => Hash::make('password123'),
            'role'     => 'admin_fakultas',
        ]);
    }
}
