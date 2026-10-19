<?php

namespace App\Http\Controllers;

use App\Models\ProfilPerusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilPerusahaanController extends Controller
{
    // Hanya ada edit & update: profil bersifat statis (tidak bisa ditambah / dihapus)
    public function edit()
    {
        $profil = ProfilPerusahaan::first() ?? new ProfilPerusahaan();

        return view('profil-admin.edit', compact('profil'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'nama_perusahaan'  => ['required', 'string', 'max:255'],
            'slogan'           => ['nullable', 'string', 'max:255'],
            'sejarah'          => ['required', 'string'],
            'nama_direktur'    => ['required', 'string', 'max:255'],
            'jabatan_direktur' => ['required', 'string', 'max:100'],
            'sambutan'         => ['required', 'string'],
            'visi'             => ['required', 'string'],
            'misi'             => ['required', 'string'],
            'moto'             => ['nullable', 'string', 'max:255'],
            'struktur'         => ['nullable', 'string'],
            'alamat'           => ['nullable', 'string', 'max:500'],
            'email'            => ['nullable', 'email', 'max:255'],
            'telepon'          => ['nullable', 'string', 'max:30'],
            'foto_sejarah'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'foto_direktur'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'nama_perusahaan.required' => 'Nama perusahaan wajib diisi.',
            'sejarah.required'         => 'Sejarah wajib diisi.',
            'nama_direktur.required'   => 'Nama direktur wajib diisi.',
            'sambutan.required'        => 'Sambutan wajib diisi.',
            'visi.required'            => 'Visi wajib diisi.',
            'misi.required'            => 'Misi wajib diisi.',
            'email.email'              => 'Format email tidak valid.',
            'foto_sejarah.image'       => 'File harus berupa gambar.',
            'foto_direktur.image'      => 'File harus berupa gambar.',
            'foto_sejarah.max'         => 'Ukuran foto maksimal 2 MB.',
            'foto_direktur.max'        => 'Ukuran foto maksimal 2 MB.',
        ]);

        $profil = ProfilPerusahaan::first() ?? new ProfilPerusahaan();

        // Foto: kalau tidak diunggah ulang, foto lama dipertahankan
        foreach (['foto_sejarah', 'foto_direktur'] as $kolom) {
            unset($data[$kolom]);

            if ($request->hasFile($kolom)) {
                if ($profil->$kolom) {
                    Storage::disk('public')->delete($profil->$kolom);
                }
                $data[$kolom] = $request->file($kolom)->store('profil', 'public');
            }
        }

        $profil->fill($data)->save();

        return redirect()->route('admin.profil.edit')
            ->with('sukses', 'Profil perusahaan berhasil diperbarui.');
    }
}