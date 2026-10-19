<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('ekstrakulikulers');
    }

    public function down(): void
    {
        // Fitur ekstrakulikuler sudah dihapus permanen, jadi tidak ada yang dikembalikan.
    }
};