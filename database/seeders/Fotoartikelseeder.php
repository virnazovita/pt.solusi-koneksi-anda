<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FotoArtikelSeeder extends Seeder
{
    public function run(): void
    {
        // [potongan judul di database, awalan nama file di public/images (huruf kecil)]
        $pasangan = [
            ['%pemilihan ketua osis%', 'kegiatan pemilih'],
            ['%apel pembukaan%',       'kegiatan apel'],
            ['%krab4t%',               'kegiatan krabat'],
            ['%kerja bakti%',          'kegiatan kerja'],
        ];

        $semuaFile = scandir(public_path('images'));

        foreach ($pasangan as [$judulLike, $awalan]) {
            // Cari file yang namanya diawali teks di atas (huruf besar/kecil tidak masalah)
            $file = collect($semuaFile)
                ->filter(fn ($f) => str_starts_with(strtolower($f), $awalan))
                ->sort()
                ->first();

            if (! $file) {
                $this->command->warn("File foto untuk \"{$awalan}\" tidak ditemukan di public/images");
                continue;
            }

            $jumlah = DB::table('artikels')->where('judul', 'like', $judulLike)->update(['foto' => $file]);

            $this->command->info("{$file}  ->  {$jumlah} artikel diperbarui");
        }
    }
}