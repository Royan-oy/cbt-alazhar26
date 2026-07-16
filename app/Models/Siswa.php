<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama',
        'nis',
        'nisn',
        'foto',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function siswaKelas()
    {
        return $this->hasMany(SiswaKelas::class);
    }

    /**
     * Penempatan kelas siswa pada tahun ajaran yang sedang aktif.
     */
    public function kelasAktif()
    {
        return $this->hasOne(SiswaKelas::class)->whereHas('tahunAjaran', function ($query) {
            $query->where('is_aktif', true);
        });
    }
}