<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $fillable = [
        'ujian_id',
        'siswa_id',
        'waktu_mulai_kerja',
        'waktu_kumpul',
        'nilai_pg',
        'nilai_essay',
        'nilai_akhir',
        'status',
        'current_question',
        'last_autosave',
        'violation_count'
    ];


    public function jawabanSiswas()
    {
        return $this->hasMany(JawabanSiswa::class);
    }

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
