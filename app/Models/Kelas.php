<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $fillable = [
        'tingkat_id',
        'nama_kelas'
    ];

    public function tingkat()
    {
        return $this->belongsTo(Tingkat::class);
    }

    // Model Kelas
    public function siswaKelas()
    {
        return $this->hasMany(SiswaKelas::class);
    }

    public function guruMapels()
    {
        return $this->belongsToMany(
            GuruMapel::class,
            'guru_mapel_kelas'
        );
    }
}
