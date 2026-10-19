<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
    public function index()
    {
        // Urutan tampil = urutan data masuk (sama seperti desain Figma).
        // Semua foto tampil dalam SATU halaman (tanpa nomor halaman).
        $daftarGaleri = Galeri::orderBy('id')->paginate(max(Galeri::count(), 1));

        return view('galeri.index', compact('daftarGaleri'));
    }

    public function create()
    {
        return view('galeri.form', ['galeri' => new Galeri()]);
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request, true);
        $data['foto'] = $request->file('foto')->store('galeri', 'public');

        Galeri::create($data);

        return redirect()->route('galeri.index')
            ->with('sukses', 'Foto galeri berhasil ditambahkan.');
    }

    public function edit(Galeri $galeri)
    {
        return view('galeri.form', compact('galeri'));
    }

    public function update(Request $request, Galeri $galeri)
    {
        $data = $this->validasi($request, false);

        if ($request->hasFile('foto')) {
            Storage::disk('public')->delete($galeri->foto);
            $data['foto'] = $request->file('foto')->store('galeri', 'public');
        }

        $galeri->update($data);

        return redirect()->route('galeri.index')
            ->with('sukses', 'Data galeri berhasil diperbarui.');
    }

    public function destroy(Galeri $galeri)
    {
        Storage::disk('public')->delete($galeri->foto);
        $galeri->delete();

        return redirect()->route('galeri.index')
            ->with('sukses', 'Foto galeri berhasil dihapus.');
    }

    private function validasi(Request $request, bool $fotoWajib): array
    {
        return $request->validate([
            'judul'    => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:100'],
            'tanggal'  => ['required', 'date'],
            'foto'     => [$fotoWajib ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'judul.required'    => 'Judul wajib diisi.',
            'kategori.required' => 'Kategori wajib diisi.',
            'tanggal.required'  => 'Tanggal wajib diisi.',
            'foto.required'     => 'Foto wajib diunggah.',
            'foto.image'        => 'File harus berupa gambar.',
            'foto.mimes'        => 'Foto harus berformat jpg, jpeg, png, atau webp.',
            'foto.max'          => 'Ukuran foto maksimal 2 MB.',
        ]);
    }
}