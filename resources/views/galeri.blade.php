@php
    // Foto galeri diambil dari database (yang kamu tambah/edit di dashboard admin).
    // Yang terbaru tampil paling depan.
    $galeris = \App\Models\Galeri::orderByDesc('tanggal')->orderByDesc('id')->get();
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Galeri Kegiatan - PT Solusi Koneksi Anda</title>
@vite(['resources/css/app.css', 'resources/css/pages/galeri.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-slate-700">

  {{-- ====================== NAVBAR ====================== --}}
  @include('partials.navbar-publik', ['aktif' => 'galeri'])

  {{-- ====================== BANNER GRADASI BIRU ====================== --}}
  <section class="galeri-hero">
    <div class="max-w-6xl mx-auto px-5 py-10 md:py-14">
      <h1 class="text-white text-2xl md:text-3xl font-extrabold">Galeri Kegiatan</h1>
      <p class="text-white/90 font-semibold text-sm md:text-base -mt-1">PT SOLUSI KONEKSI ANDA</p>
      <p class="text-blue-100 text-xs md:text-sm mt-3 max-w-2xl leading-relaxed">
        Galeri foto berisi dokumentasi kegiatan perusahaan, mulai dari pemasangan dan layanan
        di lapangan, pelatihan tim, pertemuan dengan klien, hingga berbagai kegiatan lainnya.
      </p>
    </div>
  </section>

  {{-- ====================== GALERI FOTO (MASONRY) ====================== --}}
  <div class="max-w-6xl mx-auto px-5 py-10">
    <div class="masonry">
      @foreach ($galeris as $g)
        @if ($g->foto_url)
          <img src="{{ $g->foto_url }}" alt="{{ $g->judul }}" data-nama="{{ $g->judul }}">
        @endif
      @endforeach
    </div>

    @if ($galeris->isEmpty())
      <p class="text-sm text-slate-400 text-center py-10">Belum ada foto galeri.</p>
    @endif
  </div>

  {{-- ====================== LIGHTBOX / POPUP FOTO ====================== --}}
  <div id="lightbox" class="lightbox hidden">
    <button id="lightbox-close" class="lightbox-close" aria-label="Tutup">&times;</button>
    <div class="lightbox-content">
      <img id="lightbox-img" src="" alt="">
      <p id="lightbox-nama"></p>
    </div>
  </div>

  {{-- ====================== FOOTER ====================== --}}
  <div class="navy text-center text-[11px] tracking-wide text-slate-300 py-5">
    &copy; {{ date('Y') }} PT SOLUSI KONEKSI ANDA. Semua hak cipta dilindungi.
  </div>

  <script>
    var lightbox = document.getElementById('lightbox');
    var lightboxImg = document.getElementById('lightbox-img');
    var lightboxNama = document.getElementById('lightbox-nama');

    document.querySelectorAll('.masonry img').forEach(function (foto) {
      foto.addEventListener('click', function () {
        lightboxImg.src = foto.src;
        lightboxNama.textContent = foto.getAttribute('data-nama');
        lightbox.classList.remove('hidden');
      });
    });

    function tutupLightbox() {
      lightbox.classList.add('hidden');
      lightboxImg.src = '';
    }
    document.getElementById('lightbox-close').addEventListener('click', tutupLightbox);
    lightbox.addEventListener('click', function (e) {
      if (e.target === lightbox) tutupLightbox();
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') tutupLightbox();
    });
  </script>
</body>
</html>