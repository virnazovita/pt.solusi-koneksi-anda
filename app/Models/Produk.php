<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

// Model ini dipakai untuk data "Layanan" (tabel produks).
class Produk extends Model
{
    protected $fillable = ['nama', 'deskripsi', 'kategori', 'harga', 'foto'];

    // Harga siap tampil, contoh: Rp 2.500.000. Kalau harga kosong: "Hubungi kami"
    protected function hargaRupiah(): Attribute
    {
        return Attribute::get(fn () => $this->harga === null
            ? 'Hubungi kami'
            : 'Rp ' . number_format((int) $this->harga, 0, ',', '.'));
    }

    // Alamat gambar yang siap dipakai di <img>.
    // Foto bawaan (dari seeder) ada di public/images/..., foto unggahan admin ada di storage.
    protected function fotoUrl(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->foto) {
                return null;
            }

            return str_starts_with($this->foto, 'images/')
                ? asset($this->foto)
                : asset('storage/' . $this->foto);
        });
    }
}