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

        Role::where('name', 'super_admin')
            ->first()
            ?->permissions()
            ->sync($allPermissions);

        $this->syncRole(
            'admin_dinas',
            [
                'dashboard.view',
                'users.view',
                'users.create',
                'users.update',
                'roles.view',
                'roles.update',
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
                'pembayaran.verify',
                'pembayaran.delete',
                'setoran.view',
                'setoran.update',
                'setoran.verify',
                'laporan.view',
                'laporan.export',
                'wajib_retribusi.view',
                'wajib_retribusi.create',
                'wajib_retribusi.update',
                'wajib_retribusi.delete',
                'petugas.view',
                'petugas.create',
                'petugas.update',
                'petugas.delete',
                'banner.view',
                'banner.create',
                'banner.update',
                'banner.delete',
            ]
        );

        $this->syncRole(
            'petugas',
            [
                'dashboard.view',
                'wilayah.view',
                'jenis_retribusi.view',
                'tarif.view',
                'pengajuan.view',
                'tagihan.view',
                'pembayaran.view',
                'pembayaran.create',
            ]
        );

        $this->syncRole(
            'bendahara',
            [
                'dashboard.view',
                'tagihan.view',
                'pembayaran.view',
                'pembayaran.verify',
                'setoran.view',
                'setoran.update',
                'setoran.verify',
                'laporan.view',
                'laporan.export',
            ]
        );

        $this->syncRole(
            'pimpinan',
            [
                'dashboard.view',
                'wilayah.view',
                'jenis_retribusi.view',
                'tarif.view',
                'pengajuan.view',
                'tagihan.view',
                'pembayaran.view',
                'setoran.view',
                'laporan.view',
                'laporan.export',
                'wajib_retribusi.view',
            ]
        );

        $this->syncRole(
            'user',
            [
                'dashboard.view',
                'pengajuan.view',
                'pengajuan.create',
                'pengajuan.update',
                'tagihan.view',
                'pembayaran.view',
                'pembayaran.create',
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