<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Siswa;

class PengaturanAkunController extends Controller
{
    /**
     * Menampilkan halaman pengaturan akun.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $siswa = null;
        $riwayatKelas = collect();

        if ($user->role === 'siswa') {
            $siswa = Siswa::with([
                'siswaKelas.kelas.tingkat',
                'siswaKelas.tahunAjaran',
                'kelasAktif.kelas'
            ])->where('user_id', $user->id)->first();

            if ($siswa) {
                $riwayatKelas = $siswa->siswaKelas->sortByDesc(function ($sk) {
                    return optional($sk->tahunAjaran)->id ?? $sk->id;
                })->values();
            }
        }

        return view('pengaturan-akun.index', compact('user', 'siswa', 'riwayatKelas'));
    }

    /**
     * Memperbarui password akun pengguna.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    /**
     * Memperbarui foto profil pengguna.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'foto.required' => 'File foto wajib dipilih.',
            'foto.image'    => 'File harus berupa gambar.',
            'foto.mimes'    => 'Format gambar harus jpg, jpeg, atau png.',
            'foto.max'      => 'Ukuran gambar maksimal 2MB.',
        ]);

        $user = Auth::user();

        if ($user->role === 'siswa') {
            $siswa = Siswa::where('user_id', $user->id)->firstOrFail();

            if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                Storage::disk('public')->delete($siswa->foto);
            }

            $path = $request->file('foto')->store('siswa', 'public');
            $siswa->update(['foto' => $path]);
        } else {
            if ($user->guru) {
                $guru = $user->guru;

                if ($guru->foto && Storage::disk('public')->exists($guru->foto)) {
                    Storage::disk('public')->delete($guru->foto);
                }

                $path = $request->file('foto')->store('guru', 'public');
                $guru->update(['foto' => $path]);
            }
        }

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }

    /**
     * Menghapus foto profil pengguna.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyFoto()
    {
        $user = Auth::user();

        if ($user->role === 'siswa') {
            $siswa = Siswa::where('user_id', $user->id)->first();
            if ($siswa && $siswa->foto) {
                if (Storage::disk('public')->exists($siswa->foto)) {
                    Storage::disk('public')->delete($siswa->foto);
                }
                $siswa->update(['foto' => null]);
            }
        } else {
            if ($user->guru && $user->guru->foto) {
                $guru = $user->guru;
                if (Storage::disk('public')->exists($guru->foto)) {
                    Storage::disk('public')->delete($guru->foto);
                }
                $guru->update(['foto' => null]);
            }
        }

        return back()->with('success', 'Foto profil berhasil dihapus.');
    }
}
