<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SetoranDetail extends Model
{
    protected $guarded = ['id'];

    public function setoran()
    {
        return $this->belongsTo(Setoran::class, 'setoran_id');
    }

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class, 'pembayaran_id');
    }
}
