@extends('layouts.admin')

@php
    $edit = $galeri->exists;

    $kategoriList    = ['Kegiatan', 'Prestasi', 'Fasilitas', 'Lainnya'];
    $kategoriDipilih = old('kategori', $galeri->kategori);
    if ($kategoriDipilih && ! in_array($kategoriDipilih, $kategoriList)) {
        $kategoriList[] = $kategoriDipilih;
    }

    $tanggalNilai = old('tanggal', $edit ? optional($galeri->tanggal)->format('Y-m-d') : now()->format('Y-m-d'));
    $punyaFoto    = $edit && $galeri->foto_url;
@endphp

@section('title', $edit ? 'Edit Galeri' : 'Tambah Galeri')

@push('styles')
<style>
  .gf-sub { margin: 6px 0 0 calc(var(--v) * 15); color: #6b7280; font-size: 13.5px; }
  .gf-kartu { width: calc(var(--v) * 921); max-width: 100%; margin: calc(var(--v) * 36) 0 0 calc(var(--v) * 15); padding: 28px; background: #f7f8fc; border: 1px solid #e1e5f0; border-radius: 12px; }
  .gf-grid { display: grid; grid-template-columns: 260px 1fr; gap: 28px; align-items: start; }
  .gf-label { display: block; margin-bottom: 8px; font-size: 12px; font-weight: 700; color: #111827; }
  .gf-input { display: block; width: 100%; height: 40px; padding: 0 12px; border: 1px solid #d9dee9; border-radius: 8px; background: #fff; font: inherit; font-size: 13px; color: #111827; outline: none; }
  .gf-input:focus { border-color: #284686; box-shadow: 0 0 0 3px rgba(40, 70, 134, .15); }
  .gf-campur { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 18px; }
  .gf-err { margin-top: 5px; color: #dc2626; font-size: 12px; }
  .gf-alert { background: #fdecec; color: #b42318; border-radius: 8px; padding: 10px 14px; font-size: 12.5px; font-weight: 600; margin-bottom: 18px; }

  .gf-drop { position: relative; height: 190px; display: flex; align-items: center; justify-content: center; border: 1.5px dashed #aab3c8; border-radius: 10px; background: #fff; cursor: pointer; overflow: hidden; text-align: center; }
  .gf-drop:hover, .gf-drop.seret { border-color: #284686; background: #eef3ff; }
  .gf-drop-isi { padding: 0 12px; color: #6b7280; font-size: 12px; }
  .gf-drop-isi svg { width: 28px; height: 28px; display: block; margin: 0 auto 8px; color: #374151; }
  .gf-drop-isi small { display: block; margin-top: 6px; font-size: 10.5px; color: #9aa1b2; }
  .gf-drop img { display: none; position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
  .gf-ganti { display: none; position: absolute; left: 50%; bottom: 8px; transform: translateX(-50%); padding: 4px 12px; border-radius: 999px; background: rgba(17, 24, 39, .72); color: #fff; font-size: 11px; white-space: nowrap; }
  .gf-drop.ada-foto img, .gf-drop.ada-foto .gf-ganti { display: block; }
  .gf-drop.ada-foto .gf-drop-isi { display: none; }

  .gf-aksi { display: flex; justify-content: flex-end; gap: 10px; margin-top: 26px; padding-top: 20px; border-top: 1px solid #e3e7f1; }
  .gf-btn { height: 38px; padding: 0 20px; display: inline-flex; align-items: center; gap: 8px; border: 1px solid transparent; border-radius: 8px; font: inherit; font-size: 12.5px; font-weight: 600; text-decoration: none; cursor: pointer; }
  .gf-btn svg { width: 14px; height: 14px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
  .gf-batal { background: #fff; border-color: #d9dee9; color: #111827; }
  .gf-batal:hover { background: #f3f4f8; }
  .gf-simpan { background: #284686; color: #fff; }
  .gf-simpan:hover { background: #1f3a73; }

  @media (max-width: 900px) {
    .gf-kartu { width: 100%; margin-left: 0; }
    .gf-sub { margin-left: 0; }
    .gf-grid, .gf-campur { grid-template-columns: 1fr; }
  }
</style>
@endpush

@section('content')
<div class="art">
  <h1 class="art-judul">{{ $edit ? 'Edit Galeri' : 'Tambah Galeri' }}</h1>
  <p class="gf-sub">{{ $edit ? 'Perbarui informasi foto galeri' : 'Unggah foto baru untuk galeri sekolah' }}</p>

  <form id="formGaleri" class="gf-kartu" method="POST" enctype="multipart/form-data"
        action="{{ $edit ? route('galeri.update', $galeri) : route('galeri.store') }}">
    @csrf
    @if ($edit)
      @method('PUT')
    @endif

    @if ($errors->any())
      <div class="gf-alert" role="alert">Data belum lengkap. Periksa kolom yang ditandai di bawah.</div>
    @endif

    <div class="gf-grid">
      {{-- Foto --}}
      <div>
        <span class="gf-label">Foto</span>
        <div class="gf-drop {{ $punyaFoto ? 'ada-foto' : '' }}" id="drop" tabindex="0" role="button" aria-label="Unggah foto galeri">
          <img id="dzImg" alt="Pratinjau foto" @if ($punyaFoto) src="{{ $galeri->foto_url }}" @endif>
          <div class="gf-drop-isi">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 18a4 4 0 0 1-.6-7.96A5.5 5.5 0 0 1 17 8.5a4.5 4.5 0 0 1 .5 9.5"/><path d="M12 12v8M9 15l3-3 3 3"/></svg>
            <b>Upload a file</b> or drag and drop
            <small>JPG, PNG, WEBP up to 2MB</small>
          </div>
          <span class="gf-ganti">Klik atau seret untuk mengganti foto</span>
        </div>
        <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp" hidden>
        @error('foto') <div class="gf-err">{{ $message }}</div> @enderror
      </div>

      {{-- Judul, kategori, tanggal --}}
      <div>
        <label class="gf-label" for="judul">Judul</label>
        <input type="text" id="judul" name="judul" class="gf-input" maxlength="255"
               placeholder="Contoh: Upacara Hari Kemerdekaan" value="{{ old('judul', $galeri->judul) }}">
        @error('judul') <div class="gf-err">{{ $message }}</div> @enderror

        <div class="gf-campur">
          <div>
            <label class="gf-label" for="kategori">Kategori</label>
            <select id="kategori" name="kategori" class="gf-input">
              <option value="">Pilih Kategori</option>
              @foreach ($kategoriList as $k)
                <option value="{{ $k }}" @selected($kategoriDipilih === $k)>{{ $k }}</option>
              @endforeach
            </select>
            @error('kategori') <div class="gf-err">{{ $message }}</div> @enderror
          </div>
          <div>
            <label class="gf-label" for="tanggal">Tanggal</label>
            <input type="date" id="tanggal" name="tanggal" class="gf-input" value="{{ $tanggalNilai }}">
            @error('tanggal') <div class="gf-err">{{ $message }}</div> @enderror
          </div>
        </div>
      </div>
    </div>

    <div class="gf-aksi">
      <a href="{{ route('galeri.index') }}" class="gf-btn gf-batal">Batal</a>
      <button type="submit" class="gf-btn gf-simpan">
        <svg viewBox="0 0 24 24"><path d="M5 3h11l3 3v14a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Z"/><path d="M8 3v6h8V3M8 21v-7h8v7"/></svg>
        Simpan
      </button>
    </div>
  </form>
</div>

<script>
(function () {
  var drop = document.getElementById('drop'), input = document.getElementById('foto'), img = document.getElementById('dzImg');

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
})();
</script>
@endsection