<?php

namespace Database\Seeders;

use App\Models\PesanKontak;
use Illuminate\Database\Seeder;

/**
 * Mengisi Kelola Kontak dengan 4 pesan contoh sesuai desain.
 * Jalankan:  php artisan db:seed --class=PesanKontakSeeder
 */
class PesanKontakSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama' => 'zovita', 'email' => 'vimazovita2604@gmail.com', 'subjek' => 'Semangat', 'pesan' => 'sekolah.nya keren bnget',          'waktu' => '2026-09-30 03:25:00'],
            ['nama' => 'qindy',  'email' => 'qindytalita@gmail.com',     'subjek' => 'Hebat',    'pesan' => 'sekolah.nya sejuk banget',        'waktu' => '2026-10-01 01:15:00'],
            ['nama' => 'randi',  'email' => 'randialathaya@gmail.com',   'subjek' => 'Semangat', 'pesan' => 'hebat banget anak-anak smkn4',    'waktu' => '2026-10-02 10:00:00'],
            ['nama' => 'anisa',  'email' => 'anisawahyuni7b@gmail.com',  'subjek' => 'Keren',    'pesan' => 'semangat terus smkn4',            'waktu' => '2026-10-03 14:26:00'],
        ];

        foreach ($data as $d) {
            $pesan = PesanKontak::firstOrNew(['email' => $d['email'], 'pesan' => $d['pesan']]);
            $pesan->nama   = $d['nama'];
            $pesan->subjek = $d['subjek'];
            $pesan->created_at = $d['waktu'];
            $pesan->updated_at = $d['waktu'];
            $pesan->save();

            $this->command?->info("OK: {$d['nama']}");
        }
    }
}