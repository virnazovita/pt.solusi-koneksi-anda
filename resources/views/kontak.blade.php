@php
    // Data kontak diambil dari Dashboard > Profil Perusahaan
    $kontak = [
            'alamat'           => 'Jl. Raya Pajajaran No. 88 F, Kota Bogor 16153',
            'email'            => 'solusikoneksianda04@gmail.com',
            'telepon'          => '+6285882394475',
        'jam'     => ['Senin - Jumat', '08.00 - 17.00'],
    ];

    $menu = [
        ['Home', '/'],
        ['Profil', '/profil'],
        ['Layanan', '/layanan'],
        ['Artikel', '/artikel'],
        ['Galeri', '/galeri'],
        ['Kontak', '/kontak'],
    ];

    $emailValid = str_contains($kontak['email'], '@') && ! str_contains($kontak['email'], ' ');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kontak Kami - PT Solusi Koneksi Anda</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: "Poppins", "Segoe UI", system-ui, sans-serif; color: #1f2a44; background: #fff; }
    a { text-decoration: none; color: inherit; }
    svg { display: block; }

    :root { --navy: #0b2a6f; --judul: #0b3a78; --garis: #e3e7f0; }

    /* ===== Navbar ===== */
    .nav { background: #0c1f4f; color: #fff; position: relative; z-index: 5; }
    .nav-in { max-width: 1110px; margin: 0 auto; padding: 0 20px; height: 60px; display: flex; align-items: center; justify-content: space-between; gap: 20px; }
    .brand { display: flex; align-items: center; gap: 12px; font-weight: 700; font-size: 17px; white-space: nowrap; }
    .brand img { width: 34px; height: 34px; object-fit: contain; }
    .menu { display: flex; gap: 26px; }
    .menu a { padding: 6px 0; font-size: 14.5px; font-weight: 500; border-bottom: 2px solid transparent; }
    .menu a:hover { border-bottom-color: rgba(255,255,255,.5); }
    .menu a.aktif { border-bottom-color: #fff; font-weight: 600; }
    .btn-login { padding: 10px 18px; border-radius: 8px; background: #34498f; color: #fff; font-size: 14px; font-weight: 600; white-space: nowrap; }
    .btn-login:hover { background: #3e56a3; }

    /* ===== Hero ===== */
    .hero { height: clamp(200px, 25vw, 330px); background-size: cover; background-position: center; background-color: #0b2a66; position: relative; }
    .hero::after { content: ""; position: absolute; inset: 0; background: linear-gradient(180deg, rgba(8,24,70,.25) 0%, rgba(8,24,70,.05) 60%, rgba(8,24,70,.25) 100%); }

    /* ===== 4 kartu info (menumpuk di bawah hero) ===== */
    .info { position: relative; z-index: 2; max-width: 1100px; margin: -92px auto 0; padding: 0 20px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
    .info-kartu { background: #fff; border: 1px solid var(--garis); border-radius: 10px; padding: 22px 16px 20px; text-align: center; box-shadow: 0 8px 24px rgba(15, 31, 77, .12); }
    .ikon { width: 44px; height: 44px; margin: 0 auto 12px; border-radius: 50%; background: #e9ecf3; color: #4b5568; display: grid; place-items: center; }
    .ikon svg { width: 20px; height: 20px; fill: none; stroke: currentColor; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
    .info-kartu h3 { font-size: 14px; font-weight: 700; color: var(--judul); }
    .info-kartu p { margin-top: 6px; font-size: 12.5px; line-height: 1.6; color: #4b5568; }

    /* ===== Form + Hubungi Kami ===== */
    .dua { max-width: 1100px; margin: 56px auto 0; padding: 0 20px; display: grid; grid-template-columns: 1.55fr 1fr; gap: 28px; align-items: start; }
    .form-kartu { background: #fff; border: 1px solid var(--garis); border-radius: 10px; padding: 28px 30px 30px; }
    .form-kartu h2, .hubungi h2 { font-size: 20px; font-weight: 700; color: var(--judul); display: flex; align-items: center; gap: 8px; }
    .form-kartu h2 svg { width: 18px; height: 18px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .sub { margin-top: 4px; font-size: 13px; color: #5b6579; }

    .field { margin-top: 18px; }
    .field label { display: block; margin-bottom: 7px; font-size: 13px; font-weight: 600; color: #1f2a44; }
    .input { position: relative; }
    .input svg { position: absolute; left: 13px; top: 14px; width: 16px; height: 16px; fill: none; stroke: #8a94ab; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; pointer-events: none; }
    .input input, .input textarea { width: 100%; padding: 12px 14px 12px 40px; border: 1px solid #d9dee9; border-radius: 8px; background: #fff; font: inherit; font-size: 13.5px; color: #1f2a44; outline: none; }
    .input textarea { min-height: 130px; resize: vertical; line-height: 1.6; }
    .input input::placeholder, .input textarea::placeholder { color: #9aa3b8; }
    .input input:focus, .input textarea:focus { border-color: var(--navy); box-shadow: 0 0 0 3px rgba(11, 42, 111, .12); }

    .btn-kirim { margin-top: 22px; width: 100%; height: 48px; border: 0; border-radius: 8px; background: var(--navy); color: #fff; font: inherit; font-size: 14px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; transition: background .15s; }
    .btn-kirim:hover { background: #0a2159; }
    .btn-kirim svg { width: 16px; height: 16px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

    /* Pesan sukses / error setelah kirim */
    .notif { margin-top: 16px; padding: 11px 14px; border-radius: 8px; font-size: 13px; line-height: 1.6; }
    .notif.ok { background: #e8f7ee; color: #166534; border: 1px solid #bfe8cf; }
    .notif.err { background: #fdecec; color: #b42318; border: 1px solid #f6c9c9; }
    .galat { margin-top: 6px; font-size: 12px; color: #b42318; }

    .hubungi { background: #e8eefb; border: 1px solid #d5dff5; border-radius: 10px; padding: 26px 26px 28px; }
    .hubungi .sub { color: #4f5d7a; }
    .h-item { display: flex; gap: 14px; margin-top: 22px; }
    .h-item .ikon { margin: 0; width: 38px; height: 38px; flex: none; background: #d3dbec; }
    .h-item .ikon svg { width: 17px; height: 17px; }
    .h-item b { display: block; font-size: 13.5px; color: var(--judul); }
    .h-item span { display: block; margin-top: 2px; font-size: 12.5px; line-height: 1.6; color: #4b5568; }

    /* ===== Peta ===== */
    .peta { max-width: 1100px; margin: 56px auto 0; padding: 0 20px; }
    .peta-isi { height: clamp(240px, 28vw, 340px); border-radius: 10px; overflow: hidden; border: 1px solid var(--garis); background: #eef1f6; }
    .peta-isi iframe, .peta-isi img { width: 100%; height: 100%; border: 0; display: block; object-fit: cover; }

    /* ===== Banner ajakan ===== */
    .banner { max-width: 1100px; margin: 56px auto 0; padding: 0 20px; }
    .banner-isi { position: relative; min-height: clamp(220px, 26vw, 330px); border-radius: 10px; overflow: hidden; background-size: cover; background-position: center; background-color: #1b2f66; display: flex; align-items: flex-end; }
    .banner-isi::before { content: ""; position: absolute; inset: 0; background: linear-gradient(0deg, rgba(8,20,55,.88) 0%, rgba(8,20,55,.35) 55%, rgba(8,20,55,.1) 100%); }
    .banner-teks { position: relative; padding: 28px 34px; color: #fff; max-width: 560px; }
    .banner-teks h2 { font-size: clamp(20px, 2.4vw, 28px); font-weight: 700; }
    .banner-teks p { margin-top: 8px; font-size: 13.5px; line-height: 1.7; color: rgba(255,255,255,.88); }

    /* ===== Footer ===== */
    .footer { margin-top: 70px; background: #0c1f4f; color: #fff; text-align: center; padding: 26px 20px; font-size: 13px; }

    @media (max-width: 960px) {
      .info { grid-template-columns: repeat(2, 1fr); margin-top: -70px; }
      .dua { grid-template-columns: 1fr; }
    }
    @media (max-width: 860px) {
      .nav-in { height: auto; padding: 12px 20px; flex-wrap: wrap; justify-content: center; }
      .menu { flex-wrap: wrap; justify-content: center; gap: 8px 18px; order: 3; width: 100%; }
    }
    @media (max-width: 520px) {
      .info { grid-template-columns: 1fr; }
      .form-kartu { padding: 22px 18px 24px; }
    }
  </style>
</head>
<body>

  {{-- ===== Navbar ===== --}}
  @include('partials.navbar-publik', ['aktif' => 'kontak'])

  {{-- ===== Hero ===== --}}
  <section class="hero" style="background-image: linear-gradient(135deg, #0a1f4d 0%, #0b3d78 55%, #2f5bb8 100%);" aria-label="Kontak Kami"></section>

  {{-- ===== 4 kartu info ===== --}}
  <section class="info" aria-label="Informasi kontak">
    <div class="info-kartu">
      <div class="ikon"><svg viewBox="0 0 24 24"><path d="M12 21s7-6.2 7-11.5A7 7 0 0 0 5 9.5C5 14.8 12 21 12 21Z"/><circle cx="12" cy="9.5" r="2.5"/></svg></div>
      <h3>Alamat</h3>
      <p>{{ $kontak['alamat'] }}</p>
    </div>
    <div class="info-kartu">
      <div class="ikon"><svg viewBox="0 0 24 24"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2Z"/></svg></div>
      <h3>Telepon</h3>
      <p>{{ $kontak['telepon'] }}</p>
    </div>
    <div class="info-kartu">
      <div class="ikon"><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg></div>
      <h3>Email</h3>
      <p>{{ $kontak['email'] }}</p>
    </div>
    <div class="info-kartu">
      <div class="ikon"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></div>
      <h3>Jam Operasional</h3>
      <p>{{ $kontak['jam'][0] }}<br>{{ $kontak['jam'][1] }}</p>
    </div>
  </section>

  {{-- ===== Form kirim pesan + Hubungi Kami ===== --}}
  <section class="dua">

    <div class="form-kartu" id="kirim-pesan">
      <h2>
        Kirim Pesan
        <svg viewBox="0 0 24 24"><path d="M22 2 11 13"/><path d="M22 2 15 22l-4-9-9-4 20-7Z"/></svg>
      </h2>
      <p class="sub">Isi form di bawah ini dan kami akan segera merespons pesan Anda.</p>

      @if (session('kontak_sukses'))
        <div class="notif ok" role="status">{{ session('kontak_sukses') }}</div>
      @endif
      @if ($errors->any())
        <div class="notif err" role="alert">Pesan belum terkirim. Periksa isian yang ditandai di bawah.</div>
      @endif

      <form id="formKontak" method="POST" action="{{ route('kontak.kirim') }}" novalidate>
        @csrf

        <div class="field">
          <label for="nama">Nama Lengkap</label>
          <div class="input">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-7 8-7s8 3 8 7"/></svg>
            <input type="text" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap anda" autocomplete="name">
          </div>
          @error('nama') <div class="galat">{{ $message }}</div> @enderror
        </div>

        <div class="field">
          <label for="surel">Email</label>
          <div class="input">
            <svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
            <input type="email" id="surel" name="email" value="{{ old('email') }}" placeholder="Masukkan email anda" autocomplete="email">
          </div>
          @error('email') <div class="galat">{{ $message }}</div> @enderror
        </div>

        <div class="field">
          <label for="subjek">Subjek</label>
          <div class="input">
            <svg viewBox="0 0 24 24"><path d="M4 5h16v11H8l-4 4V5Z"/></svg>
            <input type="text" id="subjek" name="subjek" value="{{ old('subjek') }}" placeholder="Masukkan subjek pesan">
          </div>
          @error('subjek') <div class="galat">{{ $message }}</div> @enderror
        </div>

        <div class="field">
          <label for="pesan">Pesan</label>
          <div class="input">
            <svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
            <textarea id="pesan" name="pesan" placeholder="Tulis pesan anda di sini">{{ old('pesan') }}</textarea>
          </div>
          @error('pesan') <div class="galat">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn-kirim">
          <svg viewBox="0 0 24 24"><path d="M22 2 11 13"/><path d="M22 2 15 22l-4-9-9-4 20-7Z"/></svg>
          Kirim Pesan
        </button>
      </form>
    </div>

    <aside class="hubungi">
      <h2>Hubungi Kami</h2>
      <p class="sub">Kami siap membantu menjawab pertanyaan Anda.</p>

      <div class="h-item">
        <div class="ikon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.2 7-11.5A7 7 0 0 0 5 9.5C5 14.8 12 21 12 21Z"/><circle cx="12" cy="9.5" r="2.5"/></svg></div>
        <div><b>Alamat</b><span>{{ $kontak['alamat'] }}</span></div>
      </div>
      <div class="h-item">
        <div class="ikon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2Z"/></svg></div>
        <div><b>Telepon</b><span>{{ $kontak['telepon'] }}</span></div>
      </div>
      <div class="h-item">
        <div class="ikon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg></div>
        <div><b>Email</b><span>{{ $kontak['email'] }}</span></div>
      </div>
      <div class="h-item">
        <div class="ikon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></div>
        <div><b>Jam Operasional</b><span>{{ $kontak['jam'][0] }}<br>{{ $kontak['jam'][1] }}</span></div>
      </div>
    </aside>
  </section>

  {{-- ===== Peta ===== --}}
  <section class="peta" aria-label="Lokasi perusahaan">
    <div class="peta-isi">
      <iframe src="https://www.google.com/maps?q={{ urlencode($kontak['alamat']) }}&output=embed" title="Peta lokasi PT Solusi Koneksi Anda" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
  </section>

  {{-- ===== Banner ajakan ===== --}}
  @php
      // Foto latar banner (public/images/foto kontak.jpg) + lapisan biru tipis.
      // Kalau fotonya belum ada, pakai gradasi biru sebagai cadangan.
      $bannerBg = is_file(public_path('images/foto kontak.jpg'))
          ? "linear-gradient(135deg, rgba(10,31,77,.55) 0%, rgba(11,61,120,.30) 100%), url('" . asset('images/' . rawurlencode('foto kontak.jpg')) . "')"
          : 'linear-gradient(135deg, #0a1f4d 0%, #0b3d78 55%, #2f5bb8 100%)';
  @endphp
  <section class="banner">
    <div class="banner-isi" style="background-image: {!! $bannerBg !!};">
      <div class="banner-teks">
        <h2>Mari Berkolaborasi</h2>
        <p>Kami terbuka untuk kerja sama dengan instansi, dunia usaha, dan masyarakat untuk bersama menghadirkan solusi teknologi yang tepat guna.</p>
      </div>
    </div>
  </section>

  {{-- ===== Footer ===== --}}
  <footer class="footer">
    &copy; {{ date('Y') }} PT Solusi Koneksi Anda. Semua Hak Cipta Dilindungi.
  </footer>

  <script>
  // Setelah pesan terkirim (atau ada isian yang salah), gulir ke bagian form
  (function () {
    var notif = document.querySelector('.notif');
    if (notif) { notif.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
  })();
  </script>

</body>
</html>