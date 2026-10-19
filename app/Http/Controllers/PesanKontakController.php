<?php

namespace App\Http\Controllers;

use App\Models\PesanKontak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PesanKontakController extends Controller
{
    // Tag yang boleh ada di isi balasan (selain itu dibuang demi keamanan)
    private const TAG_AMAN = '<p><br><b><strong><i><em><u><ul><ol><li><a><div><span>';

    public function index()
    {
        // Urutan sama seperti desain (tanggal terlama di atas).
        // Mau pesan terbaru di atas? Ganti menjadi: PesanKontak::latest()->paginate(8)
        $daftarPesan = PesanKontak::orderBy('created_at')->orderBy('id')->paginate(8);

        return view('pesan.index', compact('daftarPesan'));
    }

    // Halaman "Detail & Balas Pesan"
    public function show(PesanKontak $pesanKontak)
    {
        // Membuka detail = menandai pesan sudah dibaca
        if (! $pesanKontak->dibaca_at) {
            $pesanKontak->dibaca_at = now();
            $pesanKontak->save();
        }

        return view('pesan.show', [
            'pesan'    => $pesanKontak,
            'pengirim' => $this->pengirim(),
        ]);
    }

    // Kirim balasan lewat email ke pengunjung
    public function balas(Request $request, PesanKontak $pesanKontak)
    {
        $data = $request->validate([
            'balasan_subjek' => ['required', 'string', 'max:255'],
            'balasan_isi'    => ['required', 'string'],
        ], [
            'balasan_subjek.required' => 'Subjek email balasan wajib diisi.',
            'balasan_isi.required'    => 'Isi pesan balasan wajib diisi.',
        ]);

        $isi = strip_tags($data['balasan_isi'], self::TAG_AMAN);

        if (trim(strip_tags($isi)) === '') {
            return back()
                ->withErrors(['balasan_isi' => 'Isi pesan balasan wajib diisi.'])
                ->withInput();
        }

        $pengirim = $this->pengirim();
        $badanEmail = $isi . '<p>--<br>' . e($pengirim['nama']) . '</p>';

        try {
            Mail::html($badanEmail, function ($m) use ($pesanKontak, $data, $pengirim) {
                $m->to($pesanKontak->email, $pesanKontak->nama)
                  ->from($pengirim['email'], $pengirim['nama'])
                  ->subject($data['balasan_subjek']);
            });
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withErrors(['kirim' => 'Email gagal dikirim. Periksa pengaturan MAIL_ di file .env. (' . $e->getMessage() . ')'])
                ->withInput();
        }

        $pesanKontak->balasan_subjek = $data['balasan_subjek'];
        $pesanKontak->balasan_isi    = $isi;
        $pesanKontak->dibalas_at     = now();
        $pesanKontak->save();

        return redirect()->route('kontak.show', $pesanKontak)
            ->with('sukses', 'Balasan berhasil dikirim ke ' . $pesanKontak->email . '.');
    }

    public function destroy(PesanKontak $pesanKontak)
    {
        $pesanKontak->delete();

        return redirect()->route('kontak.index')
            ->with('sukses', 'Pesan berhasil dihapus.');
    }

    // Nama & alamat email pengirim (diatur lewat MAIL_FROM_NAME dan MAIL_FROM_ADDRESS di .env)
    private function pengirim(): array
    {
        return [
            'nama'  => config('mail.from.name'),
            'email' => config('mail.from.address'),
        ];
    }
}