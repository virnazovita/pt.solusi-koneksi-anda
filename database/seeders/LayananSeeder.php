<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Seeder;

/**
 * Mengisi data awal Layanan PT Solusi Koneksi Anda (data contoh).
 * Foto dicari di public/images/layanan/<berkas>.jpg|jpeg|png|webp.
 * Kalau fotonya belum ada, layanan tetap dibuat tanpa foto.
 *
 * Jalankan:  php artisan db:seed --class=LayananSeeder
 */
class LayananSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data produk lama (data sekolah) supaya diganti layanan perusahaan
        Produk::query()->delete();

        // Urutan sama seperti tampilan (dari atas ke bawah)
        $data = [
            ['berkas' => 'instalasi-jaringan', 'nama' => 'Instalasi Jaringan Kantor',         'deskripsi' => 'Pemasangan jaringan LAN dan WiFi yang rapi dan stabil untuk kantor.', 'kategori' => 'Jaringan',        'harga' => 2500000],
            ['berkas' => 'wifi-area-luas',     'nama' => 'Pemasangan WiFi Area Luas',         'deskripsi' => 'Jaringan WiFi untuk sekolah, hotel, dan area publik.',                 'kategori' => 'Jaringan',        'harga' => 1800000],
            ['berkas' => 'website',            'nama' => 'Pembuatan Website Company Profile', 'deskripsi' => 'Website profesional yang dapat dikelola sendiri oleh pelanggan.',     'kategori' => 'Website',         'harga' => 3500000],
            ['berkas' => 'cctv',               'nama' => 'Pemasangan CCTV',                   'deskripsi' => 'Instalasi kamera pengawas lengkap dengan pemantauan jarak jauh.',      'kategori' => 'Perangkat Keras', 'harga' => 2000000],
            ['berkas' => 'pemeliharaan',       'nama' => 'Pemeliharaan Komputer & Jaringan',  'deskripsi' => 'Perawatan berkala agar perangkat dan jaringan selalu optimal.',       'kategori' => 'Pemeliharaan',    'harga' => 750000],
            ['berkas' => 'konsultasi',         'nama' => 'Konsultasi Teknologi Informasi',    'deskripsi' => 'Diskusi kebutuhan teknologi usaha Anda bersama tim ahli kami.',        'kategori' => 'Konsultasi',      'harga' => null],
        ];

        // Daftar diurutkan dari id terbesar, jadi dimasukkan dari bawah ke atas
        foreach (array_reverse($data) as $l) {
            $foto = $this->cariFoto($l['berkas']);

            Produk::create([
                'nama'      => $l['nama'],
                'deskripsi' => $l['deskripsi'],
                'kategori'  => $l['kategori'],
                'harga'     => $l['harga'],
                'foto'      => $foto,
            ]);

            $this->command?->info(($foto ? 'OK   ' : 'TANPA FOTO  ') . $l['nama']);
        }
    }

    // Foto dibaca langsung dari public/images/layanan (tanpa perlu public/storage)
    private function cariFoto(string $berkas): ?string
    {
        foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
            if (is_file(public_path("images/layanan/{$berkas}.{$ext}"))) {
                return "images/layanan/{$berkas}.{$ext}";
            }
        }

        return null;
    }
}