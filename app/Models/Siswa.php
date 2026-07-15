<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    // Model Siswa
    public function siswaKelas()
    {
        return $this->hasMany(SiswaKelas::class);
    }
}
