<?php

namespace Database\Seeders;

use App\Models\ProfilPerusahaan;
use Illuminate\Database\Seeder;

class ProfilPerusahaanSeeder extends Seeder
{
    public function run(): void
    {
        ProfilPerusahaan::updateOrCreate(['id' => 1], [
            'nama_perusahaan'  => 'PT Solusi Koneksi Anda',
            'slogan'           => 'Terhubung Lebih Mudah, Usaha Lebih Maju.',
            'sejarah'          => "PT Solusi Koneksi Anda berdiri pada tahun 2015 sebagai penyedia solusi teknologi informasi dan jaringan untuk sekolah, instansi, dan usaha kecil menengah.\n\nSeiring bertambahnya kepercayaan pelanggan, kami mengembangkan layanan mulai dari instalasi jaringan, pembuatan website, hingga pendampingan teknis, dengan komitmen memberikan layanan yang cepat, jujur, dan terjangkau.",
            'nama_direktur'    => 'Budi Santoso, S.Kom.',
            'jabatan_direktur' => 'Direktur Utama',
            'sambutan'         => 'Selamat datang di website resmi PT Solusi Koneksi Anda. Kami percaya teknologi yang tepat dapat membantu setiap usaha bertumbuh. Karena itu kami hadir dengan layanan yang mudah dipahami, dikerjakan oleh tim yang berpengalaman, dan didukung layanan purna jual yang responsif.',
            'visi'             => 'Menjadi mitra teknologi informasi terpercaya yang membantu masyarakat dan dunia usaha terhubung dengan solusi digital yang andal.',
            'misi'             => "Menyediakan layanan jaringan dan teknologi informasi yang berkualitas.\nMemberikan pelayanan yang cepat, ramah, dan transparan.\nMengembangkan tim yang kompeten dan berintegritas.\nMenghadirkan solusi yang terjangkau dan sesuai kebutuhan pelanggan.\nMenjalin kerja sama jangka panjang dengan klien dan mitra.",
            'moto'             => 'Cepat, Tepat, Terpercaya.',
            'struktur'         => "Direktur Utama | Budi Santoso, S.Kom.\nManajer Teknis | Rina Sari, S.T.\nManajer Operasional | Andi Pratama\nManajer Pemasaran | Dewi Lestari",
            'alamat'           => 'Jl. Raya Pajajaran No. 88 F, Kota Bogor 16153',
            'email'            => 'solusikoneksianda04@gmail.com',
            'telepon'          => '085882394475',
        ]);
    }
}