<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DokumenPengajuan extends Model
{
    protected $guarded = ['id'];

    protected $appends = ['file_url'];

    public function getFileUrlAttribute(): string
    {
        if (empty($this->file_path)) {
            return '';
        }

        if (Str::startsWith($this->file_path, ['http://', 'https://'])) {
            return $this->file_path;
        }

        $cleanPath = ltrim($this->file_path, '/');
        if (!Str::startsWith($cleanPath, 'storage/')) {
            $cleanPath = 'storage/' . $cleanPath;
        }

        return asset($cleanPath);
    }
}
