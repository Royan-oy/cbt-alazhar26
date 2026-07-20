<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ujian;
use Illuminate\Support\Facades\Auth;

class ScanTokenController extends Controller
{
    /**
     * Tampilkan halaman form input token.
     */
    public function index()
    {
        return view('dashboard-siswa.scan-token.index');
    }

    /**
     * Proses pencarian ujian berdasarkan token yang diinput.
     * Jika ditemukan & valid, kembalikan ke view beserta data ujian
     * agar modal konfirmasi bisa ditampilkan.
     */
    public function cariUjian(Request $request)
    {
        $request->validate([
            'token' => 'required|string|max:10',
        ], [
            'token.required' => 'Token wajib diisi.',
        ]);

        $token = strtoupper(trim($request->token));

        // Cari ujian beserta relasi yang diperlukan untuk ditampilkan di modal
        $ujian = Ujian::with([
            'bankSoal.mataPelajaran',
            'bankSoal.guruMapel.guru',
            'jenisUjian',
            'kelas',
        ])->where('token', $token)->first();

        if (!$ujian) {
            return back()
                ->withInput()
                ->with('error', 'Token tidak valid. Pastikan token yang Anda masukkan sudah benar.');
        }

        // Cek apakah waktu ujian sedang aktif
        $now = now();
        if ($now->lt($ujian->waktu_mulai) || $now->gt($ujian->waktu_selesai)) {
            return back()
                ->withInput()
                ->with('error', 'Sesi ujian untuk token ini belum dimulai atau sudah berakhir.');
        }

        // Cek apakah siswa berhak mengikuti ujian ini berdasarkan kelasnya
        $siswa = Auth::user()->siswa;
        if ($siswa && $siswa->kelasAktif) {
            $kelasAktifId = $siswa->kelasAktif->kelas_id;
            $berhak = $ujian->kelas()->where('kelas.id', $kelasAktifId)->exists();

            if (!$berhak) {
                return back()
                    ->withInput()
                    ->with('error', 'Anda tidak terdaftar di kelas yang berhak mengikuti ujian ini.');
            }
        }

        // Kembalikan ke view dengan data ujian yang ditemukan,
        // frontend akan otomatis membuka modal konfirmasi
        return view('dashboard-siswa.scan-token.index', compact('ujian', 'token'));
    }

    /**
     * Konfirmasi siswa mau masuk ujian, buat session lalu redirect ke ruang ujian.
     */
    public function konfirmasi(Ujian $ujian)
    {
        // Double-check waktu masih valid
        $now = now();
        if ($now->lt($ujian->waktu_mulai) || $now->gt($ujian->waktu_selesai)) {
            return redirect()->route('dashboard-siswa.scan-token.index')
                ->with('error', 'Waktu ujian sudah habis saat konfirmasi. Silakan coba lagi.');
        }

        // Tandai sesi terverifikasi agar bisa masuk ke halaman kerja ujian
        // (skip halaman persiapan karena token sudah diverifikasi di scan token)
        session(['ujian_terverifikasi_' . $ujian->id => true]);

        return redirect()->route('dashboard-siswa.ujian.kerja', $ujian->id)
            ->with('success', 'Token berhasil diverifikasi. Selamat mengerjakan!');
    }
}
