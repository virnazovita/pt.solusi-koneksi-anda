<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class PesanKontak extends Model
{
    protected $table = 'pesan_kontaks';

    protected $fillable = ['nama', 'email', 'subjek', 'pesan'];

    protected function casts(): array
    {
        return [
            'dibaca_at'  => 'datetime',
            'dibalas_at' => 'datetime',
        ];
    }

    // Status pesan: Baru -> Dibaca -> Dibalas
    protected function statusLabel(): Attribute
    {
        return Attribute::get(function () {
            if ($this->dibalas_at) {
                return 'Dibalas';
            }

            return $this->dibaca_at ? 'Dibaca' : 'Baru';
        });
    }
}