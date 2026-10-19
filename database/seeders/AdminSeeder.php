<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Membuat akun admin untuk login ke dashboard (kalau belum ada).
 * Jalankan:  php artisan db:seed --class=AdminSeeder
 * Ganti kata sandi di bawah ini sebelum dipakai di tempat lain.
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@solusikoneksianda.co.id'],
            ['name' => 'admin', 'password' => Hash::make('admin12345')]
        );
    }
}