<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AuthAndRbacTest extends TestCase
{
    public function test_super_admin_has_full_permissions(): void
    {
        $user = User::where('username', 'admin')->first();

        if ($user) {
            $this->assertTrue($user->isSuperAdmin());
            $this->assertTrue($user->hasPermission('dashboard.view'));
            $this->assertTrue($user->hasPermission('wilayah.view'));
            $this->assertTrue($user->hasPermission('pengajuan.verify'));
            $this->assertTrue($user->hasPermission('setoran.verify'));
            $this->assertTrue($user->hasPermission('roles.view'));
            $this->assertTrue($user->hasPermission('users.view'));
        }
    }

    public function test_admin_dinas_permissions_and_scope(): void
    {
        $adminDinas = User::where('username', 'admin_dinas')->first();

        if ($adminDinas) {
            $this->assertTrue($adminDinas->isAdmin());
            $this->assertTrue($adminDinas->hasPermission('dashboard.view'));
            $this->assertTrue($adminDinas->hasPermission('wilayah.view'));
            $this->assertTrue($adminDinas->hasPermission('tarif.view'));
            $this->assertTrue($adminDinas->hasPermission('pengajuan.verify'));
            $this->assertTrue($adminDinas->hasPermission('tagihan.create'));
            $this->assertTrue($adminDinas->hasPermission('users.view'));
            // Admin Dinas cannot access roles management (Super Admin only)
            $this->assertFalse($adminDinas->hasPermission('roles.view'));
        }
    }

    public function test_bendahara_permissions_and_scope(): void
    {
        $bendahara = User::where('username', 'bendahara')->first();

        if ($bendahara) {
            $this->assertTrue($bendahara->isAdmin());
            $this->assertTrue($bendahara->hasPermission('dashboard.view'));
            $this->assertTrue($bendahara->hasPermission('tagihan.view'));
            $this->assertTrue($bendahara->hasPermission('pembayaran.view'));
            $this->assertTrue($bendahara->hasPermission('setoran.verify'));
            $this->assertTrue($bendahara->hasPermission('laporan.view'));
            // Bendahara cannot access master data or user management
            $this->assertFalse($bendahara->hasPermission('wilayah.view'));
            $this->assertFalse($bendahara->hasPermission('tarif.view'));
            $this->assertFalse($bendahara->hasPermission('users.view'));
            $this->assertFalse($bendahara->hasPermission('roles.view'));
        }
    }

    public function test_pimpinan_permissions_and_scope(): void
    {
        $pimpinan = User::where('username', 'pimpinan')->first();

        if ($pimpinan) {
            $this->assertTrue($pimpinan->isAdmin());
            $this->assertTrue($pimpinan->hasPermission('dashboard.view'));
            $this->assertTrue($pimpinan->hasPermission('laporan.view'));
            $this->assertTrue($pimpinan->hasPermission('laporan.export'));
            // Pimpinan is read-only / monitoring (no master CRUD, no user management)
            $this->assertFalse($pimpinan->hasPermission('wilayah.view'));
            $this->assertFalse($pimpinan->hasPermission('tarif.view'));
            $this->assertFalse($pimpinan->hasPermission('users.view'));
            $this->assertFalse($pimpinan->hasPermission('roles.view'));
        }
    }
}
