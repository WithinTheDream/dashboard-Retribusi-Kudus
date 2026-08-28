<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nama_lengkap',
        'username',
        'email',
        'no_hp',
        'password',
        'role',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Role Helper
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->hasRole('admin')
            || $this->hasRole('admin_dinas');
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function isPetugas(): bool
    {
        return $this->hasRole('petugas');
    }

    public function isBendahara(): bool
    {
        return $this->hasRole('bendahara');
    }

    public function isPimpinan(): bool
    {
        return $this->hasRole('pimpinan');
    }

    public function isUser(): bool
    {
        return $this->hasRole('user');
    }
    public function roleRelation(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
    public function hasPermission(string $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }
        return $this->roleRelation?->hasPermission($permission) ?? false;
    }
    public function hasRole(string $role): bool
    {
        return $this->roleRelation?->name === $role
            || $this->role === $role;
    }

    public function penugasanWilayahs()
    {
        return $this->hasMany(PenugasanWilayah::class, 'user_id');
    }
}