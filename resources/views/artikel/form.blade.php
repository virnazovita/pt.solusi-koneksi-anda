@extends('layouts.admin')

@php
    $edit = $artikel->exists;

    // Tag yang boleh ada di isi artikel (selain itu dibuang demi keamanan)
    $aman = '<p><br><b><strong><i><em><u><ul><ol><li><a><img><div><span>';

    $kategoriList    = ['Teknologi', 'Perangkat Keras', 'Bisnis Digital', 'Jaringan', 'Desain Digital', 'Website', 'Keamanan IT'];
    $kategoriDipilih = old('kategori', $artikel->kategori);
    if ($kategoriDipilih && ! in_array($kategoriDipilih, $kategoriList)) {
        $kategoriList[] = $kategoriDipilih;
    }

    $tanggalNilai = old('tanggal', optional($artikel->tanggal)->format('Y-m-d'));
    $punyaFoto    = $edit && $artikel->foto_url;
@endphp

@section('title', $edit ? 'Edit Artikel' : 'Tambah Artikel')

@push('styles')
<style>
  /* Ukuran diukur dari desain Figma (lebar desain 1440px). 1 satuan "v" = 1px di desain. */
  .af { --v: clamp(0.62px, calc(100vw / 1440), 1.5px); }

  /* Breadcrumb di bar atas */
  .af-topcrumb { position: fixed; top: 0; left: 240px; right: 0; height: 62px; display: flex; align-items: center; gap: 8px; padding: 0 32px; font-size: 11.5px; color: #6b7280; z-index: 4; pointer-events: none; }
  .af-topcrumb a { color: #6b7280; text-decoration: none; pointer-events: auto; }
  .af-topcrumb a:hover { color: #111827; }
  .af-topcrumb b { color: #111827; font-weight: 700; }

  /* Judul halaman */
  .af-head { margin: calc(var(--v) * 20) 0 0 calc(var(--v) * 23); }
  .af-judul { margin: 0; font-size: calc(var(--v) * 35); font-weight: 700; line-height: 1.15; color: #111827; }
  .af-sub { margin: calc(var(--v) * 10) 0 0; font-size: max(12px, calc(var(--v) * 13)); color: #6b7280; }

  /* Kartu form */
  .af-kartu { width: calc(var(--v) * 1101); max-width: 100%; margin-top: calc(var(--v) * 40); padding: calc(var(--v) * 34) calc(var(--v) * 28) calc(var(--v) * 29); background: #f7f8fc; border: 1px solid #e1e5f0; border-radius: calc(var(--v) * 8); }
  .af-atas { display: grid; grid-template-columns: calc(var(--v) * 330) 1fr; gap: calc(var(--v) * 25); align-items: start; }

  .af-label { display: block; margin-bottom: calc(var(--v) * 9); font-size: max(11px, calc(var(--v) * 11)); font-weight: 700; color: #111827; }
  .af-input { display: block; width: 100%; height: max(34px, calc(var(--v) * 34)); padding: 0 12px; border: 1px solid #d9dee9; border-radius: 6px; background: #fff; font: inherit; font-size: max(12px, calc(var(--v) * 11)); color: #111827; outline: none; }
  .af-input::placeholder { color: #a0a7b8; }
  .af-input:focus, .af-ed:focus { border-color: #0a53d9; box-shadow: 0 0 0 3px rgba(10, 83, 217, .13); }
  .af-err { margin-top: 5px; color: #dc2626; font-size: 12px; }

  /* Kotak unggah foto */
  .af-drop { position: relative; height: calc(var(--v) * 170); min-height: 130px; display: flex; align-items: center; justify-content: center; border: 1px dashed #aab3c8; border-radius: 6px; background: #fafbfd; cursor: pointer; overflow: hidden; text-align: center; transition: border-color .15s, background .15s; }
  .af-drop:hover, .af-drop.seret { border-color: #0a53d9; background: #eef3ff; }
  .af-drop:focus-visible { outline: 2px solid #0a53d9; outline-offset: 2px; }
  .af-drop-isi { padding: 0 12px; color: #6b7280; }
  .af-drop-isi svg { width: calc(var(--v) * 26); height: calc(var(--v) * 26); min-width: 22px; min-height: 22px; display: block; margin: 0 auto calc(var(--v) * 8); color: #374151; }
  .af-drop-isi p { margin: 0; font-size: max(11px, calc(var(--v) * 10.5)); }
  .af-drop-isi b { color: #111827; font-weight: 700; }
  .af-drop-isi small { display: block; margin-top: calc(var(--v) * 8); font-size: max(10px, calc(var(--v) * 9.5)); color: #9aa1b2; }
  .af-drop-img { display: none; position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
  .af-drop-ganti { display: none; position: absolute; left: 50%; bottom: 8px; transform: translateX(-50%); padding: 4px 12px; border-radius: 999px; background: rgba(17, 24, 39, .72); color: #fff; font-size: 11px; white-space: nowrap; }
  .af-drop.ada-foto .af-drop-img, .af-drop.ada-foto .af-drop-ganti { display: block; }
  .af-drop.ada-foto .af-drop-isi { display: none; }

  /* Kategori + tanggal (sesuai posisi di Figma) */
  .af-baris { display: grid; grid-template-columns: calc(var(--v) * 231) calc(var(--v) * 236); gap: calc(var(--v) * 10); margin: calc(var(--v) * 27) 0 0 calc(var(--v) * 100); }

  .af-garis { border: 0; border-top: 1px solid #e3e7f1; }
  .af-g1 { margin: calc(var(--v) * 40) 0 calc(var(--v) * 34); }
  .af-g2 { margin: calc(var(--v) * 34) 0 calc(var(--v) * 15); }

  /* Editor konten */
  .af-ed-bar { height: max(34px, calc(var(--v) * 42)); display: flex; align-items: center; gap: 2px; padding: 0 8px; border: 1px solid #d9dee9; border-bottom: 0; border-radius: 6px 6px 0 0; background: #f1f3f9; }
  .af-ed-bar button { width: 28px; height: 28px; border: 0; border-radius: 4px; background: transparent; color: #374151; font: inherit; font-size: 13px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; }
  .af-ed-bar button:hover { background: #e3e7f1; }
  .af-ed-bar svg { width: 15px; height: 15px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
  .af-ed-pisah { width: 1px; height: 16px; margin: 0 6px; background: #cdd3e0; }
  .af-ed { min-height: calc(var(--v) * 248); padding: 12px 14px; border: 1px solid #d9dee9; border-radius: 0 0 6px 6px; background: #fafbfd; font-size: max(13px, calc(var(--v) * 11.5)); line-height: 1.6; color: #111827; outline: none; overflow: auto; }
  .af-ed:empty::before { content: attr(data-placeholder); color: #a0a7b8; }
  .af-ed img { max-width: 100%; height: auto; }
  .af-ed a { color: #0a53d9; }

  /* Tombol bawah */
  .af-aksi { display: flex; justify-content: flex-end; gap: calc(var(--v) * 10); }
  .af-btn { height: max(34px, calc(var(--v) * 34)); padding: 0 calc(var(--v) * 18); display: inline-flex; align-items: center; gap: 8px; border: 1px solid transparent; border-radius: 6px; font: inherit; font-size: max(12px, calc(var(--v) * 11)); font-weight: 600; text-decoration: none; cursor: pointer; }
  .af-btn svg { width: 14px; height: 14px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
  .af-btn-batal { background: #fff; border-color: #d9dee9; color: #111827; }
  .af-btn-batal:hover { background: #f3f4f8; }
  .af-btn-simpan { background: #0a53d9; color: #fff; }
  .af-btn-simpan:hover { background: #0846b8; }

  @media (max-width: 900px) {
    .af-head { margin-left: 0; }
    .af-kartu { width: 100%; }
    .af-atas { grid-template-columns: 1fr; }
    .af-baris { margin-left: 0; grid-template-columns: 1fr 1fr; }
  }
  @media (max-width: 760px) {
    .af-topcrumb { left: 0; padding: 0 16px; }
  }
</style>
@endpush

@section('content')
<div class="art af">

  <nav class="af-topcrumb" aria-label="Breadcrumb">
    <a href="{{ url('/dashboard') }}">Dashboard</a><span>&gt;</span>
    <a href="{{ route('admin.artikel.index') }}">Artikel</a><span>&gt;</span>
    <b>{{ $edit ? 'Edit Artikel' : 'Tambah Artikel' }}</b>
  </nav>

  <div class="af-head">
    <h1 class="af-judul">{{ $edit ? 'Edit Artikel' : 'Tambah Artikel Baru' }}</h1>
    <p class="af-sub">{{ $edit ? 'Perbarui informasi detail artikel yang sudah dipublikasikan' : 'Masukkan informasi detail untuk artikel yang akan dipublikasikan' }}</p>
  </div>

  <form id="formArtikel" class="af-kartu" method="POST" enctype="multipart/form-data"
        action="{{ $edit ? route('admin.artikel.update', $artikel) : route('admin.artikel.store') }}">
    @csrf
    @if ($edit)
      @method('PUT')
    @endif

    <div class="af-atas">

      {{-- ===== Kiri: foto / thumbnail ===== --}}
      <div>
        <label class="af-label" for="foto">Foto / Thumbnail Artikel</label>

        <div class="af-drop {{ $punyaFoto ? 'ada-foto' : '' }}" id="drop" tabindex="0" role="button" aria-label="Unggah foto artikel">
          <img id="dzImg" class="af-drop-img" alt="Pratinjau foto" @if ($punyaFoto) src="{{ $artikel->foto_url }}" @endif>
          <div class="af-drop-isi">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 18a4 4 0 0 1-.6-7.96A5.5 5.5 0 0 1 17 8.5a4.5 4.5 0 0 1 .5 9.5"/><path d="M12 12v8M9 15l3-3 3 3"/></svg>
            <p><b>Upload a file</b> or drag and drop</p>
            <small>JPG, PNG, WEBP up to 2MB</small>
          </div>
          <span class="af-drop-ganti">Klik atau seret untuk mengganti foto</span>
        </div>
        <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp" hidden>
        @error('foto') <div class="af-err">{{ $message }}</div> @enderror
      </div>

      {{-- ===== Kanan: judul, kategori, tanggal ===== --}}
      <div>
        <label class="af-label" for="judul">Judul Artikel</label>
        <input type="text" id="judul" name="judul" class="af-input"
               placeholder="Contoh: Kegiatan Pramuka Persami" value="{{ old('judul', $artikel->judul) }}">
        @error('judul') <div class="af-err">{{ $message }}</div> @enderror

        <div class="af-baris">
          <div>
            <label class="af-label" for="kategori">Kategori</label>
            <select id="kategori" name="kategori" class="af-input">
              <option value="">Pilih Kategori</option>
              @foreach ($kategoriList as $k)
                <option value="{{ $k }}" @selected($kategoriDipilih === $k)>{{ $k }}</option>
              @endforeach
            </select>
            @error('kategori') <div class="af-err">{{ $message }}</div> @enderror
          </div>

          <div>
            <label class="af-label" for="tanggal">Tanggal Publikasi</label>
            <input type="date" id="tanggal" name="tanggal" class="af-input" value="{{ $tanggalNilai }}">
            @error('tanggal') <div class="af-err">{{ $message }}</div> @enderror
          </div>
        </div>
      </div>
    </div>

    <hr class="af-garis af-g1">

    {{-- ===== Konten artikel ===== --}}
    <span class="af-label">Konten Artikel</span>
    <div class="af-ed-bar">
      <button type="button" data-cmd="bold" title="Tebal" aria-label="Tebal"><b>B</b></button>
      <button type="button" data-cmd="italic" title="Miring" aria-label="Miring"><i>I</i></button>
      <button type="button" data-cmd="underline" title="Garis bawah" aria-label="Garis bawah"><u>U</u></button>
      <span class="af-ed-pisah"></span>
      <button type="button" data-cmd="insertUnorderedList" title="Daftar poin" aria-label="Daftar poin">
        <svg viewBox="0 0 24 24"><path d="M9 6h11M9 12h11M9 18h11"/><circle cx="4.5" cy="6" r="1"/><circle cx="4.5" cy="12" r="1"/><circle cx="4.5" cy="18" r="1"/></svg>
      </button>
      <button type="button" data-cmd="insertOrderedList" title="Daftar nomor" aria-label="Daftar nomor">
        <svg viewBox="0 0 24 24"><path d="M10 6h11M10 12h11M10 18h11"/><path d="M4 5l1-1v5M3.5 14.5h2l-2 2.5h2"/></svg>
      </button>
      <span class="af-ed-pisah"></span>
      <button type="button" id="btnLink" title="Sisipkan tautan" aria-label="Sisipkan tautan">
        <svg viewBox="0 0 24 24"><path d="M10 14a4 4 0 0 0 5.7 0l3-3a4 4 0 0 0-5.7-5.7l-1 1"/><path d="M14 10a4 4 0 0 0-5.7 0l-3 3a4 4 0 0 0 5.7 5.7l1-1"/></svg>
      </button>
      <button type="button" id="btnGambar" title="Sisipkan gambar" aria-label="Sisipkan gambar">
        <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="1.6"/><path d="m21 16-5-5-8 9"/></svg>
      </button>
    </div>
    <div id="editor" class="af-ed" contenteditable="true" role="textbox" aria-multiline="true" aria-label="Konten Artikel"
         data-placeholder="Tulis konten artikel di sini...">{!! strip_tags((string) old('isi', $artikel->isi), $aman) !!}</div>
    <textarea name="isi" id="isi" hidden></textarea>
    @error('isi') <div class="af-err">{{ $message }}</div> @enderror

    <hr class="af-garis af-g2">

    <div class="af-aksi">
      <a href="{{ route('admin.artikel.index') }}" class="af-btn af-btn-batal">Batal</a>
      <button type="submit" class="af-btn af-btn-simpan">
        <svg viewBox="0 0 24 24"><path d="M5 3h11l3 3v14a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Z"/><path d="M8 3v6h8V3M8 21v-7h8v7"/></svg>
        Simpan Artikel
      </button>
    </div>
  </form>

</div>

<script>
(function () {
  // ===== Unggah foto (klik atau seret) =====
  var drop  = document.getElementById('drop');
  var input = document.getElementById('foto');
  var img   = document.getElementById('dzImg');

  function tampil(file) {
    if (!file || file.type.indexOf('image/') !== 0) return;
    var r = new FileReader();
    r.onload = function (e) { img.src = e.target.result; drop.classList.add('ada-foto'); };
    r.readAsDataURL(file);
  }

  drop.addEventListener('click', function () { input.click(); });
  drop.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); input.click(); }
  });
  input.addEventListener('change', function () { tampil(input.files[0]); });

  ['dragenter', 'dragover'].forEach(function (n) {
    drop.addEventListener(n, function (e) { e.preventDefault(); drop.classList.add('seret'); });
  });
  ['dragleave', 'drop'].forEach(function (n) {
    drop.addEventListener(n, function (e) { e.preventDefault(); drop.classList.remove('seret'); });
  });
  drop.addEventListener('drop', function (e) {
    if (e.dataTransfer.files.length) { input.files = e.dataTransfer.files; tampil(input.files[0]); }
  });

  // ===== Editor konten =====
  var ed  = document.getElementById('editor');
  var isi = document.getElementById('isi');

  document.querySelectorAll('[data-cmd]').forEach(function (b) {
    b.addEventListener('mousedown', function (e) { e.preventDefault(); });
    b.addEventListener('click', function () { ed.focus(); document.execCommand(b.getAttribute('data-cmd'), false, null); });
  });

  document.getElementById('btnLink').addEventListener('click', function () {
    var url = prompt('Masukkan alamat tautan (diawali https://):');
    if (url && /^https?:\/\//i.test(url)) { ed.focus(); document.execCommand('createLink', false, url); }
  });

  document.getElementById('btnGambar').addEventListener('click', function () {
    var url = prompt('Masukkan alamat gambar (diawali https://):');
    if (url && /^https?:\/\//i.test(url)) { ed.focus(); document.execCommand('insertImage', false, url); }
  });

  ed.addEventListener('input', function () { if (ed.innerHTML === '<br>') ed.innerHTML = ''; });

  document.getElementById('formArtikel').addEventListener('submit', function () {
    var kosong = ed.innerText.trim() === '' && !ed.querySelector('img');
    isi.value = kosong ? '' : ed.innerHTML;
  });
})();
</script>
@endsection