<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use App\Models\Jenjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminJenjangController extends Controller
{
    public function __construct()
    {
        // Hanya super_admin yang boleh mengakses menu ini
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role != 'super_admin') {
                abort(403, 'Anda tidak memiliki akses ke halaman ini.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $admins = Admin::with(['user', 'jenjang'])
            ->whereHas('user', function ($query) {
                $query->where('role', 'admin_jenjang');
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('nama', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('jenjang'), function ($query) use ($request) {
                $query->where('jenjang_id', $request->jenjang);
            })
            ->orderBy('nama', 'asc')
            ->paginate(10)
            ->withQueryString();

        $totalAdmin = Admin::whereHas('user', function ($query) {
                $query->where('role', 'admin_jenjang');
            })->count();

        $jenjangs = Jenjang::orderBy('nama_jenjang', 'asc')->get();

        return view('admin-jenjang.index', compact('admins', 'totalAdmin', 'jenjangs'));
    }

    public function create()
    {
        $jenjangs = Jenjang::orderBy('nama_jenjang', 'asc')->get();

        return view('admin-jenjang.create', compact('jenjangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'       => 'required|string|max:150',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:6|confirmed',
            'jenjang_id' => 'required|exists:jenjangs,id',
        ], [
            'nama.required'       => 'Nama wajib diisi.',
            'email.required'      => 'Email wajib diisi.',
            'email.email'         => 'Format email tidak valid.',
            'email.unique'        => 'Email ini sudah terdaftar.',
            'password.required'   => 'Password wajib diisi.',
            'password.min'        => 'Password minimal 6 karakter.',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
            'jenjang_id.required' => 'Jenjang wajib dipilih.',
        ]);

        DB::transaction(function () use ($request) {
            $user = new User();
            $user->email    = $request->email;
            $user->password = Hash::make($request->password);
            $user->role     = 'admin_jenjang';
            $user->save();

            Admin::create([
                'user_id'    => $user->id,
                'jenjang_id' => $request->jenjang_id,
                'nama'       => $request->nama,
            ]);
        });

        return redirect()->route('admin-jenjang.index')
            ->with('success', 'Admin jenjang berhasil ditambahkan.');
    }

    public function edit(Admin $admin)
    {
        $jenjangs = Jenjang::orderBy('nama_jenjang', 'asc')->get();

        return view('admin-jenjang.edit', compact('admin', 'jenjangs'));
    }

    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'nama'       => 'required|string|max:150',
            'email'      => 'required|email|unique:users,email,' . $admin->user_id,
            'password'   => 'nullable|string|min:6|confirmed',
            'jenjang_id' => 'required|exists:jenjangs,id',
        ], [
            'nama.required'       => 'Nama wajib diisi.',
            'email.required'      => 'Email wajib diisi.',
            'email.email'         => 'Format email tidak valid.',
            'email.unique'        => 'Email ini sudah terdaftar.',
            'password.min'        => 'Password minimal 6 karakter.',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
            'jenjang_id.required' => 'Jenjang wajib dipilih.',
        ]);

        DB::transaction(function () use ($request, $admin) {
            $userData = [
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $admin->user()->update($userData);

            $admin->update([
                'nama'       => $request->nama,
                'jenjang_id' => $request->jenjang_id,
            ]);
        });

        return redirect()->route('admin-jenjang.index')
            ->with('success', 'Admin jenjang berhasil diperbarui.');
    }

    public function destroy(Admin $admin)
    {
        if ($admin->user_id == Auth::id()) {
            return redirect()->route('admin-jenjang.index')
                ->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        // Menghapus user akan otomatis menghapus admin (cascadeOnDelete)
        $admin->user()->delete();

        return redirect()->route('admin-jenjang.index')
            ->with('success', 'Admin jenjang berhasil dihapus.');
    }
}