<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profil_perusahaans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perusahaan');
            $table->string('slogan')->nullable();
            $table->text('sejarah');
            $table->string('foto_sejarah')->nullable();
            $table->string('nama_direktur');
            $table->string('jabatan_direktur', 100)->default('Direktur Utama');
            $table->text('sambutan');
            $table->string('foto_direktur')->nullable();
            $table->text('visi');
            $table->text('misi');              // satu misi per baris
            $table->string('moto')->nullable();
            $table->text('struktur')->nullable(); // satu baris: Jabatan | Nama
            $table->text('alamat')->nullable();
            $table->string('email')->nullable();
            $table->string('telepon', 30)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_perusahaans');
    }
};