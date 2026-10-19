@extends('layouts.admin')

@section('title', 'Profil Perusahaan')

@push('styles')
<style>
  .pf-sub { margin: 6px 0 0; color: #6b7280; font-size: 13.5px; }
  .pf-form { max-width: 921px; margin-top: 24px; display: grid; gap: 20px; }
  .pf-kartu { padding: 24px 28px; background: #f7f8fc; border: 1px solid #e1e5f0; border-radius: 12px; }
  .pf-kartu h2 { margin: 0 0 16px; font-size: 15px; color: #111827; }
  .pf-baris { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
  .pf-grup { margin-bottom: 16px; }
  .pf-grup:last-child { margin-bottom: 0; }
  .pf-label { display: block; margin-bottom: 8px; font-size: 12px; font-weight: 700; color: #111827; }
  .pf-input { display: block; width: 100%; box-sizing: border-box; padding: 10px 12px; border: 1px solid #d9dee9; border-radius: 8px; background: #fff; font: inherit; font-size: 13px; color: #111827; outline: none; }
  textarea.pf-input { min-height: 120px; resize: vertical; line-height: 1.5; }
  .pf-input:focus { border-color: #284686; box-shadow: 0 0 0 3px rgba(40, 70, 134, .15); }
  .pf-bantu { margin-top: 5px; color: #6b7280; font-size: 11.5px; }
  .pf-err { margin-top: 5px; color: #dc2626; font-size: 12px; }
  .pf-alert { background: #fdecec; color: #b42318; border-radius: 8px; padding: 10px 14px; font-size: 12.5px; font-weight: 600; }
  .pf-foto { display: block; margin-top: 10px; width: 140px; height: 100px; object-fit: cover; border-radius: 8px; border: 1px solid #e1e5f0; }
  .pf-aksi { display: flex; justify-content: flex-end; gap: 10px; }
  .pf-btn { height: 38px; padding: 0 22px; display: inline-flex; align-items: center; border: 1px solid transparent; border-radius: 8px; font: inherit; font-size: 12.5px; font-weight: 600; text-decoration: none; cursor: pointer; }
  .pf-batal { background: #fff; border-color: #d9dee9; color: #111827; }
  .pf-simpan { background: #284686; color: #fff; }
  .pf-simpan:hover { background: #1f3a73; }
  @media (max-width: 900px) { .pf-baris { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="art">
  <h1 class="art-judul">Profil Perusahaan</h1>
  <p class="pf-sub">Perbarui informasi yang tampil di halaman Profil dan Kontak website.</p>

  @if (session('sukses'))
    <div class="alert-sukses">{{ session('sukses') }}</div>
  @endif

  <form class="pf-form" method="POST" enctype="multipart/form-data" action="{{ route('admin.profil.update') }}">
    @csrf
    @method('PUT')

    @if ($errors->any())
      <div class="pf-alert" role="alert">Data belum lengkap. Periksa kolom yang ditandai di bawah.</div>
    @endif

    {{-- ===== Identitas ===== --}}
    <div class="pf-kartu">
      <h2>Identitas Perusahaan</h2>
      <div class="pf-baris">
        <div class="pf-grup">
          <label class="pf-label" for="nama_perusahaan">Nama Perusahaan</label>
          <input type="text" id="nama_perusahaan" name="nama_perusahaan" class="pf-input" maxlength="255" value="{{ old('nama_perusahaan', $profil->nama_perusahaan) }}">
          @error('nama_perusahaan') <div class="pf-err">{{ $message }}</div> @enderror
        </div>
        <div class="pf-grup">
          <label class="pf-label" for="slogan">Slogan</label>
          <input type="text" id="slogan" name="slogan" class="pf-input" maxlength="255" value="{{ old('slogan', $profil->slogan) }}">
          @error('slogan') <div class="pf-err">{{ $message }}</div> @enderror
        </div>
      </div>
      <div class="pf-grup">
        <label class="pf-label" for="moto">Moto</label>
        <input type="text" id="moto" name="moto" class="pf-input" maxlength="255" value="{{ old('moto', $profil->moto) }}">
        @error('moto') <div class="pf-err">{{ $message }}</div> @enderror
      </div>
    </div>

    {{-- ===== Sejarah ===== --}}
    <div class="pf-kartu">
      <h2>Sejarah Perusahaan</h2>
      <div class="pf-grup">
        <label class="pf-label" for="sejarah">Sejarah</label>
        <textarea id="sejarah" name="sejarah" class="pf-input">{{ old('sejarah', $profil->sejarah) }}</textarea>
        @error('sejarah') <div class="pf-err">{{ $message }}</div> @enderror
      </div>
      <div class="pf-grup">
        <label class="pf-label" for="foto_sejarah">Foto (opsional, JPG/PNG/WEBP maks. 2 MB)</label>
        <input type="file" id="foto_sejarah" name="foto_sejarah" class="pf-input" accept="image/jpeg,image/png,image/webp">
        @error('foto_sejarah') <div class="pf-err">{{ $message }}</div> @enderror
        @if ($profil->foto_sejarah_url)
          <img class="pf-foto" src="{{ $profil->foto_sejarah_url }}" alt="Foto sejarah saat ini">
        @endif
      </div>
    </div>

    {{-- ===== Sambutan direktur ===== --}}
    <div class="pf-kartu">
      <h2>Sambutan Direktur</h2>
      <div class="pf-baris">
        <div class="pf-grup">
          <label class="pf-label" for="nama_direktur">Nama Direktur</label>
          <input type="text" id="nama_direktur" name="nama_direktur" class="pf-input" maxlength="255" value="{{ old('nama_direktur', $profil->nama_direktur) }}">
          @error('nama_direktur') <div class="pf-err">{{ $message }}</div> @enderror
        </div>
        <div class="pf-grup">
          <label class="pf-label" for="jabatan_direktur">Jabatan</label>
          <input type="text" id="jabatan_direktur" name="jabatan_direktur" class="pf-input" maxlength="100" value="{{ old('jabatan_direktur', $profil->jabatan_direktur ?? 'Direktur Utama') }}">
          @error('jabatan_direktur') <div class="pf-err">{{ $message }}</div> @enderror
        </div>
      </div>
      <div class="pf-grup">
        <label class="pf-label" for="sambutan">Sambutan</label>
        <textarea id="sambutan" name="sambutan" class="pf-input">{{ old('sambutan', $profil->sambutan) }}</textarea>
        @error('sambutan') <div class="pf-err">{{ $message }}</div> @enderror
      </div>
      <div class="pf-grup">
        <label class="pf-label" for="foto_direktur">Foto Direktur (opsional)</label>
        <input type="file" id="foto_direktur" name="foto_direktur" class="pf-input" accept="image/jpeg,image/png,image/webp">
        @error('foto_direktur') <div class="pf-err">{{ $message }}</div> @enderror
        @if ($profil->foto_direktur_url)
          <img class="pf-foto" src="{{ $profil->foto_direktur_url }}" alt="Foto direktur saat ini">
        @endif
      </div>
    </div>

    {{-- ===== Visi, misi, struktur ===== --}}
    <div class="pf-kartu">
      <h2>Visi, Misi, dan Struktur Organisasi</h2>
      <div class="pf-grup">
        <label class="pf-label" for="visi">Visi</label>
        <textarea id="visi" name="visi" class="pf-input">{{ old('visi', $profil->visi) }}</textarea>
        @error('visi') <div class="pf-err">{{ $message }}</div> @enderror
      </div>
      <div class="pf-grup">
        <label class="pf-label" for="misi">Misi</label>
        <textarea id="misi" name="misi" class="pf-input">{{ old('misi', $profil->misi) }}</textarea>
        <div class="pf-bantu">Tulis satu misi per baris (tekan Enter untuk misi berikutnya).</div>
        @error('misi') <div class="pf-err">{{ $message }}</div> @enderror
      </div>
      <div class="pf-grup">
        <label class="pf-label" for="struktur">Struktur Organisasi</label>
        <textarea id="struktur" name="struktur" class="pf-input">{{ old('struktur', $profil->struktur) }}</textarea>
        <div class="pf-bantu">Satu orang per baris dengan format: Jabatan | Nama. Contoh: Manajer Teknis | Rina Sari</div>
        @error('struktur') <div class="pf-err">{{ $message }}</div> @enderror
      </div>
    </div>

    {{-- ===== Kontak ===== --}}
    <div class="pf-kartu">
      <h2>Informasi Kontak</h2>
      <div class="pf-grup">
        <label class="pf-label" for="alamat">Alamat</label>
        <input type="text" id="alamat" name="alamat" class="pf-input" maxlength="500" value="{{ old('alamat', $profil->alamat) }}">
        @error('alamat') <div class="pf-err">{{ $message }}</div> @enderror
      </div>
      <div class="pf-baris">
        <div class="pf-grup">
          <label class="pf-label" for="email">Email</label>
          <input type="email" id="email" name="email" class="pf-input" maxlength="255" value="{{ old('email', $profil->email) }}">
          @error('email') <div class="pf-err">{{ $message }}</div> @enderror
        </div>
        <div class="pf-grup">
          <label class="pf-label" for="telepon">Telepon</label>
          <input type="text" id="telepon" name="telepon" class="pf-input" maxlength="30" value="{{ old('telepon', $profil->telepon) }}">
          @error('telepon') <div class="pf-err">{{ $message }}</div> @enderror
        </div>
      </div>
    </div>

    <div class="pf-aksi">
      <a href="{{ url('/dashboard') }}" class="pf-btn pf-batal">Batal</a>
      <button type="submit" class="pf-btn pf-simpan">Simpan Perubahan</button>
    </div>
  </form>
</div>
@endsection