<?php

namespace Database\Seeders;

use App\Models\Artikel;
use Illuminate\Database\Seeder;

class ArtikelSeeder extends Seeder
{
    public function run(): void
    {
        // Foto diambil dari folder public/images (nama file harus sama persis)
        $daftar = [
            [
                'judul'    => 'Pemungutan suara pemilihan ketua osis periode 2026-2027',
                'kategori' => 'Kegiatan',
                'tanggal'  => '2026-08-26',
                'foto'     => 'images/Kegiatan Pemilihan Ketos.jpg',
                'isi'      => 'Pemungutan suara pemilihan ketua OSIS periode 2026-2027 di SMKN 4 Bogor.',
            ],
            [
                'judul'    => 'Apel pembukaan LDKS SMKN 4 BOGOR',
                'kategori' => 'Kegiatan',
                'tanggal'  => '2026-08-20',
                'foto'     => 'images/Kegiatan Pembukaan LDKS.jpg',
                'isi'      => 'Apel pembukaan Latihan Dasar Kepemimpinan Siswa (LDKS) SMKN 4 Bogor.',
            ],
            [
                'judul'    => 'KRABAT SEHAT',
                'kategori' => 'Kegiatan',
                'tanggal'  => '2026-02-12',
                'foto'     => 'images/Krabat Sehat SMKN4.jpg',
                'isi'      => 'Kegiatan Krabat Sehat SMKN 4 Bogor.',
            ],
            [
                'judul'    => 'Kerja bakti di lingkungan SMKN 4 BOGOR',
                'kategori' => 'Kegiatan',
                'tanggal'  => '2025-09-19',
                'foto'     => 'images/Kegiatan Keja Bakti SMKN4.jpg',
                'isi'      => 'Kerja bakti membersihkan lingkungan SMKN 4 Bogor.',
            ],
        ];

        foreach ($daftar as $data) {
            // updateOrCreate: kalau dijalankan dua kali, data tidak dobel
            Artikel::updateOrCreate(['judul' => $data['judul']], $data);
        }
    }
}