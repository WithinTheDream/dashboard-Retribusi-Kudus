<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisRetribusi extends Model
{
    protected $fillable = [
        'kode',
        'nama',
    ];

    public function tarifs(): HasMany
    {
        return $this->hasMany(Tarif::class);
    }

    public function wajibRetribusi(): HasMany
    {
        return $this->hasMany(WajibRetribusi::class);
    }
}