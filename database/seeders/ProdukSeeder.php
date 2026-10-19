<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Mengisi Kelola Produk dengan 8 produk sesuai desain.
 * Foto asli dicari di folder public (lihat $folderSumber), lalu disalin
 * ke storage/app/public/produk supaya tampil lewat fitur Kelola Produk.
 *
 * Jalankan:  php artisan db:seed --class=ProdukSeeder
 */
class ProdukSeeder extends Seeder
{
    // Folder (di dalam public/) tempat file foto berada. Dicari berurutan.
    private array $folderSumber = ['images', 'assets', 'images/produk', 'produk', ''];

    public function run(): void
    {
        // Urutan sama seperti desain (dari atas ke bawah)
        $data = [
            ['file' => 'atribut sekolah.jpg', 'nama' => 'Atribut sekolah', 'deskripsi' => 'Atribut lengkap sekolah',   'kategori' => 'Atribut', 'harga' => 80000],
            ['file' => 'bet sekolah.jpg',     'nama' => 'Bet',             'deskripsi' => 'Bet khas sekolah',          'kategori' => 'Atribut', 'harga' => 30000],
            ['file' => 'wearpack PPLG.jpg',   'nama' => 'Wearpack PPLG',   'deskripsi' => 'Wearpack / baju praktik',   'kategori' => 'PPLG',    'harga' => 210000],
            ['file' => 'wearpack TPFL.jpg',   'nama' => 'Wearpack TPFL',   'deskripsi' => 'Wearpack / baju praktik',   'kategori' => 'TPFL',    'harga' => 220000],
            ['file' => 'wearpack TJKT.jpg',   'nama' => 'Wearpack TJKT',   'deskripsi' => 'Wearpack / baju praktik',   'kategori' => 'TJKT',    'harga' => 245000],
            ['file' => 'wearpack TO.jpg',     'nama' => 'Wearpack TO',     'deskripsi' => 'Wearpack / baju praktik',   'kategori' => 'TO',      'harga' => 255000],
            ['file' => 'baju batik.jpg',      'nama' => 'Baju batik',      'deskripsi' => 'Baju batik khas sekolah',   'kategori' => 'Seragam', 'harga' => 135000],
            ['file' => 'baju muslim.jpg',     'nama' => 'Baju muslim',     'deskripsi' => 'Baju muslim khas sekolah',  'kategori' => 'Seragam', 'harga' => 145000],
        ];

        // Daftar produk diurutkan dari id terbesar, jadi dimasukkan dari bawah ke atas
        // supaya urutan di halaman sama dengan desain.
        foreach (array_reverse($data) as $p) {
            $sumber = $this->cariFile($p['file']);

            if (! $sumber) {
                $this->command?->warn("Foto tidak ditemukan: {$p['file']} (produk '{$p['nama']}' dilewati)");
                continue;
            }

            $tujuan = 'produk/'
                . Str::slug(pathinfo($p['file'], PATHINFO_FILENAME)) . '.'
                . strtolower(pathinfo($p['file'], PATHINFO_EXTENSION));

            Storage::disk('public')->put($tujuan, File::get($sumber));

            Produk::updateOrCreate(
                ['nama' => $p['nama']],
                [
                    'deskripsi' => $p['deskripsi'],
                    'kategori'  => $p['kategori'],
                    'harga'     => $p['harga'],
                    'stok'      => 'Tersedia',
                    'foto'      => $tujuan,
                ]
            );

            $this->command?->info("OK: {$p['nama']}");
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