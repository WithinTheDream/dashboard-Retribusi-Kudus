<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tarif extends Model
{
    protected $fillable = [
        'jenis_retribusi_id',
        'nominal',
        'satuan',
        'periode',
        'is_aktif',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'periode' => 'integer',
        'is_aktif' => 'boolean',
    ];

    public function jenisRetribusi(): BelongsTo
    {
        return $this->belongsTo(JenisRetribusi::class);
    }
}