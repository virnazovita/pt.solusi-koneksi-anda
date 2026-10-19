@php
    $menu = [
        ['Home', '/'],
        ['Profil', '/profil'],
        ['Layanan', '/layanan'],
        ['Artikel', '/artikel'],
        ['Galeri', '/galeri'],
        ['Kontak', '/kontak'],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Layanan - PT Solusi Koneksi Anda</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: "Poppins", "Segoe UI", system-ui, sans-serif; color: #111827; background: #fff; }
    a { text-decoration: none; color: inherit; }

    /* ===== Navbar ===== */
    .nav { background: #0f2a5e; color: #fff; box-shadow: 0 2px 8px rgba(0,0,0,.18); }
    .nav-in { max-width: 1100px; margin: 0 auto; padding: 0 20px; height: 68px; display: flex; align-items: center; justify-content: space-between; gap: 20px; }
    .brand { display: flex; align-items: center; gap: 12px; font-weight: 700; font-size: 17px; white-space: nowrap; }
    .brand img { width: 30px; height: 30px; object-fit: contain; }
    .menu { display: flex; gap: 26px; }
    .menu a { padding: 6px 0; font-size: 14.5px; font-weight: 500; border-bottom: 2px solid transparent; }
    .menu a:hover { border-bottom-color: rgba(255,255,255,.5); }
    .menu a.aktif { border-bottom-color: #fff; font-weight: 600; }
    .btn-login { padding: 10px 20px; border-radius: 8px; background: #1e4fa0; font-size: 14px; font-weight: 600; white-space: nowrap; }
    .btn-login:hover { background: #2559b3; }

    /* ===== Hero ===== */
    .hero { background: linear-gradient(135deg, #1e3a8a 0%, #2f5bb8 55%, #3b78de 100%); color: #fff; text-align: center; padding: 56px 20px 52px; }
    .hero h1 { font-size: 34px; font-weight: 700; line-height: 1.2; }
    .hero h2 { margin-top: 10px; font-size: 20px; font-weight: 700; }
    .hero p { max-width: 680px; margin: 16px auto 0; font-size: 15px; line-height: 1.75; }

    /* ===== Daftar layanan (3 kolom, baris terakhir di tengah) ===== */
    .layanan { max-width: 1100px; margin: 0 auto; padding: 64px 20px 80px; display: flex; flex-wrap: wrap; justify-content: center; gap: 44px 40px; }
    .kartu { width: calc((100% - 80px) / 3); background: #fff; border: 1px solid #e6e9f1; border-radius: 8px; padding: 16px; box-shadow: 0 2px 10px rgba(15, 31, 77, .06); display: flex; flex-direction: column; }
    .kartu .foto { aspect-ratio: 4 / 3; background: #eef1f8; border-radius: 6px; display: flex; align-items: center; justify-content: center; overflow: hidden; }
    .kartu .foto img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .kartu .foto-kosong { font-size: 44px; font-weight: 700; color: #9aa8c7; }
    .kartu h3 { margin-top: 14px; font-size: 15px; font-weight: 600; color: #111827; }
    .kartu .kategori { margin-top: 2px; font-size: 12.5px; color: #6b7280; }
    .kartu .deskripsi { margin-top: 8px; font-size: 13px; line-height: 1.6; color: #4b5563; }
    .kartu .harga { margin-top: 10px; font-size: 14px; font-weight: 600; color: #1e4fa0; }
    .kartu .btn { margin-top: auto; }
    .kartu .btn a { display: block; margin-top: 14px; padding: 10px 0; border-radius: 6px; background: #1e4fa0; color: #fff; text-align: center; font-size: 13px; font-weight: 600; }
    .kartu .btn a:hover { background: #17408a; }
    .kosong { width: 100%; text-align: center; color: #9ca3af; font-size: 14px; padding: 40px 0; }

    /* ===== Footer ===== */
    .footer { background: #0f2a5e; color: #fff; text-align: center; padding: 26px 20px; font-size: 13px; }

    @media (max-width: 900px) {
      .kartu { width: calc((100% - 40px) / 2); }
    }
    @media (max-width: 860px) {
      .nav-in { height: auto; padding: 12px 20px; flex-wrap: wrap; justify-content: center; }
      .menu { flex-wrap: wrap; justify-content: center; gap: 8px 18px; order: 3; width: 100%; }
    }
    @media (max-width: 560px) {
      .kartu { width: 100%; }
    }
  </style>
</head>
<body>

  {{-- ===== Navbar ===== --}}
  @include('partials.navbar-publik', ['aktif' => 'layanan'])

  {{-- ===== Hero ===== --}}
  <section class="hero">
    <h1>Layanan</h1>
    <h2>PT SOLUSI KONEKSI ANDA</h2>
    <p>Kami menyediakan layanan jaringan, pembuatan website, perangkat keras, hingga konsultasi teknologi informasi untuk membantu usaha Anda terhubung dan berkembang.</p>
  </section>

  {{-- ===== Daftar layanan ===== --}}
  <main class="layanan">
    @forelse ($layanans as $l)
      <article class="kartu">
        <div class="foto">
          @if ($l->foto_url)
            <img src="{{ $l->foto_url }}" alt="{{ $l->nama }}">
          @else
            <span class="foto-kosong">{{ mb_strtoupper(mb_substr($l->nama, 0, 1)) }}</span>
          @endif
        </div>
        <h3>{{ $l->nama }}</h3>
        <p class="kategori">{{ $l->kategori }}</p>
        @if ($l->deskripsi)
          <p class="deskripsi">{{ $l->deskripsi }}</p>
        @endif
        <p class="harga">{{ $l->harga === null ? 'Harga sesuai kebutuhan' : 'Mulai dari ' . $l->harga_rupiah }}</p>
        <div class="btn"><a href="{{ url('/kontak') }}">Hubungi Kami</a></div>
      </article>
    @empty
      <p class="kosong">Belum ada layanan.</p>
    @endforelse
  </main>

  {{-- ===== Footer ===== --}}
  <footer class="footer">
    &copy; {{ date('Y') }} PT Solusi Koneksi Anda. Semua Hak Cipta Dilindungi.
  </footer>

</body>
</html>