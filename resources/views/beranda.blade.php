@php
    $nama = $profil->nama_perusahaan ?: 'PT Solusi Koneksi Anda';

    $paragraf       = array_values(array_filter(array_map('trim', preg_split('/\R{2,}/', (string) $profil->sejarah))));
    $ringkasTentang = $paragraf[0] ?? '';

    // Foto latar hero (public/images/hero.jpg). Kalau belum ada, pakai gradasi biru.
    $adaHero = is_file(public_path('images/hero.jpg'));
    $heroBg  = $adaHero
        ? "linear-gradient(90deg, rgba(10,31,77,.94) 0%, rgba(10,31,77,.82) 40%, rgba(11,61,120,.50) 100%), url('" . asset('images/hero.jpg') . "') center / cover no-repeat"
        : 'linear-gradient(135deg, #0a1f4d 0%, #0b3d78 50%, #2f5bb8 100%)';

    // Angka berikut data contoh, ubah sesuai data perusahaan
    $statistik = [
        ['icon' => 'mdi/account-group',   'color' => '#2563eb', 'bg' => 'bg-blue-100',    'angka' => '150+', 'label' => 'Klien terlayani'],
        ['icon' => 'mdi/briefcase-check', 'color' => '#059669', 'bg' => 'bg-emerald-100', 'angka' => '300+', 'label' => 'Proyek selesai'],
        ['icon' => 'mdi/account-tie',     'color' => '#ea580c', 'bg' => 'bg-orange-100',  'angka' => '25',   'label' => 'Tim ahli'],
        ['icon' => 'mdi/calendar-clock',  'color' => '#7c3aed', 'bg' => 'bg-purple-100',  'angka' => '10+',  'label' => 'Tahun pengalaman'],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $nama }}</title>
@vite(['resources/css/app.css', 'resources/css/pages/home.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-slate-700">

  {{-- ====================== NAVBAR ====================== --}}
  @include('partials.navbar-publik', ['aktif' => 'beranda'])

  {{-- ====================== HERO ====================== --}}
  <section id="beranda" class="relative overflow-hidden" style="background: {!! $heroBg !!};">
    <div class="max-w-6xl mx-auto px-5 pt-16 pb-28 md:pt-24 md:pb-32">
      <div class="text-white max-w-xl">
        <p class="text-xs md:text-sm tracking-[0.2em] uppercase text-sky-200 font-semibold">Selamat datang di</p>
        <h1 class="text-3xl md:text-5xl font-extrabold leading-tight mt-2 drop-shadow">{{ $nama }}</h1>
        @if ($profil->slogan)
          <p class="mt-3 text-base md:text-lg text-sky-100 italic">{{ $profil->slogan }}</p>
        @endif
        @if ($ringkasTentang)
          <p class="mt-4 text-xs md:text-sm text-slate-100 leading-relaxed">{{ \Illuminate\Support\Str::limit($ringkasTentang, 220) }}</p>
        @endif
        <div class="mt-6 flex flex-wrap gap-3">
          <a href="{{ url('/layanan') }}" class="px-5 py-2.5 rounded-lg bg-white text-sm font-semibold navy-text hover:bg-slate-100">Lihat Layanan</a>
          <a href="{{ url('/kontak') }}" class="px-5 py-2.5 rounded-lg border border-white/60 text-sm font-semibold text-white hover:bg-white/10">Hubungi Kami</a>
        </div>
      </div>
    </div>
  </section>

  {{-- ====================== STATISTIK ====================== --}}
  <section class="max-w-5xl mx-auto px-5 -mt-10 relative z-10">
    <div class="bg-white rounded-2xl shadow-lg grid grid-cols-2 md:grid-cols-4 gap-4 p-5">
      @foreach($statistik as $s)
        <div class="flex items-center gap-3">
          <span class="w-12 h-12 rounded-full {{ $s['bg'] }} flex items-center justify-center shrink-0">
            <img src="https://api.iconify.design/{{ $s['icon'] }}.svg?color=%23{{ ltrim($s['color'],'#') }}" class="w-6 h-6" alt="{{ $s['label'] }}">
          </span>
          <div>
            <p class="text-lg font-bold navy-text leading-none">{{ $s['angka'] }}</p>
            <p class="text-xs text-slate-500 mt-1">{{ $s['label'] }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </section>

  {{-- ====================== LAYANAN KAMI (dari database) ====================== --}}
  <section id="layanan" class="max-w-6xl mx-auto px-5 mt-16">
    <h2 class="text-xl md:text-2xl font-bold navy-text">Layanan Kami</h2>
    <p class="text-xs text-slate-400 mt-1">Solusi yang kami tawarkan untuk kebutuhan usaha Anda</p>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-5 mt-6">
      @forelse($layanans as $l)
        <article class="rounded-xl overflow-hidden shadow hover:shadow-lg transition-shadow bg-white">
          @if ($l->foto_url)
            <img src="{{ $l->foto_url }}" class="w-full object-cover" style="aspect-ratio:4/3" alt="{{ $l->nama }}">
          @else
            <div class="w-full flex items-center justify-center bg-slate-100 text-4xl font-bold text-slate-300" style="aspect-ratio:4/3">{{ mb_strtoupper(mb_substr($l->nama, 0, 1)) }}</div>
          @endif
          <div class="p-3">
            <span class="inline-block text-[10px] font-semibold text-sky-700 bg-sky-100 px-2 py-0.5 rounded-full">{{ $l->kategori }}</span>
            <p class="text-xs md:text-sm font-semibold navy-text leading-snug mt-2">{{ $l->nama }}</p>
            @if ($l->deskripsi)
              <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ \Illuminate\Support\Str::limit($l->deskripsi, 80) }}</p>
            @endif
          </div>
        </article>
      @empty
        <p class="col-span-full text-sm text-slate-400 text-center py-8">Belum ada layanan.</p>
      @endforelse
    </div>

    <div class="mt-6 text-right">
      <a href="{{ url('/layanan') }}" class="text-sm font-semibold navy-text hover:underline">Lihat semua layanan &rarr;</a>
    </div>
  </section>

  {{-- ====================== INFORMASI TERKINI (dari database) ====================== --}}
  <section id="berita" class="max-w-6xl mx-auto px-5 mt-16">
    <h2 class="text-xl md:text-2xl font-bold navy-text">Informasi Terkini</h2>
    <p class="text-xs text-slate-400 mt-1 mb-6">Beranda &gt; Artikel</p>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
      @forelse($artikels as $a)
        <article class="rounded-xl overflow-hidden shadow hover:shadow-lg transition-shadow bg-white">
          @if ($a->foto_url)
            <img src="{{ $a->foto_url }}" class="w-full object-cover" style="aspect-ratio:4/3" alt="{{ $a->judul }}">
          @else
            <div class="w-full bg-slate-100" style="aspect-ratio:4/3"></div>
          @endif
          <div class="p-3">
            <span class="inline-block text-[10px] font-semibold text-sky-700 bg-sky-100 px-2 py-0.5 rounded-full">{{ $a->kategori }}</span>
            <p class="text-xs font-semibold navy-text leading-snug mt-2">{{ $a->judul }}</p>
            @if ($a->tanggal)
              <p class="text-[11px] text-slate-400 mt-1">{{ $a->tanggal->format('d/m/Y') }}</p>
            @endif
          </div>
        </article>
      @empty
        <p class="col-span-full text-sm text-slate-400 text-center py-8">Belum ada artikel.</p>
      @endforelse
    </div>

    <div class="mt-6 text-right">
      <a href="{{ url('/artikel') }}" class="text-sm font-semibold navy-text hover:underline">Lihat semua artikel &rarr;</a>
    </div>
  </section>

  {{-- ====================== TENTANG PERUSAHAAN ====================== --}}
  <section id="tentang" class="max-w-6xl mx-auto px-5 mt-16 grid md:grid-cols-2 gap-10 items-center">
    @if ($profil->foto_sejarah_url)
      <img src="{{ $profil->foto_sejarah_url }}" class="rounded-2xl shadow-lg w-full aspect-video object-cover" alt="Tentang {{ $nama }}">
    @else
      <div class="rounded-2xl shadow-lg navy text-white p-8 md:p-10">
        <h3 class="text-sm font-bold tracking-widest uppercase text-sky-200">Visi Kami</h3>
        <p class="mt-3 text-base md:text-lg leading-relaxed">{{ $profil->visi }}</p>
      </div>
    @endif
    <div>
      <h2 class="text-xl md:text-2xl font-bold navy-text">Tentang Perusahaan</h2>
      @foreach (array_slice($paragraf, 0, 2) as $p)
        <p class="text-sm text-slate-600 mt-3 leading-relaxed">{{ $p }}</p>
      @endforeach
      <a href="{{ url('/profil') }}" class="inline-block mt-5 px-5 py-2.5 rounded-lg navy text-white text-sm font-semibold hover:opacity-90">Selengkapnya</a>
    </div>
  </section>

  {{-- ====================== FOOTER / HUBUNGI KAMI ====================== --}}
  <footer id="kontak" class="navy text-white mt-16">
    <div class="max-w-6xl mx-auto px-5 py-10 grid md:grid-cols-3 gap-8">
      <div>
        <h3 class="font-bold mb-3">Hubungi Kami</h3>
        @if ($profil->alamat)
          <div class="flex items-start gap-2 text-sm text-slate-200 mb-2">
            <img src="https://api.iconify.design/mdi/map-marker.svg?color=%23ffffff" class="w-5 h-5 mt-0.5" alt="Alamat">
            <span>{{ $profil->alamat }}</span>
          </div>
        @endif
        @if ($profil->telepon)
          <div class="flex items-center gap-2 text-sm text-slate-200 mb-2">
            <img src="https://api.iconify.design/mdi/phone.svg?color=%23ffffff" class="w-5 h-5" alt="Telepon">
            <span>{{ $profil->telepon }}</span>
          </div>
        @endif
        @if ($profil->email)
          <div class="flex items-center gap-2 text-sm text-slate-200">
            <img src="https://api.iconify.design/mdi/email.svg?color=%23ffffff" class="w-5 h-5" alt="Email">
            <span>{{ $profil->email }}</span>
          </div>
        @endif
      </div>

      <div>
        <h3 class="font-bold mb-3">Tautan</h3>
        <ul class="space-y-2 text-sm text-slate-200">
          <li><a href="#layanan" class="hover:text-sky-300">Layanan Kami</a></li>
          <li><a href="#berita" class="hover:text-sky-300">Informasi Terkini</a></li>
          <li><a href="#tentang" class="hover:text-sky-300">Tentang Perusahaan</a></li>
        </ul>
      </div>

      <div>
        <h3 class="font-bold mb-3">Media Sosial</h3>
        <div class="flex gap-3">
          <img src="https://api.iconify.design/mdi/instagram.svg?color=%23ffffff" class="w-6 h-6" alt="Instagram">
          <img src="https://api.iconify.design/mdi/facebook.svg?color=%23ffffff" class="w-6 h-6" alt="Facebook">
          <img src="https://api.iconify.design/mdi/youtube.svg?color=%23ffffff" class="w-6 h-6" alt="YouTube">
        </div>
      </div>
    </div>
    <div class="border-t border-white/20 text-center text-[11px] tracking-wide text-slate-300 py-4">
      &copy; {{ date('Y') }} {{ strtoupper($nama) }}. Semua hak cipta dilindungi.
    </div>
  </footer>

<script>
  document.querySelectorAll('.nav-link').forEach(function (link) {
    link.addEventListener('click', function () {
      document.querySelectorAll('.nav-link').forEach(function (l) {
        l.classList.remove('active');
      });
      link.classList.add('active');
    });
  });
</script>
</body>
</html>