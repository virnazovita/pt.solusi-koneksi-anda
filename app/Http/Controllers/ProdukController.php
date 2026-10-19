<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

// Mengelola data "Layanan" perusahaan (memakai model Produk / tabel produks).
class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $kategoriList = Produk::query()->select('kategori')->distinct()->orderBy('kategori')->pluck('kategori');

        $daftarLayanan = Produk::query()
            ->when($request->filled('kategori'), fn ($q) => $q->where('kategori', $request->kategori))
            ->latest('id')
            ->paginate(8)
            ->withQueryString();

        return view('layanan.index', compact('daftarLayanan', 'kategoriList'));
    }

    public function create()
    {
        return view('layanan.form', [
            'layanan'      => new Produk(),
            'kategoriList' => $this->kategoriSaran(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request, true);
        $data['foto'] = $request->file('foto')->store('layanan', 'public');

        Produk::create($data);

        return redirect()->route('layanan.index')->with('sukses', 'Layanan berhasil ditambahkan.');
    }

    public function edit(Produk $produk)
    {
        return view('layanan.form', [
            'layanan'      => $produk,
            'kategoriList' => $this->kategoriSaran(),
        ]);
    }

    public function update(Request $request, Produk $produk)
    {
        $data = $this->validasi($request, false);

        if ($request->hasFile('foto')) {
            if ($produk->foto) {
                Storage::disk('public')->delete($produk->foto);
            }
            $data['foto'] = $request->file('foto')->store('layanan', 'public');
        } else {
            unset($data['foto']);
        }

        $produk->update($data);

        return redirect()->route('layanan.index')->with('sukses', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        if ($produk->foto) {
            Storage::disk('public')->delete($produk->foto);
        }
        $produk->delete();

        return redirect()->route('layanan.index')->with('sukses', 'Layanan berhasil dihapus.');
    }

    // Saran kategori di form: bawaan + yang sudah pernah dipakai
    private function kategoriSaran()
    {
        return collect(['Jaringan', 'Website', 'Perangkat Keras', 'Pemeliharaan', 'Konsultasi'])
            ->merge(Produk::query()->distinct()->pluck('kategori'))
            ->unique()
            ->values();
    }

    private function validasi(Request $request, bool $fotoWajib): array
    {
        return $request->validate([
            'nama'      => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:255'],
            'kategori'  => ['required', 'string', 'max:100'],
            'harga'     => ['nullable', 'integer', 'min:0'],
            'foto'      => [$fotoWajib ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'nama.required'     => 'Nama layanan wajib diisi.',
            'kategori.required' => 'Kategori wajib diisi.',
            'harga.integer'     => 'Harga harus berupa angka tanpa titik atau koma.',
            'harga.min'         => 'Harga tidak boleh negatif.',
            'foto.required'     => 'Foto layanan wajib diunggah.',
            'foto.image'        => 'File harus berupa gambar.',
            'foto.mimes'        => 'Foto harus berformat jpg, jpeg, png, atau webp.',
            'foto.max'          => 'Ukuran foto maksimal 2 MB.',
        ]);
    }
}