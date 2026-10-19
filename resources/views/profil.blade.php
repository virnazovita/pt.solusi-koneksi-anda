@php
    $nama = $profil->nama_nama ?? null;
    $nama = $profil->nama_perusahaan ?: 'PT Solusi Koneksi Anda';

    $paragrafSejarah = array_filter(array_map('trim', preg_split('/\R{2,}/', (string) $profil->sejarah)));
    $adaFotoSejarah  = (bool) $profil->foto_sejarah_url;
    $adaFotoDirektur = (bool) $profil->foto_direktur_url;

    $struktur = $profil->daftar_struktur;
    $puncak   = $struktur[0] ?? null;
    $bawahan  = array_slice($struktur, 1);
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profil Perusahaan - {{ $nama }}</title>
@vite(['resources/css/app.css', 'resources/css/pages/profil.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-slate-700">

  {{-- ====================== NAVBAR ====================== --}}
  @include('partials.navbar-publik', ['aktif' => 'profil'])

  {{-- ====================== BANNER ====================== --}}
  <section class="relative overflow-hidden h-44 md:h-56" style="background: linear-gradient(135deg, #0a1f4d 0%, #0b3d78 55%, #2f5bb8 100%);">
    <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-5">
      <h1 class="text-white text-2xl md:text-3xl font-bold drop-shadow">Profil Perusahaan</h1>
      <p class="text-slate-200 text-xs md:text-sm mt-2">Home &gt; Profil</p>
      @if ($profil->slogan)
        <p class="text-blue-100 text-sm mt-3 italic">{{ $profil->slogan }}</p>
      @endif
    </div>
  </section>

  <div class="max-w-5xl mx-auto px-5">
    <p class="flex items-center gap-2 text-xs tracking-[0.2em] text-slate-400 mt-6 mb-10 uppercase">
      <span class="w-8 h-px bg-slate-300 inline-block"></span> {{ $nama }}
    </p>

    {{-- ====================== SEJARAH PERUSAHAAN ====================== --}}
    <h2 class="text-lg md:text-xl font-bold navy-text mb-4">Sejarah Perusahaan</h2>
    <div class="grid {{ $adaFotoSejarah ? 'md:grid-cols-3' : '' }} gap-6 items-start mb-16">
      <div class="{{ $adaFotoSejarah ? 'md:col-span-2' : '' }} navy text-white rounded-xl p-5 text-sm leading-relaxed">
        @foreach ($paragrafSejarah as $para)
          <p class="{{ $loop->first ? '' : 'mt-3' }}">{{ $para }}</p>
        @endforeach
      </div>
      @if ($adaFotoSejarah)
        <img src="{{ $profil->foto_sejarah_url }}" class="rounded-xl shadow-lg w-full aspect-[4/3] object-cover" alt="Sejarah {{ $nama }}">
      @endif
    </div>

    {{-- ====================== SAMBUTAN DIREKTUR ====================== --}}
    <h2 class="text-lg md:text-xl font-bold navy-text mb-4">Sambutan {{ $profil->jabatan_direktur ?: 'Direktur' }}</h2>
    <div class="grid {{ $adaFotoDirektur ? 'md:grid-cols-3' : '' }} gap-6 items-start mb-16">
      @if ($adaFotoDirektur)
        <img src="{{ $profil->foto_direktur_url }}" class="rounded-xl shadow w-48 md:w-56 object-cover" style="aspect-ratio:4/5" alt="Foto {{ $profil->nama_direktur }}">
      @endif
      <div class="{{ $adaFotoDirektur ? 'md:col-span-2' : '' }} border border-slate-200 rounded-xl p-5 text-sm text-slate-600 leading-relaxed shadow-sm">
        <p>{!! nl2br(e($profil->sambutan)) !!}</p>
        <p class="mt-4 font-bold navy-text">{{ $profil->nama_direktur }}</p>
        <p class="text-xs text-slate-400 uppercase">{{ $profil->jabatan_direktur }} {{ $nama }}</p>
      </div>
    </div>

    {{-- ====================== VISI, MISI, MOTO & SLOGAN ====================== --}}
    <div class="grid md:grid-cols-3 gap-4 mb-16 items-start">
      <div class="border border-slate-200 rounded-xl p-5 shadow-sm">
        <h3 class="font-bold navy-text mb-2">Visi:</h3>
        <p class="text-sm text-slate-600 leading-relaxed">{{ $profil->visi }}</p>
      </div>
      <div class="navy text-white rounded-xl p-5 shadow-sm">
        <h3 class="font-bold mb-2">Misi:</h3>
        <ol class="text-sm leading-relaxed list-decimal list-inside space-y-1">
          @foreach ($profil->daftar_misi as $misi)
            <li>{{ $misi }}</li>
          @endforeach
        </ol>
      </div>
      <div class="flex flex-col gap-4">
        @if ($profil->moto)
          <div class="border border-slate-200 rounded-xl p-5 shadow-sm">
            <h3 class="font-bold navy-text mb-2">Moto:</h3>
            <p class="text-sm text-slate-600 leading-relaxed italic">"{{ $profil->moto }}"</p>
          </div>
        @endif
        @if ($profil->slogan)
          <div class="navy text-white rounded-xl p-5 shadow-sm">
            <h3 class="font-bold mb-2">Slogan:</h3>
            <p class="text-sm leading-relaxed">{{ $profil->slogan }}</p>
          </div>
        @endif
      </div>
    </div>

    {{-- ====================== STRUKTUR ORGANISASI ====================== --}}
    @if ($puncak)
      <h2 class="text-lg md:text-xl font-bold navy-text text-center mb-8">Struktur Organisasi</h2>

      <div class="flex flex-col items-center mb-6">
        <div class="org-box active w-64">
          <p class="font-bold text-sm">{{ $puncak['jabatan'] }}</p>
          <p class="text-xs mt-0.5">{{ $puncak['nama'] }}</p>
        </div>
        @if (count($bawahan))
          <div class="w-px h-8 bg-slate-300"></div>
        @endif
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-16 max-w-3xl mx-auto">
        @foreach ($bawahan as $s)
          @php $aktif = $loop->iteration % 2 === 0; @endphp
          <div class="org-box {{ $aktif ? 'active' : '' }}">
            <p class="font-bold text-sm">{{ $s['jabatan'] }}</p>
            <p class="text-xs mt-0.5 {{ $aktif ? '' : 'text-slate-500' }}">{{ $s['nama'] }}</p>
          </div>
        @endforeach
      </div>
    @endif
  </div>

  {{-- ====================== FOOTER ====================== --}}
  <div class="navy text-center text-[11px] tracking-wide text-slate-300 py-5">
    &copy; {{ date('Y') }} {{ strtoupper($nama) }}. Semua hak cipta dilindungi.
  </div>

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