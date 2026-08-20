<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class, 'tagihan_id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function setoranDetail()
    {
        return $this->hasOne(SetoranDetail::class, 'pembayaran_id');
    }
}
