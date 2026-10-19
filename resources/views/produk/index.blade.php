@extends('layouts.admin')

@section('title', 'Kelola Produk')

@push('styles')
<style>
  .pr-kartu { max-width: 960px; margin-top: 6px; padding: 24px; background: #f7f8fc; border: 1px solid #e1e5f0; border-radius: 10px; }
  .pr-kepala h2 { margin: 0; font-size: 15px; font-weight: 800; color: #111827; }
  .pr-kepala p { margin: 4px 0 0; font-size: 11.5px; color: #6b7280; }
  .pr-garis { border: 0; border-top: 1px solid #e3e7f1; margin: 16px 0; }

  .pr-bar { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 14px; flex-wrap: wrap; }
  .pr-bar select { height: 36px; min-width: 150px; padding: 0 12px; border: 1px solid #d9dee9; border-radius: 8px; background: #fff; font: inherit; font-size: 12.5px; color: #111827; }
  .pr-tambah { display: inline-flex; align-items: center; gap: 8px; height: 36px; padding: 0 18px; border-radius: 8px; background: #284686; color: #fff; font-size: 12.5px; font-weight: 600; text-decoration: none; }
  .pr-tambah:hover { background: #1f3a73; }
  .pr-tambah svg { width: 14px; height: 14px; fill: none; stroke: currentColor; stroke-width: 2.4; stroke-linecap: round; }

  .pr-box { background: #fff; border: 1px solid #e3e7f1; border-radius: 8px; overflow: hidden; }
  .pr-tabel { width: 100%; border-collapse: collapse; table-layout: fixed; }
  .pr-tabel th { height: 38px; background: #eff2f9; text-align: left; font-size: 9.5px; letter-spacing: .07em; text-transform: uppercase; color: #5b6475; font-weight: 700; padding: 0 8px; }
  .pr-tabel td { padding: 9px 8px; border-top: 1px solid #edf0f6; vertical-align: middle; font-size: 12px; color: #111827; }
  .pr-tabel .c-foto { padding-left: 24px; }
  .pr-foto { width: 40px; height: 40px; border-radius: 6px; border: 1px solid #e3e7f1; background: #fff center / contain no-repeat; }
  .pr-nama { font-weight: 800; font-style: italic; font-size: 12.5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .pr-desk { margin-top: 2px; font-size: 10.5px; color: #6b7280; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .pr-harga { color: #374151; }
  .pr-stok { display: inline-flex; align-items: center; gap: 6px; font-size: 11.5px; color: #374151; }
  .pr-stok::before { content: ""; width: 7px; height: 7px; border-radius: 50%; background: #22c55e; }
  .pr-stok.habis::before { background: #ef4444; }
  .pr-kosong { text-align: center; color: #9ca3af; padding: 48px 0 !important; }
  .pr-kaki { min-height: 48px; border-top: 1px solid #edf0f6; display: flex; align-items: center; justify-content: center; padding: 6px 16px; }

  @media (max-width: 900px) { .pr-tabel .c-foto { padding-left: 12px; } }
</style>
@endpush

@section('content')
@php
    $sekarang = $daftarProduk->currentPage();
    $terakhir = $daftarProduk->lastPage();
@endphp

<div class="art">

  @if (session('sukses'))
    <div class="alert-sukses" style="max-width:960px">{{ session('sukses') }}</div>
  @endif

  <div class="pr-kartu">
    <div class="pr-kepala">
      <h2>Kelola Produk</h2>
      <p>Kelola daftar produk dan jasa karya siswa.</p>
    </div>
    <hr class="pr-garis">

    <div class="pr-bar">
      <form method="GET" action="{{ route('produk.index') }}">
        <select name="kategori" onchange="this.form.submit()" aria-label="Filter kategori">
          <option value="">Semua Kategori</option>
          @foreach ($kategoriList as $k)
            <option value="{{ $k }}" @selected(request('kategori') === $k)>{{ $k }}</option>
          @endforeach
        </select>
      </form>

      <a href="{{ route('produk.create') }}" class="pr-tambah">
        <svg viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
        Tambah Produk
      </a>
    </div>

    <div class="pr-box">
      <table class="pr-tabel">
        <colgroup>
          <col style="width:13%"><col style="width:30%"><col style="width:13%"><col style="width:14%"><col style="width:15%"><col style="width:15%">
        </colgroup>
        <thead>
          <tr>
            <th class="c-foto">Foto Produk</th>
            <th>Nama Produk</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($daftarProduk as $p)
            <tr>
              <td class="c-foto">
                <div class="pr-foto" @if ($p->foto_url) style="background-image:url('{{ $p->foto_url }}')" @endif></div>
              </td>
              <td>
                <div class="pr-nama" title="{{ $p->nama }}">{{ $p->nama }}</div>
                @if ($p->deskripsi)
                  <div class="pr-desk" title="{{ $p->deskripsi }}">{{ $p->deskripsi }}</div>
                @endif
              </td>
              <td><span class="art-badge">{{ $p->kategori }}</span></td>
              <td class="pr-harga">{{ $p->harga_rupiah }}</td>
              <td><span class="pr-stok {{ $p->stok === 'Habis' ? 'habis' : '' }}">{{ $p->stok }}</span></td>
              <td>
                <div class="art-aksi">
                  <a href="{{ route('produk.edit', $p) }}" class="art-ikon art-edit" title="Edit" aria-label="Edit produk">
                    <svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                  </a>
                  <form action="{{ route('produk.destroy', $p) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="art-ikon art-hapus" title="Hapus" aria-label="Hapus produk">
                      <svg viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="pr-kosong">Belum ada produk.</td></tr>
          @endforelse
        </tbody>
      </table>

      <div class="pr-kaki">
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
                <a href="{{ $daftarProduk->url($h) }}" class="hal">{{ $h }}</a>
              @endif
              @php $sebelumnya = $h; @endphp
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>

</div>
@endsection