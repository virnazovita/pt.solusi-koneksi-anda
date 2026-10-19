<?php

namespace Database\Seeders;

use App\Models\Galeri;
use Illuminate\Database\Seeder;

class GaleriSeeder extends Seeder
{
    public function run(): void
    {
        // Foto diambil dari folder public/images (nama file harus sama persis).
        // Urutan sama dengan desain Figma "Kelola Galeri".
        $daftar = [
            [
                'judul'    => 'Pensi SMKN 4 BOGOR',
                'kategori' => 'Kegiatan',
                'tanggal'  => '2024-09-27',
                'foto'     => 'images/Kegiatan pensi.jpg',
            ],
            [
                'judul'    => 'Kegiatan SAGA',
                'kategori' => 'Kegiatan',
                'tanggal'  => '2024-06-17',
                'foto'     => 'images/Kegiatan Lomba SAGA.jpg',
            ],
            [
                'judul'    => 'Kegiatan Trans4krab',
                'kategori' => 'Kegiatan',
                'tanggal'  => '2024-11-16',
                'foto'     => 'images/kegiatan trans4krab.jpg',
            ],
            [
                'judul'    => 'Upacara Memperingati Kemerdekaan Hut RI Ke-81',
                'kategori' => 'Kegiatan',
                'tanggal'  => '2026-08-17',
                'foto'     => 'images/Kegiatan upacara hari anak.jpg',
            ],
        ];

        foreach ($daftar as $data) {
            // updateOrCreate: kalau dijalankan dua kali, data tidak dobel
            Galeri::updateOrCreate(['judul' => $data['judul']], $data);
        }
    }
}