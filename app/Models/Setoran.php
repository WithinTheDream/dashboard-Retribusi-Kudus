<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setoran extends Model
{
    protected $guarded = ['id'];

    public function petugas()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bendahara()
    {
        return $this->belongsTo(User::class, 'bendahara_id');
    }

    public function details()
    {
        return $this->hasMany(SetoranDetail::class, 'setoran_id');
    }
}
