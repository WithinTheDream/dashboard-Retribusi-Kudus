<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $allPermissions = Permission::pluck('id')->all();

        // 1. Super Admin: Akses Penuh ke Semua Menu & Modul
        Role::where('name', 'super_admin')
            ->first()
            ?->permissions()
            ->sync($allPermissions);

        // 2. Admin Dinas: Operasional Utama (Master, Operasional, Laporan, Kelola Pengguna Non-Admin)
        $this->syncRole(
            'admin_dinas',
            [
                'dashboard.view',
                'users.view',
                'users.create',
                'users.update',
                'users.delete',
                'wilayah.view',
                'wilayah.create',
                'wilayah.update',
                'wilayah.delete',
                'jenis_retribusi.view',
                'jenis_retribusi.create',
                'jenis_retribusi.update',
                'jenis_retribusi.delete',
                'tarif.view',
                'tarif.create',
                'tarif.update',
                'tarif.delete',
                'wajib_retribusi.view',
                'wajib_retribusi.create',
                'wajib_retribusi.update',
                'wajib_retribusi.delete',
                'pengajuan.view',
                'pengajuan.create',
                'pengajuan.update',
                'pengajuan.verify',
                'pengajuan.delete',
                'tagihan.view',
                'tagihan.create',
                'tagihan.update',
                'tagihan.delete',
                'pembayaran.view',
                'pembayaran.create',
                'pembayaran.update',
                'pembayaran.delete',
                'setoran.view',
                'penugasan.view',
                'penugasan.create',
                'penugasan.update',
                'penugasan.delete',
                'laporan.view',
                'laporan.export',
                'audit.view',
            ]
        );

        // 3. Bendahara: Verifikasi Finansial, Pembayaran, Setoran, dan Laporan Kas
        $this->syncRole(
            'bendahara',
            [
                'dashboard.view',
                'tagihan.view',
                'pembayaran.view',
                'setoran.view',
                'setoran.verify',
                'laporan.view',
                'laporan.export',
            ]
        );

        // 4. Pimpinan / Kepala Dinas: Monitoring Eksekutif & Laporan (Read-Only)
        $this->syncRole(
            'pimpinan',
            [
                'dashboard.view',
                'laporan.view',
                'laporan.export',
            ]
        );

        // 5. Petugas Lapangan: Mobile App Penagihan
        $this->syncRole(
            'petugas',
            [
                'dashboard.view',
                'tagihan.view',
                'pembayaran.view',
                'pembayaran.create',
                'setoran.view',
                'setoran.create',
            ]
        );

        // 6. User / Warga: Mobile App Pengajuan & Info Tagihan Pribadi
        $this->syncRole(
            'user',
            [
                'dashboard.view',
                'pengajuan.view',
                'pengajuan.create',
                'pengajuan.update',
                'tagihan.view',
                'pembayaran.view',
            ]
        );
    }

    private function syncRole(
        string $roleName,
        array $permissions
    ): void {
        $role = Role::where('name', $roleName)->first();

        if (!$role) {
            return;
        }

        $permissionIds = Permission::whereIn(
            'name',
            $permissions
        )->pluck('id');

        $role->permissions()->sync($permissionIds);
    }
}