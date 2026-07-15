<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jenjang_id',
        'nama',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jenjang()
    {
        return $this->belongsTo(Jenjang::class);
    }
}