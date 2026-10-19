<?php

namespace Database\Seeders;

use App\Models\Galeri;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Memindahkan 9 foto yang selama ini tertulis langsung di halaman /galeri
 * ke database, supaya muncul di dashboard (Kelola Galeri) dan bisa diedit/dihapus.
 * Foto dibaca dari public/images lalu disalin ke storage/app/public/galeri.
 *
 * Jalankan:  php artisan db:seed --class=GaleriPublikSeeder
 */
class GaleriPublikSeeder extends Seeder
{
    // Folder (di dalam public/) tempat file foto berada. Dicari berurutan.
    private array $folderSumber = ['images', 'assets', 'images/galeri', 'galeri', ''];

    public function run(): void
    {
        // Urutan = urutan di halaman publik lama (dari kiri-atas).
        // GANTI tanggal sesuai tanggal kegiatan yang sebenarnya (halaman lama tidak mencantumkan tanggal).
        $data = [
            ['file' => 'upacara 1.JPG',              'judul' => 'Upacara Bendera',                                                 'tanggal' => '2026-09-29'],
            ['file' => 'Kegiatan lomba 17.jpg',      'judul' => 'Lomba 17 Agustus',                                                'tanggal' => '2026-08-17'],
            ['file' => 'Kegiatan MPLS.jpg',          'judul' => 'MPLS (Masa Pengenalan Lingkungan Sekolah)',                       'tanggal' => '2026-07-14'],
            ['file' => 'Kegiatan P5.jpg',            'judul' => 'Kegiatan P5 (Projek Penguatan Profil Pelajar Pancasila)',         'tanggal' => '2026-05-22'],
            ['file' => 'Kegiatan Rapat Guru.jpg',    'judul' => 'Rapat Guru',                                                      'tanggal' => '2026-05-08'],
            ['file' => 'lomba futsal.jpg',           'judul' => 'Lomba Futsal',                                                    'tanggal' => '2026-04-24'],
            ['file' => 'Kegiatan Transforkr4b.jpg',  'judul' => 'Pertunjukan Seni dan Budaya',                                     'tanggal' => '2026-04-10'],
            ['file' => 'lomba PMR.jpg',              'judul' => 'Lomba PMR',                                                       'tanggal' => '2026-03-27'],
            ['file' => 'Halal Bihalal.jpg',          'judul' => 'Halal Bihalal',                                                   'tanggal' => '2026-03-13'],
        ];

        foreach ($data as $g) {
            $sumber = $this->cariFile($g['file']);

            if (! $sumber) {
                $this->command?->warn("Foto tidak ditemukan: {$g['file']} ('{$g['judul']}' dilewati)");
                continue;
            }

            $tujuan = 'galeri/'
                . Str::slug(pathinfo($g['file'], PATHINFO_FILENAME)) . '.'
                . strtolower(pathinfo($g['file'], PATHINFO_EXTENSION));

            Storage::disk('public')->put($tujuan, File::get($sumber));

            Galeri::updateOrCreate(
                ['judul' => $g['judul']],
                [
                    'kategori' => 'Kegiatan',
                    'tanggal'  => $g['tanggal'],
                    'foto'     => $tujuan,
                ]
            );

            $this->command?->info("OK: {$g['judul']}");
        }
    }

    private function cariFile(string $nama): ?string
    {
        foreach ($this->folderSumber as $folder) {
            $path = public_path(trim($folder . '/' . $nama, '/'));
            if (is_file($path)) {
                return $path;
            }
        }

        return null;
    }
}