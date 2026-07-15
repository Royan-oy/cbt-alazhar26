<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaliKelas extends Model
{
    use HasFactory;


    protected $table = 'wali_kelas';


    protected $fillable = [

        'guru_id',
        'kelas_id',
        'tahun_ajaran_id',

    ];



    /**
     * Guru wali kelas
     */
    public function guru()
    {
        return $this->belongsTo(
            Guru::class,
            'guru_id'
        );
    }



    /**
     * Kelas yang diwalikan
     */
    public function kelas()
    {
        return $this->belongsTo(
            Kelas::class,
            'kelas_id'
        );
    }



    /**
     * Tahun ajaran wali kelas
     */
    public function tahunAjaran()
    {
        return $this->belongsTo(
            TahunAjaran::class,
            'tahun_ajaran_id'
        );
    }
}