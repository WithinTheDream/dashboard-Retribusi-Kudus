<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Admin',
                'description' => 'Akses penuh terhadap seluruh sistem.',
                'is_system' => true,
            ],
            [
                'name' => 'admin_dinas',
                'display_name' => 'Admin Dinas',
                'description' => 'Mengelola operasional retribusi.',
                'is_system' => true,
            ],
            [
                'name' => 'petugas',
                'display_name' => 'Petugas Lapangan',
                'description' => 'Melakukan penagihan dan transaksi lapangan.',
                'is_system' => true,
            ],
            [
                'name' => 'bendahara',
                'display_name' => 'Bendahara',
                'description' => 'Mengelola penerimaan dan setoran.',
                'is_system' => true,
            ],
            [
                'name' => 'pimpinan',
                'display_name' => 'Pimpinan',
                'description' => 'Melihat monitoring dan laporan.',
                'is_system' => true,
            ],
            [
                'name' => 'user',
                'display_name' => 'Masyarakat',
                'description' => 'Pengguna layanan retribusi.',
                'is_system' => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}