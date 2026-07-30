@extends('layouts.app')

@section('title', 'Dashboard Utama CBT')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')

<div class="container-fluid px-0">

    {{-- Header Sambutan (Berlaku untuk semua role) --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <span class="page-header-badge">
                    <i class="fa-solid fa-circle-check"></i>
                    Portal Akademik Aktif
                </span>

                <h1 class="fw-bold text-white mb-2" style="font-size: 26px;">
                    {{ $sapaanWaktu = now()->format('H') < 11 ? 'Selamat Pagi' : (now()->format('H') < 15 ? 'Selamat Siang' : (now()->format('H') < 18 ? 'Selamat Sore' : 'Selamat Malam')) }},
                    {{ Auth::user()->nama }} 👋
                </h1>

                <p class="mb-2" style="font-size:14px;color:#cbd5e1;line-height:1.8;">
                    Anda login sebagai
                    <strong class="text-info">
                        @switch(Auth::user()->role)
                            @case('super_admin') Super Administrator @break
                            @case('admin_jenjang') Administrator Jenjang @break
                            @case('siswa') Siswa @break
                            @default Pengguna
                        @endswitch
                    </strong>
                    di <strong class="text-white">CBT Smart Online</strong> Sekolah Islam Al Azhar Pekalongan.
                </p>

                <div class="page-header-meta">
                    <i class="fa-regular fa-calendar"></i>
                    {{ now()->translatedFormat('l, d F Y') }}
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