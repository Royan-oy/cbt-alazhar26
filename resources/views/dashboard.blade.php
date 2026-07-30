@extends('layouts.app')

@section('title', 'Dashboard Utama CBT')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')

@php
    $jamSekarang = now()->format('H');
    $sapaanWaktu = $jamSekarang < 11 ? 'Selamat Pagi' : ($jamSekarang < 15 ? 'Selamat Siang' : ($jamSekarang < 18 ? 'Selamat Sore' : 'Selamat Malam'));
    $namaUser = Auth::user()->nama ?? 'Pengguna';

    // Inisial avatar
    $kataNama = preg_split('/\s+/', trim($namaUser));
    $inisialUser = strtoupper(substr($kataNama[0] ?? 'U', 0, 1) . substr($kataNama[1] ?? '', 0, 1));

    // Progres semester (opsional)
    $persenSemester = null;
    if (isset($active_tahun_ajaran) && !empty($active_tahun_ajaran->tanggal_mulai) && !empty($active_tahun_ajaran->tanggal_selesai)) {
        try {
            $mulaiTa   = \Carbon\Carbon::parse($active_tahun_ajaran->tanggal_mulai);
            $selesaiTa = \Carbon\Carbon::parse($active_tahun_ajaran->tanggal_selesai);
            $totalHariTa = max($mulaiTa->diffInDays($selesaiTa), 1);
            $hariBerjalan = min(max($mulaiTa->diffInDays(now(), false), 0), $totalHariTa);
            $persenSemester = (int) round(($hariBerjalan / $totalHariTa) * 100);
        } catch (\Throwable $e) {
            $persenSemester = null;
        }
    }
@endphp

<div class="container-fluid px-0 py-2">

    {{-- Header Sambutan (Berlaku untuk Super Admin, Admin Jenjang, & Siswa) --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <div class="page-header-top">
                    <div>
                        <h1>{{ $sapaanWaktu }}, {{ $namaUser }} 👋</h1>

                        <p class="page-header-sub">
                            Anda login sebagai
                            <strong class="hl-cyan">
                                @switch(Auth::user()->role)
                                    @case('super_admin') Super Administrator @break
                                    @case('admin_jenjang') Administrator Jenjang @break
                                    @case('siswa') Siswa @break
                                    @default Pengguna
                                @endswitch
                            </strong>
                            di <strong class="hl-white">CBT Smart Online</strong> Sekolah Islam Al Azhar Pekalongan.
                        </p>

                        <div class="page-header-meta">
                            <i class="fa-regular fa-calendar"></i>
                            {{ now()->translatedFormat('l, d F Y') }}
                        </div>

                        @if(!is_null($persenSemester))
                            <div class="semester-progress">
                                <div class="semester-progress-label">
                                    <span>Progres Tahun Ajaran {{ $active_tahun_ajaran->nama_tahun ?? '' }}</span>
                                    <b>{{ $persenSemester }}%</b>
                                </div>
                                <div class="semester-progress-track">
                                    <div class="semester-progress-fill" style="width: {{ $persenSemester }}%;"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(Auth::user()->role == 'super_admin')
        @include('dashboard.super_admin')
    @elseif(Auth::user()->role == 'admin_jenjang')
        @include('dashboard.admin_jenjang')
    @elseif(Auth::user()->role == 'siswa')
        @include('dashboard.siswa')
    @endif

</div>

@endsection