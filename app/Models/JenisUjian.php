<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisUjian extends Model
{
    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'aktif',
    ];
}
