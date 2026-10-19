<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilPerusahaan extends Model
{
    protected $table = 'profil_perusahaans';

    protected $fillable = [
        'nama_perusahaan', 'slogan', 'sejarah', 'foto_sejarah',
        'nama_direktur', 'jabatan_direktur', 'sambutan', 'foto_direktur',
        'visi', 'misi', 'moto', 'struktur', 'alamat', 'email', 'telepon',
    ];

    public function getFotoSejarahUrlAttribute(): ?string
    {
        return $this->foto_sejarah ? asset('storage/' . $this->foto_sejarah) : null;
    }

    public function getFotoDirekturUrlAttribute(): ?string
    {
        return $this->foto_direktur ? asset('storage/' . $this->foto_direktur) : null;
    }

    // Misi disimpan satu per baris, dipecah jadi array untuk tampilan publik
    public function getDaftarMisiAttribute(): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $this->misi))));
    }

    // Struktur: tiap baris "Jabatan | Nama", dipecah jadi array untuk tampilan publik
    public function getDaftarStrukturAttribute(): array
    {
        $hasil = [];
        foreach (preg_split('/\R/', (string) $this->struktur) as $baris) {
            if (trim($baris) === '') continue;
            [$jabatan, $nama] = array_pad(explode('|', $baris, 2), 2, '');
            $hasil[] = ['jabatan' => trim($jabatan), 'nama' => trim($nama)];
        }
        return $hasil;
    }
}