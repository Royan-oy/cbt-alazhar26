<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PilihanJawaban extends Model
{
    use HasFactory;

    protected $table = 'pilihan_jawabans';

    protected $fillable = [
        'soal_id',
        'kode',         // A, B, C, ... — digenerate otomatis dari urutan opsi
        'teks_pilihan',
        'pasangan',     // dipakai kalau nanti ada jenis soal menjodohkan
        'is_benar',     // true untuk jawaban benar, false untuk salah
        'urutan',
    ];

    protected $casts = [
        'is_benar' => 'boolean',
    ];

    // Relasi kembali ke Soal
    public function soal()
    {
        return $this->belongsTo(Soal::class, 'soal_id');
    }
}