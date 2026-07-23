@extends('layouts.app')
@section('title', 'Detail Bank Soal')

@section('content')

<style>
    :root {
        --primary-dark: #0f172a;
        --secondary-dark: #1e293b;
        --accent-blue: #0ea5e9;
        --surface-white: #ffffff;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
    }

    .page-header-create {
        background: linear-gradient(135deg, var(--primary-dark), var(--secondary-dark));
        border-radius: 24px;
        padding: 32px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .page-header-create::after {
        content: '';
        position: absolute;
        width: 320px;
        height: 320px;
        border-radius: 50%;
        right: -60px;
        top: -90px;
        background: radial-gradient(circle, rgba(14, 165, 233, 0.18) 0%, rgba(14, 165, 233, 0) 70%);
        pointer-events: none;
    }

    .breadcrumb-nav a { color: #94a3b8; text-decoration: none; font-size: 13px; transition: color 0.2s; }
    .breadcrumb-nav a:hover { color: #e2e8f0; }
    .breadcrumb-nav span { color: #64748b; font-size: 13px; }

    .detail-card {
        background: var(--surface-white);
        border-radius: 24px;
        border: 1px solid var(--border-color);
        box-shadow: 0 12px 34px rgba(15, 23, 42, 0.03);
        overflow: hidden;
    }

    .detail-card-header {
        padding: 24px 28px;
        border-bottom: 1px solid var(--border-color);
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }

    .detail-card-header h5 {
        font-weight: 700;
        color: var(--primary-dark);
        margin-bottom: 4px;
        font-size: 1.05rem;
    }

    .detail-card-body { padding: 28px; }

    .info-row {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        padding: 14px 0;
        border-bottom: 1px dashed var(--border-color);
    }
    .info-row:last-child { border-bottom: none; }

    .info-label {
        font-weight: 600;
        color: var(--text-muted);
        font-size: 13px;
        flex-shrink: 0;
        width: 160px;
    }

    .info-value {
        color: var(--primary-dark);
        font-size: 14px;
        font-weight: 500;
        text-align: right;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    .status-badge.published { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
    .status-badge.draft { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
    .status-badge .dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
    .status-badge.published .dot { background: #16a34a; }
    .status-badge.draft .dot { background: #d97706; }

    .btn-back, .btn-edit, .btn-manage {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border-radius: 14px;
        padding: 11px 22px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        transition: 0.2s ease;
        border: none;
        cursor: pointer;
    }

    .btn-back { background: #fff; color: var(--text-muted); border: 1.5px solid var(--border-color); }
    .btn-back:hover { color: var(--primary-dark); border-color: #cbd5e1; background: #f1f5f9; text-decoration: none; }

    .btn-edit { background: #fefce8; color: #a16207; }
    .btn-edit:hover { background: #a16207; color: #fff; text-decoration: none; }

    .btn-manage {
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        color: #fff;
        box-shadow: 0 8px 20px rgba(14, 165, 233, 0.25);
    }
    .btn-manage:hover { background: linear-gradient(135deg, #0284c7, #0369a1); color: #fff; text-decoration: none; }

    .soal-count-box {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 14px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 24px;
        gap: 12px;
        flex-wrap: wrap;
    }

    .soal-count-box .count-text strong { font-size: 20px; color: var(--primary-dark); }
    .soal-count-box .count-text span { display: block; font-size: 13px; color: var(--text-muted); }

    @media (max-width: 767.98px) {
        .page-header-create { padding: 24px 20px; border-radius: 18px; }
        .detail-card { border-radius: 18px; }
        .detail-card-header, .detail-card-body { padding: 20px; }
        .info-row { flex-direction: column; gap: 4px; }
        .info-value { text-align: left; }
        .info-label { width: auto; }
        .soal-count-box { flex-direction: column; align-items: flex-start; }
        .btn-manage { width: 100%; justify-content: center; }
    }
</style>

<div class="container-fluid py-2">

    {{-- Page Header --}}
    <div class="page-header-create mb-4">
        <div class="breadcrumb-nav mb-3">
            <a href="{{ route('dashboard-guru.bank-soal.index') }}">
                <i class="fa-solid fa-folder-open me-1"></i> Bank Soal
            </a>
            <span class="mx-2">/</span>
            <span class="text-white">Detail</span>
        </div>
        <h3 class="fw-bold mb-1" style="letter-spacing: -0.5px;">
            {{ $bank_soal->nama_bank_soal }}
        </h3>
        <p class="text-light opacity-75 mb-0 small">
            Detail informasi bank soal.
        </p>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="alert alert-success rounded-4 border-0 shadow-sm d-flex align-items-center p-3 mb-4">
        <i class="fa-solid fa-circle-check fs-5 me-2"></i>
        <div>{{ session('success') }}</div>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger rounded-4 border-0 shadow-sm d-flex align-items-center p-3 mb-4">
        <i class="fa-solid fa-circle-exclamation fs-5 me-2"></i>
        <div>{{ session('error') }}</div>
    </div>
    @endif

    {{-- Detail Card --}}
    <div class="detail-card">
        <div class="detail-card-header">
            <div>
                <h5><i class="fa-solid fa-file-lines me-2 text-primary"></i>Informasi Bank Soal</h5>
            </div>
            <a href="{{ route('dashboard-guru.bank-soal.edit', $bank_soal->id) }}" class="btn-edit">
                <i class="fa-solid fa-pen"></i>
                Edit
            </a>
        </div>

        <div class="detail-card-body">
            <div class="info-row">
                <span class="info-label">Nama Bank Soal</span>
                <span class="info-value">{{ $bank_soal->nama_bank_soal }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Mata Pelajaran</span>
                <span class="info-value">{{ $bank_soal->mataPelajaran->nama_mapel ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Jenjang</span>
                <span class="info-value">{{ $bank_soal->jenjang->nama_jenjang ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">KKM (Ketuntasan)</span>
                <span class="info-value">
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 13px;">
                        {{ $bank_soal->kkm ?? 75 }}
                    </span>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Deskripsi</span>
                <span class="info-value">{{ $bank_soal->deskripsi ?: '—' }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="info-value">
                    @if($bank_soal->is_publish)
                        <span class="status-badge published"><span class="dot"></span> Publik</span>
                    @else
                        <span class="status-badge draft"><span class="dot"></span> Draft</span>
                    @endif
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Dibuat</span>
                <span class="info-value">{{ $bank_soal->created_at->format('d M Y, H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Diperbarui</span>
                <span class="info-value">{{ $bank_soal->updated_at->format('d M Y, H:i') }}</span>
            </div>

            {{-- Ringkasan jumlah soal + tautan ke pengelolaan soal --}}
            @php $totalBobot = $bank_soal->soals->sum('bobot'); @endphp
            <div class="soal-count-box">
                <div class="d-flex align-items-center gap-4 flex-wrap">
                    <div class="count-text">
                        <strong>{{ $bank_soal->soals->count() }}</strong>
                        <span>soal di dalam bank soal ini</span>
                    </div>
                    <div class="count-text" style="border-left: 2px solid #bfdbfe; padding-left: 16px;">
                        <strong style="color: {{ $totalBobot == 100 ? '#16a34a' : '#dc2626' }}">{{ $totalBobot }}</strong>
                        <span>
                            total bobot
                            @if($totalBobot == 100)
                                <i class="fa-solid fa-circle-check text-success ms-1" style="font-size: 11px;"></i>
                            @else
                                <span class="ms-1" style="color: #dc2626; font-weight: 600; font-size: 11px;">
                                    (harus 100)
                                </span>
                            @endif
                        </span>
                    </div>
                </div>
                <a href="{{ route('dashboard-guru.bank-soal.soal.index', $bank_soal->id) }}" class="btn-manage">
                    <i class="fa-solid fa-list-check"></i>
                    Kelola Soal
                </a>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('dashboard-guru.bank-soal.index') }}" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i>
            Kembali ke Daftar
        </a>
    </div>

</div>

@endsection