@extends('layouts.app')

@section('title', 'Detail Guru')

@section('content')

<style>

:root {
    --primary-dark:#0f172a;
    --secondary-dark:#1e293b;
    --accent:#0ea5e9;
    --border:#e2e8f0;
}


.page-header {

    background:linear-gradient(135deg,#0f172a,#1e293b);

    border-radius:24px;

    padding:32px;

    color:white;

    box-shadow:0 15px 40px rgba(0,0,0,.08);

}


.content-card {

    background:white;

    border:1px solid var(--border);

    border-radius:24px;

    padding:24px;

    box-shadow:0 10px 30px rgba(15,23,42,.05);

}



.profile-photo {

    width:110px;

    height:110px;

    object-fit:cover;

    border-radius:50%;

    border:5px solid #f8fafc;

}



.profile-fallback {

    width:110px;

    height:110px;

    border-radius:50%;

    background:linear-gradient(135deg,#0ea5e9,#0369a1);

    display:flex;

    align-items:center;

    justify-content:center;

    color:white;

    font-size:35px;

    font-weight:700;

}



.info-item {

    padding:14px 0;

    border-bottom:1px dashed #e2e8f0;

}



.info-label {

    font-size:11px;

    text-transform:uppercase;

    color:#64748b;

    font-weight:700;

}



.info-value {

    font-weight:600;

    color:#1e293b;

}



.section-title {

    font-weight:700;

    color:#1e293b;

    margin-bottom:18px;

}



.wali-box {

    background:#f0f9ff;

    border:1px solid #bae6fd;

    border-radius:16px;

    padding:15px;

}



.mapel-card {

    border:1px solid #e2e8f0;

    border-radius:18px;

    padding:18px;

    margin-bottom:15px;

}



.badge-kelas {

    background:#e0f2fe;

    color:#0369a1;

    border-radius:10px;

    padding:6px 10px;

    font-size:12px;

    font-weight:600;

}



</style>



<div class="container-fluid py-3">



{{-- HEADER --}}

<div class="page-header mb-4">


    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">


        <div>


            <span class="badge bg-info bg-opacity-25 text-info rounded-pill px-3 py-2">

                DETAIL GURU

            </span>


            <h3 class="fw-bold mt-2 mb-1">

                {{ $guru->nama }}

            </h3>


            <p class="mb-0 text-light opacity-75">

                NIP {{ $guru->nip ?? '-' }}

                |

                {{ optional($guru->jenjang)->nama_jenjang ?? '-' }}

            </p>


        </div>



        <div>


            <a href="{{ route('guru.edit',$guru->id) }}"
               class="btn btn-info text-white rounded-3 me-2">


                <i class="fa-solid fa-pen me-2"></i>

                Edit


            </a>



            <a href="{{ route('guru.index') }}"
               class="btn btn-outline-light rounded-3">


                <i class="fa-solid fa-arrow-left me-2"></i>

                Kembali


            </a>


        </div>


    </div>


</div>






<div class="row g-4">



{{-- BIODATA --}}

<div class="col-lg-4">


<div class="content-card">



<div class="text-center mb-4">


@if($guru->foto)


<img src="{{ asset('storage/'.$guru->foto) }}"
     class="profile-photo mb-3">


@else


<div class="profile-fallback mx-auto mb-3">

{{ strtoupper(substr($guru->nama,0,1)) }}

</div>


@endif



<h5 class="fw-bold">

{{ $guru->nama }}

</h5>


<small class="text-muted">

{{ optional($guru->user)->email ?? '-' }}

</small>



</div>





<div class="info-item">

<div class="info-label">

NIP

</div>

<div class="info-value">

{{ $guru->nip ?? '-' }}

</div>

</div>




<div class="info-item">

<div class="info-label">

Nomor HP

</div>

<div class="info-value">

{{ $guru->no_hp ?? '-' }}

</div>

</div>





<div class="info-item">

<div class="info-label">

Jenjang

</div>

<div class="info-value">

{{ optional($guru->jenjang)->nama_jenjang ?? '-' }}

</div>

</div>





<div class="info-item">

<div class="info-label">

Email Akun

</div>

<div class="info-value">

{{ optional($guru->user)->email ?? '-' }}

</div>

</div>




</div>

</div>







{{-- KANAN --}}

<div class="col-lg-8">





{{-- WALI KELAS --}}

<div class="content-card mb-4">


<div class="section-title">

<i class="fa-solid fa-users text-primary me-2"></i>

Status Wali Kelas

</div>



@forelse($guru->waliKelas as $wali)


<div class="wali-box mb-3">


<i class="fa-solid fa-door-open text-primary me-2"></i>


<strong>

{{ optional($wali->kelas)->nama_kelas ?? '-' }}

</strong>


<span class="text-muted">

({{ optional(optional($wali->kelas)->tingkat)->nama_tingkat ?? '-' }})

</span>


<br>


<small class="text-muted">

Tahun Ajaran :

{{ optional($wali->tahunAjaran)->nama_tahun ?? '-' }}

</small>


</div>


@empty


<div class="text-center text-muted py-4">

<i class="fa-solid fa-users-slash fa-2x mb-2"></i>

<br>

Belum menjadi wali kelas.


</div>


@endforelse



</div>








{{-- GURU MAPEL --}}

<div class="content-card">


<div class="section-title">


<i class="fa-solid fa-book-open text-primary me-2"></i>

Mata Pelajaran yang Diampu


</div>




@forelse($guru->guruMapels as $gm)



<div class="mapel-card">



<h6 class="fw-bold mb-3">


{{ optional($gm->mataPelajaran)->nama_mapel ?? '-' }}


</h6>




<div class="mb-2">


<strong>

Kelas :

</strong>


@forelse($gm->kelas as $kelas)


<span class="badge-kelas me-1">

{{ $kelas->nama_kelas }}

</span>


@empty


<span class="text-muted">

Tidak ada kelas

</span>


@endforelse



</div>





<div class="text-muted small">


<i class="fa-solid fa-calendar me-1"></i>


Tahun Ajaran :

{{ optional($gm->tahunAjaran)->nama_tahun ?? '-' }}


</div>



</div>




@empty


<div class="text-center text-muted py-4">


<i class="fa-solid fa-book-open fa-2x mb-2"></i>


<br>


Belum ada penugasan mata pelajaran.


</div>


@endforelse





</div>




</div>



</div>


</div>


@endsection