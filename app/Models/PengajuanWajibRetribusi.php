<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanWajibRetribusi extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jenisRetribusi()
    {
        return $this->belongsTo(JenisRetribusi::class);
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function dokumen()
    {
        return $this->hasMany(DokumenPengajuan::class, 'pengajuan_id');
    }

    public function histori()
    {
        return $this->hasMany(HistoriPengajuan::class, 'pengajuan_id');
    }

    public function wajibRetribusi()
    {
        return $this->hasOne(WajibRetribusi::class, 'pengajuan_id');
    }
}
