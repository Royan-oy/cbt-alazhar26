<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    public function guru()
    {
        return $this->hasOne(
            Guru::class,
            'user_id',
            'id'
        );
    }   

    public function siswa()
    {
        return $this->hasOne(Siswa::class);
    }

    public function getNamaAttribute()
    {
        switch ($this->role) {

            case 'super_admin':
            case 'admin_jenjang':
                return optional($this->admin)->nama;

            case 'guru':
                return optional($this->guru)->nama;

            case 'siswa':
                return optional($this->siswa)->nama;

            default:
                return '-';
        }
    }
}
