<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    protected $fillable = ['judul', 'kategori', 'tanggal', 'foto', 'isi'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Dipakai di view: $artikel->foto_url
    public function getFotoUrlAttribute(): ?string
    {
        if (! $this->foto) {
            return null;
        }

        // Foto bawaan di folder public/images (contoh: "images/Kegiatan P5.jpg")
        if (str_starts_with($this->foto, 'images/')) {
            return asset(implode('/', array_map('rawurlencode', explode('/', $this->foto))));
        }

        // Foto hasil unggahan lewat form admin (storage/app/public/artikel/...)
        return asset('storage/' . $this->foto);
    }
}