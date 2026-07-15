<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuruMapel extends Model
{
    protected $fillable = [
        'guru_id',
        'mata_pelajaran_id',
        'kelas_id',
        'tahun_ajaran_id',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function kelas()
    {
        return $this->belongsToMany(
            Kelas::class,
            'guru_mapel_kelas',
            'guru_mapel_id',
            'kelas_id'
        );
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}