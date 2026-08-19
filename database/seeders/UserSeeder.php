<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nama_lengkap' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@retribusikudas.test',
            'no_hp' => '081234567890',
            'password' => 'password',
            'role' => 'admin',
        ]);

        User::create([
            'nama_lengkap' => 'User Demo',
            'username' => 'user',
            'email' => 'user@retribusikudas.test',
            'no_hp' => '081234567891',
            'password' => 'password',
            'role' => 'user',
        ]);

        User::create([
            'nama_lengkap' => 'Petugas Demo',
            'username' => 'petugas',
            'email' => 'petugas@retribusikudas.test',
            'no_hp' => '081234567892',
            'password' => 'password',
            'role' => 'petugas',
        ]);

        User::create([
            'nama_lengkap' => 'Bendahara Demo',
            'username' => 'bendahara',
            'email' => 'bendahara@retribusikudas.test',
            'no_hp' => '081234567893',
            'password' => 'password',
            'role' => 'bendahara',
        ]);
    }
}