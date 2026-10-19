<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArtikelController extends Controller
{
    public function index()
    {
        $daftarArtikel = Artikel::latest('tanggal')->latest('id')->paginate(8);

        return view('artikel.index', compact('daftarArtikel'));
    }

    public function create()
    {
        return view('artikel.form', ['artikel' => new Artikel()]);
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request, true);
        $data['foto'] = $request->file('foto')->store('artikel', 'public');

        Artikel::create($data);

        return redirect()->route('admin.artikel.index')
            ->with('sukses', 'Artikel berhasil ditambahkan.');
    }

    public function edit(Artikel $artikel)
    {
        return view('artikel.form', compact('artikel'));
    }

    public function update(Request $request, Artikel $artikel)
    {
        $data = $this->validasi($request, false);

        if ($request->hasFile('foto')) {
            Storage::disk('public')->delete($artikel->foto);
            $data['foto'] = $request->file('foto')->store('artikel', 'public');
        }

        $artikel->update($data);

        return redirect()->route('admin.artikel.index')
            ->with('sukses', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Artikel $artikel)
    {
        Storage::disk('public')->delete($artikel->foto);
        $artikel->delete();

        return redirect()->route('admin.artikel.index')
            ->with('sukses', 'Artikel berhasil dihapus.');
    }

    private function validasi(Request $request, bool $fotoWajib): array
    {
        $data = $request->validate([
            'judul'    => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:100'],
            'tanggal'  => ['required', 'date'],
            'isi'      => ['required', 'string'],
            'foto'     => [$fotoWajib ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'judul.required'    => 'Judul wajib diisi.',
            'kategori.required' => 'Kategori wajib diisi.',
            'tanggal.required'  => 'Tanggal wajib diisi.',
            'isi.required'      => 'Isi artikel wajib diisi.',
            'foto.required'     => 'Foto wajib diunggah.',
            'foto.image'        => 'File harus berupa gambar.',
            'foto.mimes'        => 'Foto harus berformat jpg, jpeg, png, atau webp.',
            'foto.max'          => 'Ukuran foto maksimal 2 MB.',
        ]);

        // Isi artikel boleh berformat (tebal, daftar, tautan, gambar), tag lain dibuang
        $data['isi'] = strip_tags($data['isi'], '<p><br><b><strong><i><em><u><ul><ol><li><a><img><div><span>');

        return $data;
    }
}