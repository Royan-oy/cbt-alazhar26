<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jenjang_id',
        'nama',
        'nip',
        'no_hp',
        'foto',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jenjang()
    {
        return $this->belongsTo(Jenjang::class);
    }

    public function guruMapels()
    {
        return $this->hasMany(GuruMapel::class);
    }

    public function waliKelas()
    {
        return $this->hasMany(WaliKelas::class);
    }
}