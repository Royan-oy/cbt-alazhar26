<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ujian extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_soal_id',
        'tahun_ajaran_id',
        'jenis_ujian_id',
        'nama_ujian',
        'waktu_mulai',
        'waktu_selesai',
        'durasi_minimal',
        'token',
        'token_aktif',
        'acak_soal',
        'acak_jawaban',
        'tampilkan_nilai',
        'tampilkan_pembahasan',
    ];

    protected $casts = [
        'waktu_mulai'           => 'datetime',
        'waktu_selesai'         => 'datetime',
        'token_aktif'           => 'boolean',
        'acak_soal'             => 'boolean',
        'acak_jawaban'          => 'boolean',
        'tampilkan_nilai'       => 'boolean',
        'tampilkan_pembahasan'  => 'boolean',
    ];

    public function bankSoal()
    {
        return $this->belongsTo(BankSoal::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function jenisUjian()
    {
        return $this->belongsTo(JenisUjian::class);
    }

    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'ujian_kelas')->withTimestamps();
    }

    public static function generateToken()
    {
        do {
            $token = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::where('token', $token)->exists());

        return $token;
    }

    /**
     * Status waktu pelaksanaan ujian saat ini (untuk badge di listing).
     */
    public function getStatusWaktuAttribute(): string
    {
        $now = now();

        if ($now->lt($this->waktu_mulai)) {
            return 'akan_datang';
        }

        if ($now->gt($this->waktu_selesai)) {
            return 'selesai';
        }

        return 'berlangsung';
    }

    // TAMBAHKAN FUNGSI INI
    public function mataPelajaran()
    {
        // Sesuaikan 'mata_pelajaran_id' dengan nama foreign key di tabel ujians Anda
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }

    
    
    public function autoUpdateTokenStatus()
    {
        $now = now();

        // Belum aktif tapi waktu sudah masuk
        if (!$this->token_aktif &&
            $now->greaterThanOrEqualTo($this->waktu_mulai) &&
            $now->lessThanOrEqualTo($this->waktu_selesai)) {

            $this->update([
                'token_aktif' => true
            ]);
        }

        // Masih aktif tapi waktu sudah habis
        if ($this->token_aktif &&
            $now->greaterThan($this->waktu_selesai)) {

            $this->update([
                'token_aktif' => false
            ]);
        }
        
    }
}