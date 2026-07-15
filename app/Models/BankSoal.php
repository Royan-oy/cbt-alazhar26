<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankSoal extends Model
{
    public function guruMapel()
    {
        return $this->belongsTo(
            GuruMapel::class,
            'guru_mapel_id'
        );
    }

    public function jenjang()
    {
        return $this->belongsTo(
            Jenjang::class,
            'jenjang_id'
        );
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(
            MataPelajaran::class,
            'mata_pelajaran_id'
        );
    }
}
