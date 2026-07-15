<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jenjang extends Model
{
    protected $fillable = [
        'nama_jenjang',
        'slug'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function tingkats()
    {
        return $this->hasMany(Tingkat::class);
    }
}