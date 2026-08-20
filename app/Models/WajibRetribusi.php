<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WajibRetribusi extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanWajibRetribusi::class, 'pengajuan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function jenisRetribusi()
    {
        return $this->belongsTo(JenisRetribusi::class);
    }

    public function tagihans()
    {
        return $this->hasMany(Tagihan::class, 'wajib_retribusi_id');
    }

    public function kunjungan()
    {
        return $this->hasMany(KunjunganPenagihan::class, 'wajib_retribusi_id');
    }
}
