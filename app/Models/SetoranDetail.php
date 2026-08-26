<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SetoranDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'setoran_id',
        'pembayaran_id',
    ];

    public function setoran(): BelongsTo
    {
        return $this->belongsTo(Setoran::class, 'setoran_id');
    }

    public function pembayaran(): BelongsTo
    {
        return $this->belongsTo(Pembayaran::class, 'pembayaran_id');
    }
}
