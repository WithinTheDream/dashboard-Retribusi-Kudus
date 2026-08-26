<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KunjunganPenagihan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wajib_retribusi_id',
        'waktu_kunjungan',
        'hasil_kunjungan',
        'catatan',
        'lat',
        'long',
    ];

    protected $casts = [
        'waktu_kunjungan' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function wajibRetribusi(): BelongsTo
    {
        return $this->belongsTo(WajibRetribusi::class, 'wajib_retribusi_id');
    }
}
