<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            $table->dropColumn('stok');                                          // layanan tidak punya stok
            $table->unsignedBigInteger('harga')->nullable()->default(null)->change(); // harga boleh kosong
            $table->string('foto')->nullable()->change();                        // foto boleh kosong (data awal)
        });
    }

    public function down(): void
    {
        DB::table('produks')->whereNull('harga')->update(['harga' => 0]);
        DB::table('produks')->whereNull('foto')->update(['foto' => '']);

        Schema::table('produks', function (Blueprint $table) {
            $table->string('stok', 20)->default('Tersedia');
            $table->unsignedBigInteger('harga')->default(0)->change();
            $table->string('foto')->nullable(false)->change();
        });
    }
};