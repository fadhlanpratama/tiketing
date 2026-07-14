<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Users;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        if (Users::where('email', 'admin@gmail.com')->exists()) {
            return;
        }

        Users::create([
            'nama_lengkap' => 'Admin Sistem Tiketing',
            'email'        => 'admin@gmail.com',
            'divisi'       => 'IT',
            'no_telp'      => '010101010101',
            'password'     => Hash::make('Admin123'),
            'role'         => 'admin',
        ]);
    }
}