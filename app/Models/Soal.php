<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_soal_id',
        'jenis_soal',
        'teks_soal',
        'gambar',
        'bobot',
        'urutan',
    ];

    public function bankSoal()
    {
        return $this->belongsTo(BankSoal::class);
    }

    /**
     * Label ramah-baca untuk tiap jenis soal.
     */
    public static function jenisLabel($jenis)
    {
        $labels = [
            'pilihan_ganda'          => 'Pilihan Ganda',
            // 'pilihan_ganda_kompleks' => 'Pilihan Ganda Kompleks',
            // 'benar_salah'            => 'Benar/Salah',
            'essay'                  => 'Essay',
            'isian'                  => 'Isian Singkat',
            // 'menjodohkan'            => 'Menjodohkan',
            // 'mengurutkan'            => 'Mengurutkan',
        ];

        return $labels[$jenis] ?? ucfirst(str_replace('_', ' ', $jenis));
    }
}