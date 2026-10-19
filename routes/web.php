<?php

use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\KontakPublikController;
use App\Http\Controllers\PesanKontakController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfilPerusahaanController;
use App\Models\Artikel;
use App\Models\Produk;
use App\Models\ProfilPerusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ===== Halaman publik =====

// Home: profil, 4 layanan terbaru, dan 4 artikel terbaru diambil dari database
Route::get('/', function () {
    $profil   = ProfilPerusahaan::first() ?? new ProfilPerusahaan();
    $layanans = Produk::latest('id')->take(4)->get();
    $artikels = Artikel::latest('tanggal')->latest('id')->take(4)->get();

    return view('beranda', compact('profil', 'layanans', 'artikels'));
})->name('home');

// Profil perusahaan: isinya diambil dari database (diubah lewat Dashboard > Profil Perusahaan)
Route::get('/profil', function () {
    $profil = ProfilPerusahaan::first() ?? new ProfilPerusahaan();

    return view('profil', compact('profil'));
})->name('profil');

// Layanan: diambil dari database (dikelola lewat Dashboard > Layanan). Yang terbaru tampil di depan.
Route::get('/layanan', function () {
    $layanans = Produk::latest('id')->get();

    return view('layanan', compact('layanans'));
})->name('layanan');

// Tautan lama /produk dialihkan ke /layanan
Route::redirect('/produk', '/layanan');

// Alamat alternatif untuk masuk admin: /admin dan /admin/login diarahkan ke halaman login
Route::redirect('/admin', '/login');
Route::redirect('/admin/login', '/login');

Route::get('/artikel', function () {
    $artikels = Artikel::latest('tanggal')->latest('id')->get();

    return view('artikel', compact('artikels'));
})->name('artikel');

// Galeri: data foto diambil langsung di dalam view galeri.blade.php
Route::view('/galeri', 'galeri')->name('galeri');

// Kontak: alamat, telepon, dan email diambil dari Profil Perusahaan
Route::get('/kontak', function () {
    $profil = ProfilPerusahaan::first() ?? new ProfilPerusahaan();

    return view('kontak', compact('profil'));
})->name('kontak');
Route::post('/kontak', [KontakPublikController::class, 'kirim'])->middleware('throttle:5,1')->name('kontak.kirim');

// ===== Tamu (belum login) =====
Route::middleware('guest')->group(function () {

    Route::get('/login', fn () => view('auth.login'))->name('login');

    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'email.required'    => 'Nama pengguna atau email wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        // Boleh login pakai email ATAU nama pengguna (kolom `name` di tabel users)
        $login = $credentials['email'];
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        if (Auth::attempt([$field => $login, 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        return back()
            ->withErrors(['email' => 'Email atau kata sandi salah.'])
            ->onlyInput('email');
    })->name('login.process');
});

// ===== Sudah login (admin) =====
Route::middleware('auth')->group(function () {

    // Ganti 'dashboard' dengan nama view dashboard-mu kalau berbeda
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    })->name('logout');

    // Profil Perusahaan (statis) -> /dashboard/profil, hanya edit & update
    Route::prefix('dashboard')->name('admin.')->group(function () {
        Route::get('profil', [ProfilPerusahaanController::class, 'edit'])->name('profil.edit');
        Route::put('profil', [ProfilPerusahaanController::class, 'update'])->name('profil.update');
    });

    // Artikel -> /dashboard/artikel (nama route: admin.artikel.*)
    Route::prefix('dashboard')->name('admin.')->group(function () {
        Route::resource('artikel', ArtikelController::class)->except('show');
    });

    // Galeri -> /dashboard/galeri (nama route: galeri.*)
    Route::prefix('dashboard')->group(function () {
        Route::resource('galeri', GaleriController::class)->except('show');
    });

    // Layanan -> /dashboard/layanan (nama route: layanan.*), memakai ProdukController & model Produk
    Route::prefix('dashboard')->group(function () {
        Route::resource('layanan', ProdukController::class)
            ->parameters(['layanan' => 'produk'])
            ->except('show');
    });

    // Kontak (pesan masuk) -> /dashboard/kontak (nama route: kontak.index, kontak.destroy)
    Route::prefix('dashboard')->group(function () {
        Route::resource('kontak', PesanKontakController::class)
            ->only(['index', 'destroy'])
            ->parameters(['kontak' => 'pesanKontak']);
    });

    // Kontak: detail & balas pesan -> /dashboard/kontak/{id}
    Route::prefix('dashboard')->group(function () {
        Route::get('kontak/{pesanKontak}', [PesanKontakController::class, 'show'])->name('kontak.show');
        Route::post('kontak/{pesanKontak}/balas', [PesanKontakController::class, 'balas'])->name('kontak.balas');
    });
});