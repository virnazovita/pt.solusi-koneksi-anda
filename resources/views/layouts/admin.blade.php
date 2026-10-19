<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Admin') - PT Solusi Koneksi Anda</title>
  <style>
    /* Gaya dasar halaman admin: sidebar, bar atas, area isi, nomor halaman */
    * { box-sizing: border-box; }
    body { margin: 0; font-family: "Segoe UI", system-ui, sans-serif; background: #fff; color: #111827; }

    /* ===== Sidebar ===== */
    .sidebar { position: fixed; inset: 0 auto 0 0; width: 240px; background: #284686; color: #fff; display: flex; flex-direction: column; padding: 22px 14px; z-index: 5; }
    .sb-merek { display: flex; align-items: center; gap: 10px; padding: 0 8px 20px; border-bottom: 1px solid rgba(255,255,255,.18); margin-bottom: 18px; }
    .sb-merek img { width: 36px; height: 36px; border-radius: 8px; object-fit: contain; background: #fff; padding: 3px; flex: none; }
    .sb-merek b { display: block; font-size: 14px; letter-spacing: .01em; }
    .sb-merek small { font-size: 9px; letter-spacing: .1em; opacity: .7; }
    .sb-menu a { display: flex; align-items: center; gap: 10px; color: #fff; text-decoration: none; font-size: 13px; padding: 10px 12px; border-radius: 8px; margin-bottom: 4px; opacity: .88; }
    .sb-menu a:hover { background: rgba(255,255,255,.1); }
    .sb-menu a.aktif { background: rgba(255,255,255,.16); font-weight: 600; opacity: 1; }
    .sidebar svg { width: 16px; height: 16px; flex: none; }
    .sb-keluar { margin: auto 0 0; border-top: 1px solid rgba(255,255,255,.18); padding-top: 14px; }
    .sb-keluar button { display: flex; align-items: center; gap: 10px; width: 100%; background: none; border: 0; color: #fff; font-size: 13px; font-family: inherit; padding: 10px 12px; cursor: pointer; opacity: .88; }
    .sb-keluar button:hover { opacity: 1; }

    /* ===== Area utama ===== */
    .main { margin-left: 240px; min-height: 100vh; }
    .topbar { height: 62px; background: #fafafa; border-bottom: 1px solid #eee; display: flex; align-items: center; padding: 0 32px; font-size: 14px; }
    .content { padding: 28px 32px 48px; }

    /* ===== Pesan sukses ===== */
    .alert-sukses { background: #e8f7ee; color: #166534; border-radius: 8px; padding: 10px 14px; font-size: 13px; margin: 0 0 16px; }

    /* ===== Nomor halaman ===== */
    .pagination { display: flex; align-items: center; gap: 6px; }
    .pagination .hal { min-width: 28px; height: 28px; padding: 0 8px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid #d9dee9; border-radius: 6px; background: #fff; color: #374151; font-size: 12px; text-decoration: none; }
    .pagination .hal:hover { background: #eef3ff; }
    .pagination .hal.aktif { background: #284686; border-color: #284686; color: #fff; font-weight: 600; }
    .pagination .titik { color: #9ca3af; }

    @media (max-width: 760px) {
      .sidebar { position: static; width: auto; }
      .main { margin-left: 0; }
      .topbar, .content { padding-left: 16px; padding-right: 16px; }
    }

    /* Ukuran diukur dari desain Figma (lebar desain 1440px).
       "calc(var(--v) * 29)" = 29px di desain, ikut membesar/mengecil sesuai lebar layar.
       Mau semuanya lebih kecil/besar? Ubah angka 1440 pada --v (lebih besar = lebih kecil). */
    .art { --v: clamp(0.62px, calc(100vw / 1440), 1.5px); }

    /* ===== Judul + tombol Tambah Artikel ===== */
    .art-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; width: calc(var(--v) * 936); max-width: 100%; }
    .art-judul { font-size: calc(var(--v) * 29); font-weight: 700; line-height: 1.15; margin: 0; color: #111827; }
    .art-crumb { margin: calc(var(--v) * 14) 0 calc(var(--v) * 60) calc(var(--v) * 15); font-size: max(11px, calc(var(--v) * 12)); color: #b4bac6; }
    .art-crumb a { color: #b4bac6; text-decoration: none; }
    .art-crumb b { color: #1d4ed8; font-weight: 700; }

    .art-tombol { display: inline-flex; align-items: center; gap: calc(var(--v) * 8); min-height: 36px; margin-top: calc(var(--v) * 4); padding: calc(var(--v) * 10) calc(var(--v) * 20); border-radius: calc(var(--v) * 8); background: #284686; color: #fff; font-size: max(12px, calc(var(--v) * 10)); font-weight: 600; text-decoration: none; white-space: nowrap; transition: background .15s; }
    .art-tombol:hover { background: #1f3a73; }
    .art-tombol svg { width: 14px; height: 14px; fill: none; stroke: currentColor; stroke-width: 2.4; stroke-linecap: round; }

    .art .alert-sukses { width: calc(var(--v) * 921); max-width: 100%; margin-left: calc(var(--v) * 15); }

    /* ===== Kartu tabel: lebar & tinggi sesuai desain (915 x 671) ===== */
    .art-kartu { width: calc(var(--v) * 921); max-width: 100%; min-height: calc(var(--v) * 678); margin-left: calc(var(--v) * 15);
                 background: #f7f8fc; border: 1px solid #e1e5f0; border-radius: calc(var(--v) * 8); overflow: hidden; display: flex; flex-direction: column; }
    .art-tabel { width: 100%; border-collapse: collapse; table-layout: fixed; }
    .art-tabel th { height: calc(var(--v) * 40); background: #eff2f9; text-align: left; font-size: max(9.5px, calc(var(--v) * 8)); letter-spacing: .07em; text-transform: uppercase; color: #5b6475; font-weight: 600; padding: 0; }
    .art-tabel td { padding: calc(var(--v) * 6.5) 0; border-top: 1px solid #e3e7f1; vertical-align: middle; font-size: max(11px, calc(var(--v) * 9.4)); color: #111827; }
    .art-tabel .k-foto { padding-left: calc(var(--v) * 65); }
    .art-tabel td.t-judul { font-weight: 500; padding-right: calc(var(--v) * 20); }
    .art-tabel td.t-tanggal { color: #4b5563; }

    .art-foto { width: calc(var(--v) * 49); height: calc(var(--v) * 49); min-width: 36px; min-height: 36px; border-radius: calc(var(--v) * 8); background: #dfe5f3 center / cover no-repeat; }
    .art-badge { display: inline-block; background: #dbe5fb; color: #2b5bd7; font-size: max(10px, calc(var(--v) * 8.5)); font-weight: 500; padding: calc(var(--v) * 3) calc(var(--v) * 12); border-radius: 999px; }

    .art-aksi { display: flex; gap: calc(var(--v) * 8); }
    .art-aksi form { margin: 0; }
    .art-ikon { width: calc(var(--v) * 28); height: calc(var(--v) * 28); min-width: 26px; min-height: 26px; display: inline-flex; align-items: center; justify-content: center; border-radius: calc(var(--v) * 7); padding: 0; cursor: pointer; font: inherit; }
    .art-ikon svg { width: 52%; height: 52%; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .art-edit { background: #eef3ff; border: 1px solid #c9dafc; color: #2b5bd7; }
    .art-hapus { background: #fff0f0; border: 1px solid #f4c3c3; color: #dc2626; }

    .art-kosong { text-align: center; color: #9ca3af; padding: calc(var(--v) * 60) 0 !important; }
    .art-kaki { margin-top: auto; min-height: calc(var(--v) * 52); border-top: 1px solid #e3e7f1; display: flex; align-items: center; justify-content: center; gap: 12px; padding: 6px calc(var(--v) * 24); }

    /* ===== Form tambah / edit ===== */
    .art-form { width: calc(var(--v) * 921); max-width: 100%; margin-left: calc(var(--v) * 15); padding: calc(var(--v) * 32); background: #f7f8fc; border: 1px solid #e1e5f0; border-radius: calc(var(--v) * 8); }
    .art-form label { display: block; margin: 18px 0 6px; font-size: max(12px, calc(var(--v) * 10)); font-weight: 600; color: #374151; }
    .art-form label:first-of-type { margin-top: 0; }
    .art-form input[type=text],
    .art-form input[type=date],
    .art-form input[type=file],
    .art-form textarea { width: 100%; padding: 11px 14px; border: 1px solid #d9dee9; border-radius: 8px; background: #fff; font: inherit; font-size: max(13px, calc(var(--v) * 10.5)); color: #111827; outline: none; }
    .art-form textarea { min-height: 180px; resize: vertical; line-height: 1.6; }
    .art-form input:focus,
    .art-form textarea:focus { border-color: #284686; box-shadow: 0 0 0 3px rgba(40, 70, 134, .15); }
    .art-err { margin-top: 5px; color: #dc2626; font-size: 12.5px; }
    .art-preview { display: block; margin-top: 10px; width: 140px; height: 100px; object-fit: cover; border-radius: 8px; border: 1px solid #e1e5f0; }
    .art-form-aksi { display: flex; gap: 10px; margin-top: 26px; }
    .art-form-aksi button { border: 0; font: inherit; cursor: pointer; }
    .art-btn-batal { display: inline-flex; align-items: center; min-height: 36px; padding: 0 20px; border-radius: 8px; background: #e3e7f1; color: #374151; font-size: max(12px, calc(var(--v) * 10)); font-weight: 600; text-decoration: none; }
    .art-btn-batal:hover { background: #d6dbe9; }
    .art-form-aksi .art-tombol { margin-top: 0; }

    @media (max-width: 900px) {
      .art-kartu, .art-form, .art .alert-sukses { width: 100%; margin-left: 0; }
      .art-crumb { margin-left: 0; }
      .art-tabel .k-foto { padding-left: 16px; }
    }
  </style>
  @stack('styles')
</head>
<body>

@php $ik = 'fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"'; @endphp

<div class="layout">

  {{-- ================= SIDEBAR ================= --}}
  <aside class="sidebar">
    <div class="sb-merek">
      <img src="{{ asset('images/logo.png') }}" alt="Logo" width="36" height="36">
      <div><b>SOLUSI KONEKSI ANDA</b><small>ADMIN DASHBOARD</small></div>
    </div>

    <nav class="sb-menu">
      <a href="{{ url('/dashboard') }}" class="{{ request()->is('dashboard') ? 'aktif' : '' }}">
        <svg {!! $ik !!}><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>Dashboard</a>
      <a href="{{ url('/dashboard/profil') }}" class="{{ request()->is('dashboard/profil*') ? 'aktif' : '' }}">
        <svg {!! $ik !!}><path d="M3 21h18M5 21V7l7-4 7 4v14"/><path d="M9 21v-6h6v6M9 10h.01M15 10h.01"/></svg>Profil Perusahaan</a>
      <a href="{{ url('/dashboard/artikel') }}" class="{{ request()->is('dashboard/artikel*') ? 'aktif' : '' }}">
        <svg {!! $ik !!}><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M3 10h18M8 2v4M16 2v4"/></svg>Artikel</a>
      <a href="{{ url('/dashboard/galeri') }}" class="{{ request()->is('dashboard/galeri*') ? 'aktif' : '' }}">
        <svg {!! $ik !!}><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-5-5L5 21"/></svg>Galeri</a>
      <a href="{{ url('/dashboard/layanan') }}" class="{{ request()->is('dashboard/layanan*') ? 'aktif' : '' }}">
        <svg {!! $ik !!}><path d="M3 11v2l14 5V6L3 11Z"/><path d="M7 14v4a2 2 0 0 0 4 0"/></svg>Layanan</a>
      <a href="{{ url('/dashboard/kontak') }}" class="{{ request()->is('dashboard/kontak*') ? 'aktif' : '' }}">
        <svg {!! $ik !!}><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z"/></svg>Kontak</a>
    </nav>

    <form class="sb-keluar" method="POST" action="{{ url('/logout') }}">
      @csrf
      <button type="submit">
        <svg {!! $ik !!}><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>Logout</button>
    </form>
  </aside>

  {{-- ================= AREA UTAMA ================= --}}
  <div class="main">
    <header class="topbar">@include('partials.breadcrumb')</header>

    <section class="content">
      @yield('content')
    </section>
  </div>

</div>

</body>
</html>