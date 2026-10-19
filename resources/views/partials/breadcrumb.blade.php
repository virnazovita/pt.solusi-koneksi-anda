{{--
  Breadcrumb otomatis di bar atas halaman admin, mengikuti nama route.
  Contoh: Dashboard > Artikel > Tambah Artikel
  Untuk menambah fitur baru, cukup tambah satu baris di array $menu.
--}}
@php
    $nama = optional(request()->route())->getName() ?? '';
    $nama = \Illuminate\Support\Str::after($nama, 'admin.');          // admin.artikel.create -> artikel.create
    [$grup, $aksi] = array_pad(explode('.', $nama, 2), 2, null);

    $menu = [
        'profil'          => ['label' => 'Profil Perusahaan', 'tambah' => 'Edit Profil',        'edit' => 'Edit Profil',          'index' => 'admin.profil.edit', 'tunggal' => true],
        'artikel'         => ['label' => 'Artikel',         'tambah' => 'Tambah Artikel',         'edit' => 'Edit Artikel',         'index' => 'admin.artikel.index'],
        'galeri'          => ['label' => 'Galeri',          'tambah' => 'Tambah Foto',            'edit' => 'Edit Foto',            'index' => 'galeri.index'],
        'layanan'         => ['label' => 'Layanan',         'tambah' => 'Tambah Layanan',         'edit' => 'Edit Layanan',         'index' => 'layanan.index'],
        'kontak'          => ['label' => 'Kontak',          'tambah' => 'Detail & Balas Pesan',   'edit' => 'Detail & Balas Pesan',   'index' => 'kontak.index', 'tengah' => 'Pesan Masuk'],
    ];

    $crumb = [];
    if ($nama === 'dashboard') {
        $crumb = [['Dashboard', null]];
    } elseif (isset($menu[$grup])) {
        $m = $menu[$grup];
        $crumb[] = ['Dashboard', url('/dashboard')];

        if ($aksi === 'index' || ! empty($m['tunggal'])) {
            $crumb[] = [$m['label'], null];
        } else {
            $crumb[] = [$m['tengah'] ?? $m['label'], route($m['index'])];
            $crumb[] = [in_array($aksi, ['create', 'store']) ? $m['tambah'] : $m['edit'], null];
        }
    }

    // Form artikel sudah punya breadcrumb sendiri, jadi tidak ditampilkan dua kali
    $sudahAda = in_array($nama, ['artikel.create', 'artikel.edit', 'artikel.store', 'artikel.update']);
@endphp

@if (! empty($crumb) && ! $sudahAda)
<style>
  .bc { display: flex; align-items: center; flex-wrap: wrap; gap: 8px; font-size: 13px; color: #6b7280; }
  .bc a { color: #6b7280; text-decoration: none; }
  .bc a:hover { color: #111827; }
  .bc b { color: #111827; font-weight: 700; }
  .bc-sep { color: #6b7280; }
</style>
<nav class="bc" aria-label="Breadcrumb">
  @foreach ($crumb as [$teks, $link])
    @if (! $loop->first)<span class="bc-sep">&gt;</span>@endif
    @if ($loop->last)
      <b>{{ $teks }}</b>
    @elseif ($link)
      <a href="{{ $link }}">{{ $teks }}</a>
    @else
      <span>{{ $teks }}</span>
    @endif
  @endforeach
</nav>
@endif