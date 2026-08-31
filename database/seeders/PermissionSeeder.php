<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'dashboard.view', 'display_name' => 'Melihat Dashboard', 'module' => 'dashboard'],

            ['name' => 'users.view', 'display_name' => 'Melihat Pengguna', 'module' => 'users'],
            ['name' => 'users.create', 'display_name' => 'Menambah Pengguna', 'module' => 'users'],
            ['name' => 'users.update', 'display_name' => 'Mengubah Pengguna', 'module' => 'users'],
            ['name' => 'users.delete', 'display_name' => 'Menghapus Pengguna', 'module' => 'users'],

            ['name' => 'roles.view', 'display_name' => 'Melihat Role', 'module' => 'roles'],
            ['name' => 'roles.create', 'display_name' => 'Menambah Role', 'module' => 'roles'],
            ['name' => 'roles.update', 'display_name' => 'Mengubah Permission Role', 'module' => 'roles'],
            ['name' => 'roles.delete', 'display_name' => 'Menghapus Role', 'module' => 'roles'],

            ['name' => 'wilayah.view', 'display_name' => 'Melihat Wilayah', 'module' => 'wilayah'],
            ['name' => 'wilayah.create', 'display_name' => 'Menambah Wilayah', 'module' => 'wilayah'],
            ['name' => 'wilayah.update', 'display_name' => 'Mengubah Wilayah', 'module' => 'wilayah'],
            ['name' => 'wilayah.delete', 'display_name' => 'Menghapus Wilayah', 'module' => 'wilayah'],

            ['name' => 'jenis_retribusi.view', 'display_name' => 'Melihat Jenis Retribusi', 'module' => 'jenis_retribusi'],
            ['name' => 'jenis_retribusi.create', 'display_name' => 'Menambah Jenis Retribusi', 'module' => 'jenis_retribusi'],
            ['name' => 'jenis_retribusi.update', 'display_name' => 'Mengubah Jenis Retribusi', 'module' => 'jenis_retribusi'],
            ['name' => 'jenis_retribusi.delete', 'display_name' => 'Menghapus Jenis Retribusi', 'module' => 'jenis_retribusi'],

            ['name' => 'tarif.view', 'display_name' => 'Melihat Tarif', 'module' => 'tarif'],
            ['name' => 'tarif.create', 'display_name' => 'Menambah Tarif', 'module' => 'tarif'],
            ['name' => 'tarif.update', 'display_name' => 'Mengubah Tarif', 'module' => 'tarif'],
            ['name' => 'tarif.delete', 'display_name' => 'Menghapus Tarif', 'module' => 'tarif'],

            ['name' => 'pengajuan.view', 'display_name' => 'Melihat Pengajuan', 'module' => 'pengajuan'],
            ['name' => 'pengajuan.create', 'display_name' => 'Membuat Pengajuan', 'module' => 'pengajuan'],
            ['name' => 'pengajuan.update', 'display_name' => 'Mengubah Pengajuan', 'module' => 'pengajuan'],
            ['name' => 'pengajuan.verify', 'display_name' => 'Memverifikasi Pengajuan', 'module' => 'pengajuan'],
            ['name' => 'pengajuan.delete', 'display_name' => 'Menghapus Pengajuan', 'module' => 'pengajuan'],

            ['name' => 'tagihan.view', 'display_name' => 'Melihat Tagihan', 'module' => 'tagihan'],
            ['name' => 'tagihan.create', 'display_name' => 'Membuat Tagihan', 'module' => 'tagihan'],
            ['name' => 'tagihan.update', 'display_name' => 'Mengubah Tagihan', 'module' => 'tagihan'],
            ['name' => 'tagihan.delete', 'display_name' => 'Menghapus Tagihan', 'module' => 'tagihan'],

            ['name' => 'pembayaran.view', 'display_name' => 'Melihat Pembayaran', 'module' => 'pembayaran'],
            ['name' => 'pembayaran.create', 'display_name' => 'Membuat Pembayaran', 'module' => 'pembayaran'],
            ['name' => 'pembayaran.update', 'display_name' => 'Mengubah Pembayaran', 'module' => 'pembayaran'],
            ['name' => 'pembayaran.verify', 'display_name' => 'Memverifikasi Pembayaran', 'module' => 'pembayaran'],
            ['name' => 'pembayaran.delete', 'display_name' => 'Menghapus Pembayaran', 'module' => 'pembayaran'],

            ['name' => 'setoran.view', 'display_name' => 'Melihat Setoran Petugas', 'module' => 'setoran'],
            ['name' => 'setoran.create', 'display_name' => 'Membuat Setoran', 'module' => 'setoran'],
            ['name' => 'setoran.update', 'display_name' => 'Mengubah Setoran', 'module' => 'setoran'],
            ['name' => 'setoran.verify', 'display_name' => 'Memverifikasi Setoran', 'module' => 'setoran'],

            ['name' => 'penugasan.view', 'display_name' => 'Melihat Penugasan Wilayah', 'module' => 'penugasan'],
            ['name' => 'penugasan.create', 'display_name' => 'Menambah Penugasan Wilayah', 'module' => 'penugasan'],
            ['name' => 'penugasan.update', 'display_name' => 'Mengubah Penugasan Wilayah', 'module' => 'penugasan'],
            ['name' => 'penugasan.delete', 'display_name' => 'Menghapus Penugasan Wilayah', 'module' => 'penugasan'],

            ['name' => 'laporan.view', 'display_name' => 'Melihat Laporan', 'module' => 'laporan'],
            ['name' => 'laporan.export', 'display_name' => 'Export Laporan', 'module' => 'laporan'],

            ['name' => 'audit.view', 'display_name' => 'Melihat Audit Log', 'module' => 'audit'],

            ['name' => 'wajib_retribusi.view', 'display_name' => 'Melihat Wajib Retribusi', 'module' => 'wajib_retribusi'],
            ['name' => 'wajib_retribusi.create', 'display_name' => 'Menambah Wajib Retribusi', 'module' => 'wajib_retribusi'],
            ['name' => 'wajib_retribusi.update', 'display_name' => 'Mengubah Wajib Retribusi', 'module' => 'wajib_retribusi'],
            ['name' => 'wajib_retribusi.delete', 'display_name' => 'Menghapus Wajib Retribusi', 'module' => 'wajib_retribusi'],

            ['name' => 'petugas.view', 'display_name' => 'Melihat Petugas', 'module' => 'petugas'],
            ['name' => 'petugas.create', 'display_name' => 'Menambah Petugas & Wilayah', 'module' => 'petugas'],
            ['name' => 'petugas.update', 'display_name' => 'Mengubah Petugas & Penugasan', 'module' => 'petugas'],
            ['name' => 'petugas.delete', 'display_name' => 'Menghapus Petugas', 'module' => 'petugas'],

            ['name' => 'banner.view', 'display_name' => 'Melihat Banner', 'module' => 'banner'],
            ['name' => 'banner.create', 'display_name' => 'Menambah Banner', 'module' => 'banner'],
            ['name' => 'banner.update', 'display_name' => 'Mengubah Banner', 'module' => 'banner'],
            ['name' => 'banner.delete', 'display_name' => 'Menghapus Banner', 'module' => 'banner'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}