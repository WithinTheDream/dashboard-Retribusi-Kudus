<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WajibRetribusi extends Model
{
    protected $fillable = [
        'kode',
        'nama_lengkap',
        'nik',
        'nama_usaha',
        'alamat',
        'rt',
        'rw',
        'kecamatan_id',
        'desa_id',
        'lokasi_long',
        'lat',
        'no_hp',
        'jenis_retribusi_id',
        'nib',
        'dokumen_nib',
        'npwp',
        'dokumen_npwp',
        'status_pengajuan',
    ];

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    public function jenisRetribusi(): BelongsTo
    {
        return $this->belongsTo(JenisRetribusi::class);
    }
}