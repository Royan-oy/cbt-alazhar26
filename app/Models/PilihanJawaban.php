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
        'opsi', // A, B, C, D, E
        'teks_pilihan',
        'file_pendukung', // Jika opsi berupa gambar
        'is_correct' // 1 untuk jawaban benar, 0 untuk salah
    ];

    // Relasi kembali ke Soal
    public function soal()
    {
        return $this->belongsTo(Soal::class, 'soal_id');
    }
}