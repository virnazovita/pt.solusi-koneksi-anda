@extends('layouts.admin')

@section('title', 'Kelola Galeri')

@section('content')
@php
    // Nomor halaman mengikuti jumlah halaman asli dari database
    $sekarang = $daftarGaleri->currentPage();
    $terakhir = $daftarGaleri->lastPage();
@endphp

<div class="art">

  <div class="art-head">
    <div>
      <h1 class="art-judul">Kelola Galeri</h1>
      <div class="art-crumb"><a href="{{ url('/dashboard') }}">Dashboard</a> / <b>Galeri</b></div>
    </div>

    <a href="{{ route('galeri.create') }}" class="art-tombol">
      <svg viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
      Tambah Foto
    </a>
  </div>

  @if (session('sukses'))
    <div class="alert-sukses">{{ session('sukses') }}</div>
  @endif

  <div class="art-kartu">
    <table class="art-tabel">
      <colgroup>
        <col style="width:13.66%"><col style="width:42.19%"><col style="width:12.79%"><col style="width:16.17%"><col style="width:15.19%">
      </colgroup>
      <thead>
        <tr>
          <th class="k-foto">Foto</th>
          <th>Judul</th>
          <th>Kategori</th>
          <th>Tanggal</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($daftarGaleri as $g)
          <tr>
            <td class="k-foto">
              <div class="art-foto" @if ($g->foto_url) style="background-image:url('{{ $g->foto_url }}')" @endif></div>
            </td>
            <td class="t-judul">{{ $g->judul }}</td>
            <td><span class="art-badge">{{ $g->kategori }}</span></td>
            <td class="t-tanggal">{{ $g->tanggal->locale('id')->translatedFormat('d F Y') }}</td>
            <td>
              <div class="art-aksi">
                {{-- Edit --}}
                <a href="{{ route('galeri.edit', $g) }}" class="art-ikon art-edit" title="Edit" aria-label="Edit foto">
                  <svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                </a>

                {{-- Hapus (dengan konfirmasi) --}}
                <form action="{{ route('galeri.destroy', $g) }}" method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus foto ini?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="art-ikon art-hapus" title="Hapus" aria-label="Hapus foto">
                    <svg viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="art-kosong">Belum ada foto galeri.</td>
          </tr>
        @endforelse
      </tbody>
    </table>

    {{-- Strip bawah kartu: nomor halaman (1 2 3 ... 8) --}}
    <div class="art-kaki">
      @if ($terakhir > 1)
        @php
          $daftarHalaman = collect([1, $terakhir])
              ->merge(range(max(1, $sekarang - 1), min($terakhir, $sekarang + 1)))
              ->unique()
              ->sort()
              ->values();
          $sebelumnya = 0;
        @endphp
        <div class="pagination">
          @foreach ($daftarHalaman as $h)
            @if ($h - $sebelumnya > 1)
              <span class="titik">...</span>
            @endif

            @if ($h == $sekarang)
              <span class="hal aktif" aria-current="page">{{ $h }}</span>
            @else
              <a href="{{ $daftarGaleri->url($h) }}" class="hal">{{ $h }}</a>
            @endif

            @php $sebelumnya = $h; @endphp
          @endforeach
        </div>
      @endif
    </div>
  </div>

</div>
@endsection