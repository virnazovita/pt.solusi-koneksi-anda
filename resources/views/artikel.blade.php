@php
  // Artikel diambil dari database (yang kamu tambah/edit di dashboard admin).
  // Variabel $artikels dikirim oleh route /artikel di routes/web.php.
  $semuaArtikel   = collect($artikels ?? []);
  $daftarKategori = $semuaArtikel->pluck('kategori')->filter()->unique()->values();
  $artikel = $semuaArtikel->map(fn ($a) => [
      'img'      => $a->foto_url,
      'kategori' => $a->kategori,
      'judul'    => $a->judul,
      'ringkas'  => \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($a->isi))), 320),
  ])->all();
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Artikel / Informasi Terkini - PT Solusi Koneksi Anda</title>
@vite(['resources/css/app.css', 'resources/css/pages/artikel.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-slate-700">

  {{-- ====================== NAVBAR ====================== --}}
  @include('partials.navbar-publik', ['aktif' => 'artikel'])

  <div class="max-w-6xl mx-auto px-5">
    <p class="text-xs text-slate-400 mt-6">Home &gt; Artikel</p>
    <h1 class="text-2xl md:text-3xl font-bold navy-text mt-1">Artikel / Informasi Terkini</h1>
    <p class="text-sm text-slate-500 mt-2 max-w-2xl">
      Ikuti kabar terbaru, tips teknologi, pengumuman, dan berbagai kegiatan dari
      PT Solusi Koneksi Anda.
    </p>

    <div class="grid md:grid-cols-4 gap-8 mt-8 mb-14">
      {{-- ====================== SIDEBAR ====================== --}}
      <aside class="md:col-span-1 space-y-5">
        <div class="border border-slate-200 rounded-xl p-4 shadow-sm">
          <h3 class="text-sm font-bold navy-text mb-3">Cari Artikel</h3>
          <div class="relative">
            <input type="text" id="cari-artikel" placeholder="Masukkan kata kunci..." class="w-full text-xs border border-slate-200 rounded-lg pl-8 pr-3 py-2 focus:outline-none focus:ring-1 focus:ring-sky-400">
            <img src="https://api.iconify.design/mdi/magnify.svg?color=%2394a3b8" class="w-4 h-4 absolute left-2.5 top-1/2 -translate-y-1/2" alt="Cari">
          </div>
        </div>

        <div class="border border-slate-200 rounded-xl p-4 shadow-sm">
          <h3 class="text-sm font-bold navy-text mb-3">Kategori</h3>
          <div class="space-y-1">
            <a href="#" class="kategori-btn active" data-filter="Semua">Semua</a>
            {{-- Daftar kategori mengikuti kategori yang dipakai artikel di database --}}
            @foreach ($daftarKategori as $kat)
              <a href="#" class="kategori-btn" data-filter="{{ $kat }}">{{ $kat }}</a>
            @endforeach
          </div>
        </div>
      </aside>

      {{-- ====================== DAFTAR ARTIKEL ====================== --}}
      <div class="md:col-span-3 space-y-5">

        <div id="daftar-artikel">
        @foreach($artikel as $a)
          <article class="artikel-item flex flex-col sm:flex-row gap-4 border border-slate-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow mb-5" data-kategori="{{ $a['kategori'] }}">
            @if ($a['img'])
              <img src="{{ $a['img'] }}" class="w-full sm:w-40 aspect-square rounded-lg object-cover shrink-0" alt="{{ $a['judul'] }}">
            @endif
            <div>
              <span class="inline-block text-[10px] font-semibold text-sky-700 bg-sky-100 px-2 py-0.5 rounded-full">{{ $a['kategori'] }}</span>
              <h3 class="text-sm font-bold navy-text mt-2 leading-snug">{{ $a['judul'] }}</h3>
              <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">{{ $a['ringkas'] }}</p>
            </div>
          </article>
        @endforeach
        </div>
        @if (empty($artikel))
          <p class="text-sm text-slate-400 text-center py-10">Belum ada artikel.</p>
        @endif
        <p id="artikel-kosong" class="hidden text-sm text-slate-400 text-center py-10">Belum ada artikel untuk kategori ini.</p>

      </div>
    </div>
  </div>

  {{-- ====================== FOOTER ====================== --}}
  <div class="navy text-center text-[11px] tracking-wide text-slate-300 py-5">
    &copy; {{ date('Y') }} PT SOLUSI KONEKSI ANDA. Semua hak cipta dilindungi.
  </div>

  <script>
    var kategoriAktif = 'Semua';

    function terapkanFilter() {
      var kataKunci = document.getElementById('cari-artikel').value.trim().toLowerCase();
      var items = document.querySelectorAll('.artikel-item');
      var visibleCount = 0;
      items.forEach(function (item) {
        var cocokKategori = kategoriAktif === 'Semua' || item.getAttribute('data-kategori') === kategoriAktif;
        var judul = item.querySelector('h3').textContent.toLowerCase();
        var cocokKataKunci = kataKunci === '' || judul.indexOf(kataKunci) !== -1;
        var tampil = cocokKategori && cocokKataKunci;
        item.style.display = tampil ? '' : 'none';
        if (tampil) visibleCount++;
      });
      document.getElementById('artikel-kosong').classList.toggle('hidden', visibleCount > 0);
    }

    document.querySelectorAll('.kategori-btn').forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelectorAll('.kategori-btn').forEach(function (b) {
          b.classList.remove('active');
        });
        btn.classList.add('active');
        kategoriAktif = btn.getAttribute('data-filter');
        terapkanFilter();
      });
    });

    document.getElementById('cari-artikel').addEventListener('input', terapkanFilter);
  </script>
</body>
</html>