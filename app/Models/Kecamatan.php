<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kecamatan extends Model
{
    protected $fillable = [
        'kecamatan',
    ];

    public function desas(): HasMany
    {
        return $this->hasMany(Desa::class, 'kec_id');
    }

    public function wajibRetribusi(): HasMany
    {
        return $this->hasMany(WajibRetribusi::class);
    }
}