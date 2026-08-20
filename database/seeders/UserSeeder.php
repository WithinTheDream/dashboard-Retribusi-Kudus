<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nama_lengkap' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@retribusikudus.test',
            'no_hp' => '081234567890',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'nama_lengkap' => 'User Demo',
            'username' => 'user',
            'email' => 'user@retribusikudus.test',
            'no_hp' => '081234567891',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        User::create([
            'nama_lengkap' => 'Petugas Demo',
            'username' => 'petugas',
            'email' => 'petugas@retribusikudus.test',
            'no_hp' => '081234567892',
            'password' => Hash::make('password'),
            'role' => 'petugas',
        ]);

        User::create([
            'nama_lengkap' => 'Bendahara Demo',
            'username' => 'bendahara',
            'email' => 'bendahara@retribusikudus.test',
            'no_hp' => '081234567893',
            'password' => Hash::make('password'),
            'role' => 'bendahara',
        ]);
    }
}
