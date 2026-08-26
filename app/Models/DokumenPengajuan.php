<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumenPengajuan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_id',
        'jenis_dokumen',
        'file_path',
    ];

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(PengajuanWajibRetribusi::class, 'pengajuan_id');
    }
}
