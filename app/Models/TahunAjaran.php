<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    protected $fillable = [
        'nama_tahun',
        'semester',
        'is_aktif'
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
    ];

    public function getSemesterLabelAttribute()
    {
        return ucfirst($this->semester); // 'ganjil' -> 'Ganjil', 'genap' -> 'Genap'
    }
}