<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoriPengajuan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_id',
        'status',
        'catatan',
        'user_id',
    ];

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(PengajuanWajibRetribusi::class, 'pengajuan_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
