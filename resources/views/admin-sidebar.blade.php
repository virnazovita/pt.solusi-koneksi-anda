{{-- Gaya dasar admin ikut dimuat di sini, jadi tampilan tetap rapi walaupun admin.css tidak terbaca --}}
<style>
/* Gaya dasar halaman admin (sidebar, bar atas, area isi, nomor halaman) */
* { box-sizing: border-box; }
body { margin: 0; font-family: "Segoe UI", system-ui, sans-serif; background: #fff; color: #111827; }

/* Sidebar */
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

/* Area utama */
.main { margin-left: 240px; min-height: 100vh; }
.topbar { height: 62px; background: #fafafa; border-bottom: 1px solid #eee; display: flex; align-items: center; padding: 0 32px; font-size: 14px; }
.topbar .aktif { color: #1d4ed8; font-weight: 600; }
.content { padding: 28px 32px 48px; }

/* Pesan sukses */
.alert-sukses { background: #e8f7ee; color: #166534; border-radius: 8px; padding: 10px 14px; font-size: 13px; margin: 0 0 16px; }

/* Nomor halaman */
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
</style>
@php $ik = 'fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"'; @endphp
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