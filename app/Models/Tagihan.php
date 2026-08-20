<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function wajibRetribusi()
    {
        return $this->belongsTo(WajibRetribusi::class, 'wajib_retribusi_id');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'tagihan_id');
    }
}
