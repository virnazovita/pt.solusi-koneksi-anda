<?php

namespace App\Http\Controllers;

use App\Models\PesanKontak;
use Illuminate\Http\Request;

class KontakPublikController extends Controller
{
    // Menerima pesan dari form di halaman publik /kontak, lalu menyimpannya ke database.
    // Pesan yang masuk otomatis tampil di dashboard admin (Kelola Kontak).
    public function kirim(Request $request)
    {
        $data = $request->validate([
            'nama'   => ['required', 'string', 'max:255'],
            'email'  => ['required', 'email', 'max:255'],
            'subjek' => ['nullable', 'string', 'max:255'],
            'pesan'  => ['required', 'string', 'max:5000'],
        ], [
            'nama.required'   => 'Nama lengkap wajib diisi.',
            'email.required'  => 'Email wajib diisi.',
            'email.email'     => 'Format email belum benar.',
            'pesan.required'  => 'Pesan wajib diisi.',
            'pesan.max'       => 'Pesan terlalu panjang (maksimal 5000 karakter).',
        ]);

        // Subjek boleh dikosongkan; kalau kosong dipakai teks bawaan
        $data['subjek'] = $data['subjek'] ?? 'Pesan dari website SMKN 4 Bogor';

        PesanKontak::create($data);

        return redirect()->to(url('/kontak') . '#kirim-pesan')
            ->with('kontak_sukses', 'Terima kasih! Pesan kamu sudah terkirim dan akan segera kami balas.');
    }
}