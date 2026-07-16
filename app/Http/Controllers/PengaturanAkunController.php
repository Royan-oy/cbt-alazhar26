<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PengaturanAkunController extends Controller
{
    /**
     * Menampilkan halaman pengaturan akun.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('pengaturan-akun.index');
    }
}
