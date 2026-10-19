@extends('layouts.admin')

@section('title', 'Kelola Kontak')

@push('styles')
<style>
  .kt-sub { margin: 8px 0 28px calc(var(--v) * 15); font-size: 12.5px; color: #6b7280; }
  .art-tabel th.kt-pad { padding: 0 14px 0 0; }
  .art-tabel td.kt-pad { padding: 14px 14px 14px 0; }
  .art-tabel th.kt-awal, .art-tabel td.kt-awal { padding-left: calc(var(--v) * 40); }
  .art-tabel tbody tr:hover td { background: #f1f4fb; }
  .kt-nama { font-weight: 700; }
  .kt-status { display: inline-block; font-size: 11px; font-weight: 600; padding: 4px 12px; border-radius: 999px; white-space: nowrap; }
  .kt-status.sudah { background: #dff7e8; color: #15803d; }
  .kt-status.belum { background: #fef3c7; color: #b45309; }
  .kt-baru { display: inline-block; width: 7px; height: 7px; margin-right: 6px; border-radius: 50%; background: #2563eb; vertical-align: middle; }
  .kt-email { color: #1f2937; word-break: break-all; }
  .kt-pesan { color: #6b7280; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
  .kt-tgl { font-weight: 700; white-space: nowrap; }
</style>
@endpush

@section('content')
@php
    $sekarang = $daftarPesan->currentPage();
    $terakhir = $daftarPesan->lastPage();
@endphp

<div class="art">

  <div class="art-head">
    <div>
      <h1 class="art-judul">Kelola Kontak</h1>
      <p class="kt-sub">Daftar pesan yang dikirim pengunjung lewat halaman kontak.</p>
    </div>
  </div>

  @if (session('sukses'))
    <div class="alert-sukses">{{ session('sukses') }}</div>
  @endif

  <div class="art-kartu">
    <table class="art-tabel">
      <colgroup>
        <col style="width:12%"><col style="width:21%"><col style="width:10%"><col style="width:18%"><col style="width:16%"><col style="width:14%"><col style="width:9%">
      </colgroup>
      <thead>
        <tr>
          <th class="kt-pad kt-awal">Nama</th>
          <th class="kt-pad">Email</th>
          <th class="kt-pad">Subjek</th>
          <th class="kt-pad">Pesan</th>
          <th class="kt-pad">Tanggal</th>
          <th class="kt-pad">Status</th>
          <th class="kt-pad">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($daftarPesan as $p)
          <tr>
            <td class="kt-pad kt-awal kt-nama">
              @if (! $p->dibaca_at)<span class="kt-baru" title="Belum dibaca"></span>@endif
              {{ $p->nama }}
            </td>
            <td class="kt-pad kt-email">{{ $p->email }}</td>
            <td class="kt-pad">{{ $p->subjek }}</td>
            <td class="kt-pad"><div class="kt-pesan" title="{{ $p->pesan }}">{{ $p->pesan }}</div></td>
            <td class="kt-pad kt-tgl">{{ $p->created_at->locale('id')->translatedFormat('d M Y, H.i') }}</td>
            <td class="kt-pad">
              @if ($p->dibalas_at)
                <span class="kt-status sudah">Sudah Dibalas</span>
              @else
                <span class="kt-status belum">Belum Dibalas</span>
              @endif
            </td>
            <td class="kt-pad">
              <div class="art-aksi">
                <a href="{{ route('kontak.show', $p) }}" class="art-ikon art-edit" title="Balas" aria-label="Buka detail dan balas pesan dari {{ $p->nama }}">
                  <svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                </a>
                <form action="{{ route('kontak.destroy', $p) }}" method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus pesan ini?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="art-ikon art-hapus" title="Hapus" aria-label="Hapus pesan">
                    <svg viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="art-kosong">Belum ada pesan masuk.</td>
          </tr>
        @endforelse
      </tbody>
    </table>

    <div class="art-kaki">
      @if ($terakhir > 1)
        @php
          $daftarHalaman = collect([1, $terakhir])
              ->merge(range(max(1, $sekarang - 1), min($terakhir, $sekarang + 1)))
              ->unique()->sort()->values();
          $sebelumnya = 0;
        @endphp
        <div class="pagination">
          @foreach ($daftarHalaman as $h)
            @if ($h - $sebelumnya > 1) <span class="titik">...</span> @endif
            @if ($h == $sekarang)
              <span class="hal aktif" aria-current="page">{{ $h }}</span>
            @else
              <a href="{{ $daftarPesan->url($h) }}" class="hal">{{ $h }}</a>
            @endif
            @php $sebelumnya = $h; @endphp
          @endforeach
        </div>
      @endif
    </div>
  </div>

</div>
@endsection