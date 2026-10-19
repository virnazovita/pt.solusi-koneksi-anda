<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesan_kontaks', function (Blueprint $table) {
            $table->timestamp('dibaca_at')->nullable()->after('pesan');
            $table->timestamp('dibalas_at')->nullable()->after('dibaca_at');
            $table->string('balasan_subjek')->nullable()->after('dibalas_at');
            $table->longText('balasan_isi')->nullable()->after('balasan_subjek');
        });
    }

    public function down(): void
    {
        Schema::table('pesan_kontaks', function (Blueprint $table) {
            $table->dropColumn(['dibaca_at', 'dibalas_at', 'balasan_subjek', 'balasan_isi']);
        });
    }
};