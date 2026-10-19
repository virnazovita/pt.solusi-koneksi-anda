@php
    // $aktif diisi dari halaman yang memanggil: beranda, profil, layanan, artikel, galeri, atau kontak
    $aktif = $aktif ?? '';

    $menuPublik = [
        ['beranda', 'Beranda',           '/'],
        ['profil',  'Profil Perusahaan', '/profil'],
        ['layanan', 'Produk / Layanan',  '/layanan'],
        ['artikel', 'Artikel',           '/artikel'],
        ['galeri',  'Galeri Kegiatan',   '/galeri'],
        ['kontak',  'Kontak Kami',       '/kontak'],
    ];
@endphp
<style>
  /* Gaya navbar sama dengan tampilan awal (nilai diambil dari app.css) */
  .pn { position: sticky; top: 0; z-index: 50; background-color: #0a1f4d; box-shadow: 0 4px 6px -1px rgb(0 0 0 / .1), 0 2px 4px -2px rgb(0 0 0 / .1); font-family: 'Poppins', 'Segoe UI', sans-serif; }
  .pn, .pn * { box-sizing: border-box; }
  .pn-in { max-width: 80rem; margin: 0 auto; padding: .75rem 1.25rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
  .pn-brand { display: flex; align-items: center; gap: .625rem; text-decoration: none; }
  /* Kotak logo: sama dengan kotak logo di sidebar dashboard (36px, sudut 8px, latar putih, padding 3px) */
  .pn-brand img { display: block; width: 36px; height: 36px; flex: none; border-radius: 8px; object-fit: contain; background: #fff; padding: 3px; }
  .pn-brand span { color: #fff; font-weight: 700; letter-spacing: .025em; font-size: .875rem; line-height: 1.25rem; white-space: nowrap; }

  .pn-menu { display: none; align-items: center; gap: 1.5rem; }
  .pn-link { color: #cbd5e1; font-size: .85rem; font-weight: 500; text-decoration: none; white-space: nowrap; }
  .pn-link:hover { color: #fff; }
  .pn-link.aktif { color: #fff; font-weight: 600; position: relative; }
  .pn-link.aktif::after { content: ''; position: absolute; left: 0; right: 0; bottom: -6px; height: 2px; background: #fff; border-radius: 2px; }

  .pn-kanan { display: flex; align-items: center; gap: .75rem; }
  .pn-burger { display: block; background: none; border: 0; padding: 0; cursor: pointer; }
  .pn-burger img { width: 1.75rem; height: 1.75rem; display: block; }

  /* Menu versi layar kecil (muncul saat tombol hamburger ditekan) */
  .pn-mobile { display: none; padding: 0 1.25rem 1rem; }
  .pn-mobile.buka { display: block; }
  .pn-mobile a { display: block; padding: .6rem 0; border-top: 1px solid rgba(255, 255, 255, .08); color: #cbd5e1; font-size: .9rem; text-decoration: none; }
  .pn-mobile a.aktif { color: #fff; font-weight: 600; }

  @media (min-width: 768px) {
    .pn-brand span { font-size: 1rem; line-height: 1.5rem; }
  }
  @media (min-width: 1200px) {
    .pn-menu { display: flex; }
    .pn-burger { display: none; }
    .pn-mobile, .pn-mobile.buka { display: none; }
  }
</style>

<header class="pn">
  <div class="pn-in">
    <a href="{{ url('/') }}" class="pn-brand">
      <img src="{{ asset('images/logo.png') }}" alt="Logo PT Solusi Koneksi Anda">
      <span>PT SOLUSI KONEKSI ANDA</span>
    </a>

    <nav class="pn-menu" aria-label="Menu utama">
      @foreach ($menuPublik as [$kode, $label, $alamat])
        <a href="{{ url($alamat) }}" class="pn-link {{ $aktif === $kode ? 'aktif' : '' }}">{{ $label }}</a>
      @endforeach
    </nav>

    <div class="pn-kanan">
      <button type="button" class="pn-burger" aria-label="Buka menu" aria-expanded="false"
              onclick="var m=document.getElementById('pnMobile');var b=m.classList.toggle('buka');this.setAttribute('aria-expanded', b);">
        <img src="https://api.iconify.design/mdi/menu.svg?color=%23ffffff" alt="Menu">
      </button>
    </div>
  </div>

  <nav class="pn-mobile" id="pnMobile" aria-label="Menu utama (layar kecil)">
    @foreach ($menuPublik as [$kode, $label, $alamat])
      <a href="{{ url($alamat) }}" class="{{ $aktif === $kode ? 'aktif' : '' }}">{{ $label }}</a>
    @endforeach
  </nav>
</header>