<?php

namespace Database\Seeders;

use App\Models\Artikel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Memindahkan 4 artikel yang selama ini tertulis langsung di halaman /artikel
 * ke database, supaya muncul di dashboard (Kelola Artikel) dan bisa diedit/dihapus.
 * Foto dibaca dari public/images lalu disalin ke storage/app/public/artikel.
 *
 * Jalankan:  php artisan db:seed --class=ArtikelPublikSeeder
 */
class ArtikelPublikSeeder extends Seeder
{
    // Folder (di dalam public/) tempat file foto berada. Dicari berurutan.
    private array $folderSumber = ['images', 'assets', 'images/artikel', 'artikel', ''];

    public function run(): void
    {
        // Samakan nama kategori lama "Kegiatan" dengan nama di halaman publik
        Artikel::where('kategori', 'Kegiatan')->update(['kategori' => 'Kegiatan sekolah']);

        // GANTI tanggal sesuai tanggal kegiatan yang sebenarnya (halaman lama tidak mencantumkan tanggal).
        $data = [
            [
                'file'     => 'artikel 1.jpg',
                'kategori' => 'Kegiatan sekolah',
                'judul'    => 'Kegiatan Peresmian Jembatan Garuda 2026',
                'tanggal'  => '2026-09-28',
                'isi'      => 'Kegiatan ini merupakan peresmian Jembatan Garuda di RW. 08 Kelurahan Muarasari, Bogor Selatan. Acara dihadiri oleh pemerintah, TNI, kepolisian, dan masyarakat sebagai bentuk dukungan terhadap pembangunan fasilitas yang bermanfaat bagi warga.',
            ],
            [
                'file'     => 'artikel 2.jpg',
                'kategori' => 'Prestasi',
                'judul'    => 'Penyerahan Penghargaan Kepada Siswa Berprestasi',
                'tanggal'  => '2026-09-20',
                'isi'      => 'Kegiatan ini merupakan penyerahan penghargaan kepada siswa SMKN 4 BOGOR sebagai bentuk apresiasi atas kerja keras dan pencapaian mereka. Penghargaan tersebut diharapkan dapat menjadi motivasi bagi seluruh siswa untuk terus mengembangkan potensi dan meraih prestasi.',
            ],
            [
                'file'     => 'artikel 4.jpg',
                'kategori' => 'Kegiatan sekolah',
                'judul'    => 'Kegiatan Transforkr4b SMKN 4 Bogor',
                'tanggal'  => '2026-09-12',
                'isi'      => 'Kegiatan ini merupakan pertunjukan seni dan budaya siswa SMKN 4 BOGOR yang menampilkan tari tradisional dan seni vokal. Kegiatan tersebut bertujuan untuk mengembangkan kreativitas, bakat, kerja sama, serta menumbuhkan rasa cinta terhadap budaya Indonesia.',
            ],
            [
                'file'     => 'artikel 3.jpg',
                'kategori' => 'Pengumuman',
                'judul'    => 'Pengumuman/Rapat MBG SMKN 4 Bogor',
                'tanggal'  => '2026-09-05',
                'isi'      => 'Kegiatan ini merupakan rapat persiapan pelaksanaan Makan Bergizi Gratis (MBG) di SMKN 4 Bogor. Rapat dilakukan untuk membahas perencanaan dan kesiapan program agar dapat terlaksana dengan baik serta mendukung kesehatan dan kebutuhan gizi para siswa.',
            ],
        ];

        foreach ($data as $a) {
            $sumber = $this->cariFile($a['file']);

            if (! $sumber) {
                $this->command?->warn("Foto tidak ditemukan: {$a['file']} (artikel '{$a['judul']}' dilewati)");
                continue;
            }

            $tujuan = 'artikel/'
                . Str::slug(pathinfo($a['file'], PATHINFO_FILENAME)) . '.'
                . strtolower(pathinfo($a['file'], PATHINFO_EXTENSION));

            Storage::disk('public')->put($tujuan, File::get($sumber));

            Artikel::updateOrCreate(
                ['judul' => $a['judul']],
                [
                    'kategori' => $a['kategori'],
                    'tanggal'  => $a['tanggal'],
                    'isi'      => '<p>' . e($a['isi']) . '</p>',
                    'foto'     => $tujuan,
                ]
            );

            $this->command?->info("OK: {$a['judul']}");
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