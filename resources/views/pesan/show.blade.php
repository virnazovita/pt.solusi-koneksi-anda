@extends('layouts.admin')

@section('title', 'Detail & Balas Pesan')

@push('styles')
<style>
  .pd-sub { margin: 8px 0 0 calc(var(--v) * 15); font-size: 12.5px; color: #6b7280; }
  .pd-grid { display: grid; grid-template-columns: 38fr 62fr; gap: 18px; width: calc(var(--v) * 1040); max-width: 100%; margin-top: calc(var(--v) * 30); align-items: start; }

  .pd-kartu { background: #f7f8fc; border: 1px solid #e1e5f0; border-radius: 10px; overflow: hidden; }
  .pd-isi { padding: 22px; }
  .pd-label { display: block; margin: 0 0 8px; font-size: 10.5px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: #6b7280; }
  .pd-kepala { display: flex; justify-content: space-between; align-items: center; gap: 10px; margin-bottom: 4px; }
  .pd-dari { margin: 0 0 18px; padding-bottom: 14px; border-bottom: 1px solid #e3e7f1; font-size: 11.5px; color: #6b7280; }
  .pd-dari b { color: #111827; }

  .pd-badge { font-size: 10.5px; font-weight: 600; padding: 3px 12px; border-radius: 999px; }
  .pd-badge.Baru { background: #fef3c7; color: #b45309; }
  .pd-badge.Dibaca { background: #d9f7e4; color: #15803d; }
  .pd-badge.Dibalas { background: #dbe5fb; color: #2b5bd7; }

  .pd-box { background: #eeeeee; border-radius: 8px; padding: 12px 14px; font-size: 13px; color: #111827; line-height: 1.6; margin-bottom: 18px; word-break: break-word; }
  .pd-box.besar { min-height: 150px; white-space: pre-line; }
  .pd-kaki { border-top: 1px solid #e3e7f1; padding: 12px 22px; text-align: right; }
  .pd-hapus { background: none; border: 0; padding: 0; font: inherit; font-size: 12.5px; font-weight: 700; color: #dc2626; cursor: pointer; }
  .pd-hapus:hover { text-decoration: underline; }

  .pd-balas { background: #fff; padding: 26px; }
  .pd-balas h2 { margin: 0; font-size: 16px; font-weight: 800; color: #111827; }
  .pd-balas .pd-ket { margin: 4px 0 20px; font-size: 12px; color: #6b7280; }
  .pd-dua { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
  .pd-field label { display: block; margin-bottom: 6px; font-size: 12px; font-weight: 700; color: #111827; }
  .pd-input { display: block; width: 100%; height: 40px; padding: 0 12px; border: 1px solid #d9dee9; border-radius: 8px; background: #fff; font: inherit; font-size: 13px; color: #111827; outline: none; }
  .pd-input[readonly] { background: #eeeeee; color: #4b5563; }
  .pd-input:not([readonly]):focus { border-color: #284686; box-shadow: 0 0 0 3px rgba(40, 70, 134, .15); }

  .pd-tools { display: flex; gap: 2px; padding: 6px 8px; background: #eef1f8; border: 1px solid #e1e6f0; border-bottom: 0; border-radius: 8px 8px 0 0; }
  .pd-tools button { width: 30px; height: 28px; border: 0; background: none; border-radius: 6px; cursor: pointer; color: #33405f; font: 700 13px inherit; display: grid; place-items: center; }
  .pd-tools button:hover { background: #dde4f3; }
  .pd-tools svg { width: 15px; height: 15px; }
  .pd-tools .sep { width: 1px; background: #e1e6f0; margin: 4px 6px; }
  #editorBalas { min-height: 220px; padding: 14px 16px; background: #fff; border: 1px solid #e1e6f0; border-radius: 0 0 8px 8px; font-size: 13.5px; line-height: 1.7; outline: none; }
  #editorBalas:focus { border-color: #284686; }
  #editorBalas.kosong::before { content: 'Tulis balasan di sini...'; color: #a3abc0; }
  #editorBalas ul, #editorBalas ol { padding-left: 22px; }
  #editorBalas a { color: #2563eb; }

  .pd-err { margin-top: 6px; color: #dc2626; font-size: 12px; font-weight: 600; }
  .pd-alert { background: #fdecec; color: #b42318; border-radius: 8px; padding: 10px 14px; font-size: 12.5px; font-weight: 600; margin-bottom: 16px; word-break: break-word; }
  .pd-info { background: #eef3ff; color: #284686; border-radius: 8px; padding: 10px 14px; font-size: 12.5px; margin-bottom: 16px; }

  .pd-aksi { display: flex; justify-content: flex-end; gap: 10px; margin-top: 22px; }
  .pd-btn { height: 38px; padding: 0 18px; display: inline-flex; align-items: center; gap: 8px; border: 1px solid transparent; border-radius: 8px; font: inherit; font-size: 12.5px; font-weight: 700; text-decoration: none; cursor: pointer; }
  .pd-btn svg { width: 15px; height: 15px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
  .pd-batal { background: #fff; border-color: #d9dee9; color: #111827; }
  .pd-batal:hover { background: #f3f4f8; }
  .pd-kirim { background: #0a53d9; color: #fff; box-shadow: 0 6px 14px rgba(10, 83, 217, .25); }
  .pd-kirim:hover { background: #0846b8; }

  @media (max-width: 900px) {
    .pd-grid { grid-template-columns: 1fr; }
    .pd-dua { grid-template-columns: 1fr; }
    .pd-sub { margin-left: 0; }
  }
</style>
@endpush

@section('content')
@php
    $aman = '<p><br><b><strong><i><em><u><ul><ol><li><a><div><span>';
    $subjekBalasan = old('balasan_subjek', $pesan->balasan_subjek ?: 'Re: ' . $pesan->subjek);
    $isiBalasan    = (string) old('balasan_isi', '');
    $status        = $pesan->status_label;
@endphp

<div class="art">
  <h1 class="art-judul">Detail &amp; Balas Pesan</h1>
  <p class="pd-sub">Baca pesan dari pengunjung dan kirim balasan melalui email sekolah.</p>

  @if (session('sukses'))
    <div class="alert-sukses" style="margin-top:16px;width:calc(var(--v) * 1040);margin-left:0">{{ session('sukses') }}</div>
  @endif

  <div class="pd-grid">

    {{-- ===== Kiri: pesan masuk ===== --}}
    <div class="pd-kartu">
      <div class="pd-isi">
        <div class="pd-kepala">
          <span class="pd-label" style="margin:0">Pesan Masuk</span>
          <span class="pd-badge {{ $status }}">{{ $status }}</span>
        </div>
        <p class="pd-dari">
          Dari <b>{{ $pesan->nama }}</b> &middot; {{ $pesan->email }}<br>
          {{ $pesan->created_at->locale('id')->translatedFormat('d F Y, H.i') }}
        </p>

        <span class="pd-label">Subjek</span>
        <div class="pd-box">{{ $pesan->subjek }}</div>

        <span class="pd-label">Isi Pesan</span>
        <div class="pd-box besar">{{ $pesan->pesan }}</div>
      </div>

      <div class="pd-kaki">
        <form action="{{ route('kontak.destroy', $pesan) }}" method="POST"
              onsubmit="return confirm('Yakin ingin menghapus pesan ini?')">
          @csrf
          @method('DELETE')
          <button type="submit" class="pd-hapus">Hapus Pesan</button>
        </form>
      </div>
    </div>

    {{-- ===== Kanan: formulir balas ===== --}}
    <div class="pd-kartu pd-balas">
      <h2>Formulir Balas Pesan</h2>
      <p class="pd-ket">Kirim tanggapan langsung melalui server sekolah</p>

      @if ($errors->has('kirim'))
        <div class="pd-alert" role="alert">{{ $errors->first('kirim') }}</div>
      @endif

      @if ($pesan->dibalas_at)
        <div class="pd-info">Pesan ini sudah dibalas pada {{ $pesan->dibalas_at->locale('id')->translatedFormat('d F Y, H.i') }}. Kamu masih bisa mengirim balasan lagi.</div>
      @endif

      <form id="formBalas" method="POST" action="{{ route('kontak.balas', $pesan) }}">
        @csrf

        <div class="pd-dua">
          <div class="pd-field">
            <label for="penerima">Penerima</label>
            <input type="text" id="penerima" class="pd-input" value="{{ $pesan->email }}" readonly>
          </div>
          <div class="pd-field">
            <label for="pengirim">Kirim Sebagai</label>
            <input type="text" id="pengirim" class="pd-input" value="{{ $pengirim['nama'] }} &lt;{{ $pengirim['email'] }}&gt;" readonly>
          </div>
        </div>

        <div class="pd-field" style="margin-bottom:16px">
          <label for="balasan_subjek">Subjek Email Balasan</label>
          <input type="text" id="balasan_subjek" name="balasan_subjek" class="pd-input" maxlength="255" value="{{ $subjekBalasan }}">
          @error('balasan_subjek') <div class="pd-err">{{ $message }}</div> @enderror
        </div>

        <div class="pd-field">
          <label>Isi Pesan Balasan</label>
          <div class="pd-tools" id="toolsBalas">
            <button type="button" data-cmd="bold" title="Tebal">B</button>
            <button type="button" data-cmd="italic" title="Miring" style="font-style:italic">I</button>
            <button type="button" data-cmd="underline" title="Garis bawah" style="text-decoration:underline">U</button>
            <span class="sep"></span>
            <button type="button" data-cmd="insertUnorderedList" title="Daftar poin"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M9 6h11M9 12h11M9 18h11M4 6h.01M4 12h.01M4 18h.01"/></svg></button>
            <button type="button" data-cmd="insertOrderedList" title="Daftar nomor"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M10 6h10M10 12h10M10 18h10M4 5h1v4M4 15c1-1 2 0 1 1l-1 1h2"/></svg></button>
            <span class="sep"></span>
            <button type="button" data-cmd="createLink" title="Tautan"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 14a5 5 0 0 0 7 0l3-3a5 5 0 0 0-7-7l-1 1M14 10a5 5 0 0 0-7 0l-3 3a5 5 0 0 0 7 7l1-1"/></svg></button>
          </div>
          <div id="editorBalas" contenteditable="true">{!! strip_tags($isiBalasan, $aman) !!}</div>
          <input type="hidden" name="balasan_isi" id="balasan_isi">
          @error('balasan_isi') <div class="pd-err">{{ $message }}</div> @enderror
          <div class="pd-err" id="isiKosong" style="display:none">Isi pesan balasan wajib diisi.</div>
        </div>

        <div class="pd-aksi">
          <a href="{{ route('kontak.index') }}" class="pd-btn pd-batal">Batal</a>
          <button type="submit" class="pd-btn pd-kirim">
            <svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
            Kirim Balasan Sekarang
          </button>
        </div>
      </form>
    </div>

  </div>
</div>

<script>
(function () {
  var ed = document.getElementById('editorBalas'), hidden = document.getElementById('balasan_isi'), err = document.getElementById('isiKosong');
  document.execCommand('defaultParagraphSeparator', false, 'p');

  function kosong() { return ed.innerText.trim() === ''; }
  function segarkan() { ed.classList.toggle('kosong', kosong()); }
  ed.addEventListener('input', segarkan); segarkan();

  document.getElementById('toolsBalas').addEventListener('mousedown', function (e) {
    var b = e.target.closest('button'); if (!b) return;
    e.preventDefault(); ed.focus();
    var cmd = b.dataset.cmd, val = null;
    if (cmd === 'createLink') {
      val = prompt('Masukkan alamat tautan (https://...)');
      if (!val || !/^https?:\/\//i.test(val)) return;
    }
    document.execCommand(cmd, false, val);
    segarkan();
  });

  document.getElementById('formBalas').addEventListener('submit', function (e) {
    if (kosong()) { e.preventDefault(); err.style.display = 'block'; ed.focus(); return; }
    hidden.value = ed.innerHTML;
  });
})();
</script>
@endsection