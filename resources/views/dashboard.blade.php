@php
    // ===== DATA HALAMAN: semuanya otomatis dari database =====
    $jumlahArtikel = \App\Models\Artikel::count();
    $jumlahLayanan = \App\Models\Produk::count();
    $jumlahGaleri  = \App\Models\Galeri::count();
    $jumlahPesan   = \App\Models\PesanKontak::count();

    // Aktivitas terkini: 3 data terbaru yang ditambah / diperbarui dari artikel, galeri, dan layanan
    $aktivitas = collect()
        ->merge(\App\Models\Artikel::latest('updated_at')->take(3)->get()->map(fn ($a) => ['teks' => 'Artikel ditambah / diperbarui', 'judul' => $a->judul, 'waktu' => $a->updated_at]))
        ->merge(\App\Models\Galeri::latest('updated_at')->take(3)->get()->map(fn ($g) => ['teks' => 'Foto galeri ditambah / diperbarui', 'judul' => $g->judul, 'waktu' => $g->updated_at]))
        ->merge(\App\Models\Produk::latest('updated_at')->take(3)->get()->map(fn ($l) => ['teks' => 'Layanan ditambah / diperbarui', 'judul' => $l->nama, 'waktu' => $l->updated_at]))
        ->sortByDesc('waktu')
        ->take(3)
        ->values()
        ->map(fn ($x) => ['teks' => $x['teks'], 'judul' => $x['judul'], 'waktu' => $x['waktu']->locale('id')->diffForHumans()])
        ->all();
    $jumlahAktivitas = count($aktivitas);
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin - PT Solusi Koneksi Anda</title>
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v=2">
  <style>
    /* Semua ukuran di bawah ini diukur dari desain Figma (lebar desain 1440px).
       "calc(var(--v) * 25)" artinya 25px di desain, lalu ikut membesar/mengecil sesuai lebar layar.
       Mau semuanya lebih kecil/besar? Ubah angka 1440 di --v (lebih besar = lebih kecil). */
    .dsb { --v: clamp(0.62px, calc(100vw / 1440), 1.5px); }

    .dsb-halo { font-size: calc(var(--v) * 25); font-weight: 700; margin: 0; color: #111827; }
    .dsb-sub { font-size: calc(var(--v) * 12); color: #6b7280; margin: calc(var(--v) * 8) 0 0; }

    /* 4 kartu ringkasan: lebar sama rata, tinggi mengikuti perbandingan desain (269 x 195) */
    .dsb-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: calc(var(--v) * 20); margin-top: calc(var(--v) * 58); }
    .dsb-stat { aspect-ratio: 269 / 195; background: #fff; border-radius: calc(var(--v) * 10); box-shadow: 0 3px 12px rgba(15,23,42,.12);
                padding: calc(var(--v) * 24) calc(var(--v) * 24) calc(var(--v) * 20); display: flex; flex-direction: column; }
    .dsb-stat-atas { display: flex; justify-content: space-between; align-items: flex-start; }
    .dsb-label { font-size: calc(var(--v) * 9.5); letter-spacing: .06em; text-transform: uppercase; color: #6b7280; font-weight: 500; margin-top: calc(var(--v) * 4); }
    .dsb-ikon { width: calc(var(--v) * 36); height: calc(var(--v) * 36); border-radius: calc(var(--v) * 8); display: flex; align-items: center; justify-content: center; font-size: calc(var(--v) * 13); font-weight: 700; }
    .dsb-ikon svg { width: calc(var(--v) * 18); height: calc(var(--v) * 18); }
    .dsb-ikon.biru { background: #dbeafe; color: #2563eb; }
    .dsb-ikon.abu { background: #e5e7eb; color: #374151; }
    .dsb-ikon.ungu { background: #ede9fe; color: #6d4bd8; }
    .dsb-ikon.merah { background: #fee2e2; color: #dc2626; }
    .dsb-angka { flex: 1; display: flex; align-items: center; font-size: calc(var(--v) * 40); font-weight: 700; color: #111827; line-height: 1; }

    /* Aktivitas terkini */
    .dsb-judul-seksi { font-size: calc(var(--v) * 15); font-weight: 700; margin: calc(var(--v) * 24) 0 calc(var(--v) * 36); color: #111827; }
    .dsb-panel { background: #fff; border: 1px solid #e5e7eb; border-radius: calc(var(--v) * 10); box-shadow: 0 3px 12px rgba(15,23,42,.08);
                 padding: calc(var(--v) * 43) 4.7% calc(var(--v) * 35); }
    .dsb-alur { display: grid; grid-template-columns: 37.2% 1fr 37.2%; align-items: center; row-gap: calc(var(--v) * 92); }
    .dsb-aktif { aspect-ratio: 372 / 133; min-height: calc(var(--v) * 110); background: #eeeeee; border-radius: calc(var(--v) * 8); box-shadow: 0 4px 10px rgba(15,23,42,.14);
                 padding: calc(var(--v) * 26) calc(var(--v) * 18) calc(var(--v) * 16); }
    .dsb-aktif .teks { font-size: calc(var(--v) * 15); color: #8b8b8b; }
    .dsb-aktif .judul { font-size: calc(var(--v) * 15); font-weight: 600; color: #1d4ed8; margin: calc(var(--v) * 14) 0 calc(var(--v) * 14); }
    .dsb-aktif .waktu { font-size: calc(var(--v) * 11); color: #a3a3a3; }
    .dsb-tengah { display: flex; flex-direction: column; align-items: center; gap: calc(var(--v) * 20); color: #111827; }
    .dsb-tengah svg { width: calc(var(--v) * 22); height: calc(var(--v) * 22); }
    .dsb-kiri-kanan { display: flex; gap: calc(var(--v) * 100); }
    .dsb-bawah { grid-column: 1 / -1; justify-self: center; width: 37.2%; }
    .dsb-kosong { text-align: center; color: #9ca3af; font-size: 14px; padding: 30px 0; }

    @media (max-width: 1100px) { .dsb-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 760px) {
      .dsb-grid { grid-template-columns: 1fr; }
      .dsb-stat { aspect-ratio: auto; min-height: 130px; }
      .dsb-alur { grid-template-columns: 1fr; row-gap: 16px; }
      .dsb-aktif, .dsb-bawah { width: 100%; aspect-ratio: auto; }
      .dsb-tengah { display: none; }
    }
  </style>
</head>
<body>

<div class="layout">

  @include('admin-sidebar')

  <div class="main">

    <header class="topbar">
      <span class="aktif">Dashboard</span>
    </header>

    <section class="content dsb">

      <h1 class="dsb-halo">Halo, Admin</h1>
      <p class="dsb-sub">Berikut ringkasan aktivitas website perusahaan hari ini</p>

      {{-- ===== 4 kartu ringkasan ===== --}}
      <div class="dsb-grid">

        <div class="dsb-stat">
          <div class="dsb-stat-atas">
            <span class="dsb-label">Jumlah Artikel</span>
            <span class="dsb-ikon biru">Aa</span>
          </div>
          <div class="dsb-angka">{{ $jumlahArtikel }}</div>
        </div>

        <div class="dsb-stat">
          <div class="dsb-stat-atas">
            <span class="dsb-label">Jumlah Layanan</span>
            <span class="dsb-ikon abu">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11v2l14 5V6L3 11Z"/><path d="M7 14v4a2 2 0 0 0 4 0"/></svg>
            </span>
          </div>
          <div class="dsb-angka">{{ $jumlahLayanan }}</div>
        </div>

        <div class="dsb-stat">
          <div class="dsb-stat-atas">
            <span class="dsb-label">Jumlah Galeri</span>
            <span class="dsb-ikon biru">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-5-5L5 21"/></svg>
            </span>
          </div>
          <div class="dsb-angka">{{ $jumlahGaleri }}</div>
        </div>

        <div class="dsb-stat">
          <div class="dsb-stat-atas">
            <span class="dsb-label">Pesan Masuk</span>
            <span class="dsb-ikon merah">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
            </span>
          </div>
          <div class="dsb-angka">{{ $jumlahPesan }}</div>
        </div>

      </div>

      {{-- ===== Aktivitas terkini ===== --}}
      <h2 class="dsb-judul-seksi">Aktivitas Terkini</h2>

      <div class="dsb-panel">
        @if ($jumlahAktivitas === 0)
          <div class="dsb-kosong">Belum ada aktivitas.</div>
        @else
          <div class="dsb-alur">

            {{-- Kartu 1 (kiri) --}}
            <div class="dsb-aktif">
              <div class="teks">{{ $aktivitas[0]['teks'] }}</div>
              <div class="judul">"{{ $aktivitas[0]['judul'] }}"</div>
              <div class="waktu">{{ $aktivitas[0]['waktu'] }}</div>
            </div>

            {{-- Panah tengah --}}
            <div class="dsb-tengah">
              @if ($jumlahAktivitas >= 2)
                <div class="dsb-kiri-kanan">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12H4M10 6l-6 6 6 6"/></svg>
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12h16M14 6l6 6-6 6"/></svg>
                </div>
              @endif
              @if ($jumlahAktivitas >= 3)
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 4v16M6 14l6 6 6-6"/></svg>
              @endif
            </div>

            {{-- Kartu 2 (kanan) --}}
            @if ($jumlahAktivitas >= 2)
              <div class="dsb-aktif">
                <div class="teks">{{ $aktivitas[1]['teks'] }}</div>
                <div class="judul">"{{ $aktivitas[1]['judul'] }}"</div>
                <div class="waktu">{{ $aktivitas[1]['waktu'] }}</div>
              </div>
            @endif

            {{-- Kartu 3 (bawah, tengah) --}}
            @if ($jumlahAktivitas >= 3)
              <div class="dsb-aktif dsb-bawah">
                <div class="teks">{{ $aktivitas[2]['teks'] }}</div>
                <div class="judul">"{{ $aktivitas[2]['judul'] }}"</div>
                <div class="waktu">{{ $aktivitas[2]['waktu'] }}</div>
              </div>
            @endif

          </div>
        @endif
      </div>

    </section>
  </div>

</div>

</body>
</html>