<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel artikels lama (kolomnya tidak cocok) dibuat ulang dengan kolom yang benar.
        // Aman dijalankan karena isi tabel lama masih kosong (0 data).
        Schema::dropIfExists('artikels');

        Schema::create('artikels', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kategori', 100);
            $table->date('tanggal');
            $table->string('foto');
            $table->longText('isi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artikels');
    }
};