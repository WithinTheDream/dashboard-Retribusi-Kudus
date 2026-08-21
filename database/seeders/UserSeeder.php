<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::where('name', 'super_admin')->firstOrFail();
        $adminDinas = Role::where('name', 'admin_dinas')->firstOrFail();
        $petugas = Role::where('name', 'petugas')->firstOrFail();
        $bendahara = Role::where('name', 'bendahara')->firstOrFail();
        $pimpinan = Role::where('name', 'pimpinan')->firstOrFail();
        $userRole = Role::where('name', 'user')->firstOrFail();

        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'nama_lengkap' => 'Super Administrator',
                'email' => 'admin@retribusikudus.test',
                'no_hp' => '081234567890',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'role_id' => $superAdmin->id,
            ]
        );

        User::updateOrCreate(
            ['username' => 'admin_dinas'],
            [
                'nama_lengkap' => 'Admin Dinas',
                'email' => 'admindinas@retribusikudus.test',
                'no_hp' => '081234567891',
                'password' => Hash::make('password'),
                'role' => 'admin_dinas',
                'role_id' => $adminDinas->id,
            ]
        );

        User::updateOrCreate(
            ['username' => 'petugas'],
            [
                'nama_lengkap' => 'Petugas Demo',
                'email' => 'petugas@retribusikudus.test',
                'no_hp' => '081234567892',
                'password' => Hash::make('password'),
                'role' => 'petugas',
                'role_id' => $petugas->id,
            ]
        );

        User::updateOrCreate(
            ['username' => 'bendahara'],
            [
                'nama_lengkap' => 'Bendahara Demo',
                'email' => 'bendahara@retribusikudus.test',
                'no_hp' => '081234567893',
                'password' => Hash::make('password'),
                'role' => 'bendahara',
                'role_id' => $bendahara->id,
            ]
        );

        User::updateOrCreate(
            ['username' => 'pimpinan'],
            [
                'nama_lengkap' => 'Pimpinan Demo',
                'email' => 'pimpinan@retribusikudus.test',
                'no_hp' => '081234567894',
                'password' => Hash::make('password'),
                'role' => 'pimpinan',
                'role_id' => $pimpinan->id,
            ]
        );

        User::updateOrCreate(
            ['username' => 'user'],
            [
                'nama_lengkap' => 'User Demo',
                'email' => 'user@retribusikudus.test',
                'no_hp' => '081234567895',
                'password' => Hash::make('password'),
                'role' => 'user',
                'role_id' => $userRole->id,
            ]
        );
    }
}