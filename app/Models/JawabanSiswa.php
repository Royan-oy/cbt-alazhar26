<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanSiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nilai_id',
        'soal_id',
        'pilihan_jawaban_id',
        'jawaban_text',
        'jawaban_json',
        'is_benar',
        'is_ragu_ragu',
        'nilai'
    ];

    protected $casts = [
        'jawaban_json' => 'array',
        'is_benar' => 'boolean',
        'is_ragu_ragu' => 'boolean'
    ];

    public function nilai()
    {
        return $this->belongsTo(Nilai::class);
    }

    public function soal()
    {
        return $this->belongsTo(Soal::class);
    }

    public function pilihanJawaban()
    {
        return $this->belongsTo(PilihanJawaban::class);
    }
}