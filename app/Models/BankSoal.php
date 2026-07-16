<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankSoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'guru_mapel_id',
        'mata_pelajaran_id',
        'jenjang_id',
        'nama_bank_soal',
        'deskripsi',
        'is_publish',
    ];

    protected $casts = [
        'is_publish' => 'boolean',
    ];

    public function guruMapel()
    {
        return $this->belongsTo(GuruMapel::class);
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function jenjang()
    {
        return $this->belongsTo(Jenjang::class);
    }

    public function soals()
    {
        return $this->hasMany(Soal::class);
    }

    public function ujians()
    {
        return $this->hasMany(Ujian::class);
    }
}