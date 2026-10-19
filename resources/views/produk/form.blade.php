@extends('layouts.admin')

@php
    $edit      = $produk->exists;
    $punyaFoto = $edit && $produk->foto_url;
    $stokNow   = old('stok', $produk->stok ?: 'Tersedia');
@endphp

@section('title', $edit ? 'Edit Produk' : 'Tambah Produk')

@push('styles')
<style>
  .pf-sub { margin: 6px 0 0 calc(var(--v) * 15); color: #6b7280; font-size: 13.5px; }
  .pf-kartu { width: calc(var(--v) * 921); max-width: 100%; margin: calc(var(--v) * 36) 0 0 calc(var(--v) * 15); padding: 28px; background: #f7f8fc; border: 1px solid #e1e5f0; border-radius: 12px; }
  .pf-grid { display: grid; grid-template-columns: 260px 1fr; gap: 28px; align-items: start; }
  .pf-label { display: block; margin-bottom: 8px; font-size: 12px; font-weight: 700; color: #111827; }
  .pf-input { display: block; width: 100%; height: 40px; padding: 0 12px; border: 1px solid #d9dee9; border-radius: 8px; background: #fff; font: inherit; font-size: 13px; color: #111827; outline: none; }
  .pf-input:focus { border-color: #284686; box-shadow: 0 0 0 3px rgba(40, 70, 134, .15); }
  .pf-kolom { margin-top: 18px; }
  .pf-dua { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 18px; }
  .pf-err { margin-top: 5px; color: #dc2626; font-size: 12px; }
  .pf-alert { background: #fdecec; color: #b42318; border-radius: 8px; padding: 10px 14px; font-size: 12.5px; font-weight: 600; margin-bottom: 18px; }

  .pf-drop { position: relative; height: 200px; display: flex; align-items: center; justify-content: center; border: 1.5px dashed #aab3c8; border-radius: 10px; background: #fff; cursor: pointer; overflow: hidden; text-align: center; }
  .pf-drop:hover, .pf-drop.seret { border-color: #284686; background: #eef3ff; }
  .pf-drop-isi { padding: 0 12px; color: #6b7280; font-size: 12px; }
  .pf-drop-isi svg { width: 28px; height: 28px; display: block; margin: 0 auto 8px; color: #374151; }
  .pf-drop-isi small { display: block; margin-top: 6px; font-size: 10.5px; color: #9aa1b2; }
  .pf-drop img { display: none; position: absolute; inset: 0; width: 100%; height: 100%; object-fit: contain; background: #fff; }
  .pf-ganti { display: none; position: absolute; left: 50%; bottom: 8px; transform: translateX(-50%); padding: 4px 12px; border-radius: 999px; background: rgba(17, 24, 39, .72); color: #fff; font-size: 11px; white-space: nowrap; }
  .pf-drop.ada-foto img, .pf-drop.ada-foto .pf-ganti { display: block; }
  .pf-drop.ada-foto .pf-drop-isi { display: none; }

  .pf-aksi { display: flex; justify-content: flex-end; gap: 10px; margin-top: 26px; padding-top: 20px; border-top: 1px solid #e3e7f1; }
  .pf-btn { height: 38px; padding: 0 20px; display: inline-flex; align-items: center; gap: 8px; border: 1px solid transparent; border-radius: 8px; font: inherit; font-size: 12.5px; font-weight: 600; text-decoration: none; cursor: pointer; }
  .pf-btn svg { width: 14px; height: 14px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
  .pf-batal { background: #fff; border-color: #d9dee9; color: #111827; }
  .pf-batal:hover { background: #f3f4f8; }
  .pf-simpan { background: #284686; color: #fff; }
  .pf-simpan:hover { background: #1f3a73; }

  @media (max-width: 900px) {
    .pf-kartu { width: 100%; margin-left: 0; }
    .pf-sub { margin-left: 0; }
    .pf-grid, .pf-dua { grid-template-columns: 1fr; }
  }
</style>
@endpush

@section('content')
<div class="art">
  <h1 class="art-judul">{{ $edit ? 'Edit Produk' : 'Tambah Produk' }}</h1>
  <p class="pf-sub">{{ $edit ? 'Perbarui informasi produk atau jasa' : 'Masukkan informasi produk atau jasa karya siswa' }}</p>

  <form id="formProduk" class="pf-kartu" method="POST" enctype="multipart/form-data"
        action="{{ $edit ? route('produk.update', $produk) : route('produk.store') }}">
    @csrf
    @if ($edit)
      @method('PUT')
    @endif

    @if ($errors->any())
      <div class="pf-alert" role="alert">Data belum lengkap. Periksa kolom yang ditandai di bawah.</div>
    @endif

    <div class="pf-grid">
      {{-- Foto produk --}}
      <div>
        <span class="pf-label">Foto Produk</span>
        <div class="pf-drop {{ $punyaFoto ? 'ada-foto' : '' }}" id="drop" tabindex="0" role="button" aria-label="Unggah foto produk">
          <img id="dzImg" alt="Pratinjau foto" @if ($punyaFoto) src="{{ $produk->foto_url }}" @endif>
          <div class="pf-drop-isi">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 18a4 4 0 0 1-.6-7.96A5.5 5.5 0 0 1 17 8.5a4.5 4.5 0 0 1 .5 9.5"/><path d="M12 12v8M9 15l3-3 3 3"/></svg>
            <b>Upload a file</b> or drag and drop
            <small>JPG, PNG, WEBP up to 2MB</small>
          </div>
          <span class="pf-ganti">Klik atau seret untuk mengganti foto</span>
        </div>
        <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp" hidden>
        @error('foto') <div class="pf-err">{{ $message }}</div> @enderror
      </div>

      {{-- Data produk --}}
      <div>
        <label class="pf-label" for="nama">Nama Produk</label>
        <input type="text" id="nama" name="nama" class="pf-input" maxlength="255"
               placeholder="Contoh: Wearpack PPLG" value="{{ old('nama', $produk->nama) }}">
        @error('nama') <div class="pf-err">{{ $message }}</div> @enderror

        <div class="pf-kolom">
          <label class="pf-label" for="deskripsi">Deskripsi Singkat</label>
          <input type="text" id="deskripsi" name="deskripsi" class="pf-input" maxlength="255"
                 placeholder="Contoh: Wearpack / baju praktik" value="{{ old('deskripsi', $produk->deskripsi) }}">
          @error('deskripsi') <div class="pf-err">{{ $message }}</div> @enderror
        </div>

        <div class="pf-dua">
          <div>
            <label class="pf-label" for="kategori">Kategori</label>
            <input type="text" id="kategori" name="kategori" class="pf-input" list="daftarKategori" maxlength="100"
                   placeholder="Pilih atau ketik kategori" value="{{ old('kategori', $produk->kategori) }}" autocomplete="off">
            <datalist id="daftarKategori">
              @foreach ($kategoriList as $k)
                <option value="{{ $k }}">
              @endforeach
            </datalist>
            @error('kategori') <div class="pf-err">{{ $message }}</div> @enderror
          </div>
          <div>
            <label class="pf-label" for="harga">Harga (Rp)</label>
            <input type="number" id="harga" name="harga" class="pf-input" min="0" step="1"
                   placeholder="80000" value="{{ old('harga', $produk->harga) }}">
            @error('harga') <div class="pf-err">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="pf-kolom">
          <label class="pf-label" for="stok">Stok</label>
          <select id="stok" name="stok" class="pf-input">
            <option value="Tersedia" @selected($stokNow === 'Tersedia')>Tersedia</option>
            <option value="Habis" @selected($stokNow === 'Habis')>Habis</option>
          </select>
          @error('stok') <div class="pf-err">{{ $message }}</div> @enderror
        </div>
      </div>
    </div>

    <div class="pf-aksi">
      <a href="{{ route('produk.index') }}" class="pf-btn pf-batal">Batal</a>
      <button type="submit" class="pf-btn pf-simpan">
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