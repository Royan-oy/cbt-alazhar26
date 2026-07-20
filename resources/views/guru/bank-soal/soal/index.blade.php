@extends('layouts.app')
@section('title', 'Daftar Soal')

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

    .page-header-soal {
        background: linear-gradient(135deg, var(--primary-dark), var(--secondary-dark));
        border-radius: 24px;
        padding: 32px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .page-header-soal::after {
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

    .header-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        border-radius: 14px;
        padding: 11px 22px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: 0.25s ease;
        cursor: pointer;
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        color: #fff;
        box-shadow: 0 8px 20px rgba(14, 165, 233, 0.3);
    }

    .header-btn:hover {
        color: #fff;
        background: linear-gradient(135deg, #0284c7, #0369a1);
        transform: translateY(-3px);
        box-shadow: 0 14px 28px rgba(14, 165, 233, 0.4);
        text-decoration: none;
    }

    /* ===== FILTER BAR ===== */
    .filter-bar {
        background: var(--surface-white);
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 14px 16px;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.02);
    }

    .filter-search-wrap {
        position: relative;
        flex: 1;
        min-width: 220px;
    }

    .filter-search-wrap i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 13px;
        pointer-events: none;
    }

    .filter-search-input {
        width: 100%;
        border: 1.5px solid var(--border-color);
        border-radius: 12px;
        padding: 10px 14px 10px 38px;
        font-size: 13.5px;
        background: #f8fafc;
        color: var(--primary-dark);
        transition: all 0.2s ease;
    }

    .filter-search-input:focus {
        outline: none;
        background: #fff;
        border-color: var(--accent-blue);
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1);
    }

    .filter-select {
        border: 1.5px solid var(--border-color);
        border-radius: 12px;
        padding: 10px 34px 10px 14px;
        font-size: 13.5px;
        background-color: #f8fafc;
        color: var(--primary-dark);
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M2.22 4.47a.75.75 0 011.06 0L6 7.19l2.72-2.72a.75.75 0 011.06 1.06l-3.25 3.25a.75.75 0 01-1.06 0L2.22 5.53a.75.75 0 010-1.06z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 11px;
        cursor: pointer;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    .filter-select:focus {
        outline: none;
        background-color: #fff;
        border-color: var(--accent-blue);
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1);
    }

    .filter-count {
        font-size: 12px;
        color: var(--text-muted);
        white-space: nowrap;
        flex-shrink: 0;
    }

    .filter-count strong { color: var(--primary-dark); }

    .filter-reset {
        border: none;
        background: #f1f5f9;
        color: #475569;
        border-radius: 10px;
        width: 36px;
        height: 36px;
        display: none;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        flex-shrink: 0;
        transition: 0.2s;
    }
    .filter-reset:hover { background: #e2e8f0; }
    .filter-reset.show { display: inline-flex; }

    .no-match-state {
        display: none;
        padding: 50px 24px;
        text-align: center;
        background: var(--surface-white);
        border-radius: 20px;
        border: 1px solid var(--border-color);
    }

    /* ===== IMPORT BUTTON (outline, secondary) ===== */
    .header-btn-outline {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 1.5px solid rgba(255, 255, 255, 0.25);
        border-radius: 14px;
        padding: 10.5px 20px;
        font-weight: 600;
        font-size: 0.9rem;
        color: #fff;
        background: rgba(255, 255, 255, 0.08);
        cursor: pointer;
        transition: 0.2s ease;
        text-decoration: none;
    }
    .header-btn-outline:hover { background: rgba(255, 255, 255, 0.18); color: #fff; }

    /* ===== IMPORT MODAL ===== */
    .field-error { color: #ef4444; font-size: 12px; margin-top: 6px; display: flex; align-items: center; gap: 4px; }

    .import-step { display: flex; gap: 14px; align-items: flex-start; }
    .import-step-num {
        width: 26px; height: 26px; border-radius: 50%;
        background: #eff6ff; color: #2563eb;
        font-weight: 700; font-size: 12px;
        display: inline-flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .import-step-title { font-weight: 600; font-size: 14px; color: var(--primary-dark); margin-bottom: 10px; }

    .btn-template-download {
        display: inline-flex; align-items: center; gap: 8px;
        background: #f0fdf4; color: #16a34a;
        border: 1.5px solid #bbf7d0; border-radius: 12px;
        padding: 10px 18px; font-weight: 600; font-size: 13.5px;
        text-decoration: none; transition: 0.2s;
    }
    .btn-template-download:hover { background: #16a34a; color: #fff; text-decoration: none; }

    .upload-container { width: 100%; }
    .upload-box {
        border: 2px dashed var(--border-color);
        border-radius: 16px;
        background-color: #f8fafc;
        padding: 28px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .upload-box:hover { border-color: var(--accent-blue); background-color: #f0f9ff; }
    .upload-box.has-file { border-style: solid; background-color: #fff; padding: 0; }
    .upload-content { pointer-events: none; }
    .upload-icon { font-size: 28px; color: #94a3b8; margin-bottom: 10px; }
    .upload-box:hover .upload-icon { color: var(--accent-blue); }
    .upload-text { font-size: 13.5px; color: var(--primary-dark); font-weight: 600; margin-bottom: 4px; }
    .upload-hint { font-size: 12px; color: var(--text-muted); }

    .import-file-selected {
        display: flex; align-items: center; gap: 10px;
        padding: 14px 16px; font-size: 13.5px; color: var(--primary-dark);
    }
    .import-file-selected span { flex: 1; word-break: break-all; }
    .btn-remove-file {
        flex-shrink: 0; width: 26px; height: 26px; border: none; border-radius: 8px;
        background: #fff1f2; color: #e11d48; display: inline-flex;
        align-items: center; justify-content: center; cursor: pointer;
    }
    .btn-remove-file:hover { background: #e11d48; color: #fff; }

    .btn-back {
        display: inline-flex; align-items: center; gap: 8px;
        background: #fff; color: var(--text-muted);
        border: 1.5px solid var(--border-color); border-radius: 14px;
        padding: 11px 22px; font-weight: 600; font-size: 14px;
        cursor: pointer; transition: 0.2s ease;
    }
    .btn-back:hover { color: var(--primary-dark); border-color: #cbd5e1; background: #f1f5f9; }

    .btn-submit-import {
        display: inline-flex; align-items: center; gap: 8px;
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        color: #fff; border: none; border-radius: 14px;
        padding: 11px 24px; font-weight: 600; font-size: 14px;
        cursor: pointer; transition: 0.25s ease;
        box-shadow: 0 8px 20px rgba(14, 165, 233, 0.25);
    }
    .btn-submit-import:hover { background: linear-gradient(135deg, #0284c7, #0369a1); }

    /* ===== SOAL CARD ===== */
    .soal-card {
        background: var(--surface-white);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 20px 22px;
        margin-bottom: 14px;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.02);
        transition: all 0.2s ease;
    }

    .soal-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
    }

    .soal-card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 10px;
        flex-wrap: wrap;
    }

    .soal-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 10px;
        background: #f1f5f9;
        color: var(--primary-dark);
        font-weight: 700;
        font-size: 13px;
        flex-shrink: 0;
    }

    .soal-badges { display: flex; gap: 6px; flex-wrap: wrap; align-items: center; }

    .badge-jenis {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 11px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.3px;
    }

    .badge-jenis.pilihan_ganda { background: #eff6ff; color: #2563eb; }
    .badge-jenis.essay { background: #faf5ff; color: #7e22ce; }
    .badge-jenis.isian { background: #f0fdf4; color: #16a34a; }

    .badge-bobot {
        background: #fff7ed;
        color: #c2410c;
        padding: 4px 11px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 700;
    }

    .soal-text {
        color: var(--primary-dark);
        font-size: 14px;
        line-height: 1.6;
        margin: 10px 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .soal-meta {
        font-size: 12px;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .soal-actions {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-shrink: 0;
    }

    .action-icon-btn {
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s ease;
        font-size: 13px;
        cursor: pointer;
    }
    .action-icon-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,0.12); }

    .btn-icon-edit { background: #fefce8; color: #a16207; }
    .btn-icon-edit:hover { background: #a16207; color: white; }

    .btn-icon-delete { background: #fff1f2; color: #e11d48; }
    .btn-icon-delete:hover { background: #e11d48; color: white; }

    .empty-state {
        padding: 60px 24px;
        text-align: center;
        background: var(--surface-white);
        border-radius: 20px;
        border: 1px solid var(--border-color);
    }

    .empty-icon-wrap {
        width: 80px;
        height: 80px;
        border-radius: 24px;
        background: #f1f5f9;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        font-size: 32px;
        color: #94a3b8;
    }

    @media (max-width: 767.98px) {
        .page-header-soal { padding: 24px 20px; border-radius: 18px; }
        .page-header-soal .d-flex.justify-content-between { flex-direction: column; align-items: stretch !important; gap: 16px; }
        .header-btn { width: 100%; justify-content: center; }
        .header-btn-outline { width: 100%; justify-content: center; }
        .page-header-soal .d-flex.gap-2 { flex-direction: column; width: 100%; }
        .soal-card { padding: 16px; border-radius: 16px; }
        .soal-actions { width: 100%; justify-content: flex-end; margin-top: 8px; }
        .soal-card-top { flex-direction: column; }
        .filter-bar { padding: 12px; }
        .filter-search-wrap { min-width: 100%; order: 1; }
        .filter-select { flex: 1; order: 2; }
        .filter-reset { order: 3; }
        .filter-count { width: 100%; order: 4; text-align: center; padding-top: 4px; }
    }
</style>

<div class="container-fluid py-2">

    {{-- Page Header --}}
    <div class="page-header-soal mb-4">
        <div class="breadcrumb-nav mb-3">
            <a href="{{ route('dashboard-guru.bank-soal.index') }}">Bank Soal</a>
            <span class="mx-2">/</span>
            <a href="{{ route('dashboard-guru.bank-soal.show', $bank_soal->id) }}">{{ Str::limit($bank_soal->nama_bank_soal, 30) }}</a>
            <span class="mx-2">/</span>
            <span class="text-white">Daftar Soal</span>
        </div>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="fw-bold mb-1" style="letter-spacing: -0.5px;">Daftar Soal</h3>
                <p class="text-light opacity-75 mb-0 small">
                    {{ $bank_soal->nama_bank_soal }} &middot; {{ $soals->count() }} soal
                </p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="header-btn-outline" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="fa-solid fa-file-excel"></i>
                    Import Excel
                </button>
                <a href="{{ route('dashboard-guru.bank-soal.soal.create', $bank_soal->id) }}" class="header-btn">
                    <i class="fa-solid fa-plus"></i>
                    Tambah Soal
                </a>
            </div>
        </div>
    </div>

    {{-- Total Bobot Indicator --}}
    @php $totalBobot = $soals->sum('bobot'); @endphp
    <div class="d-flex align-items-center gap-3 flex-wrap mb-4 px-3 py-3 rounded-4 border {{ $totalBobot == 100 ? 'border-success bg-success bg-opacity-10' : 'border-danger bg-danger bg-opacity-10' }}">
        <div class="d-flex align-items-center gap-2">
            @if($totalBobot == 100)
                <i class="fa-solid fa-circle-check text-success fs-5"></i>
            @else
                <i class="fa-solid fa-triangle-exclamation text-danger fs-5"></i>
            @endif
            <span style="font-size: 14px;">
                <strong>Total Bobot: {{ $totalBobot }} / 100</strong>
                @if($totalBobot == 100)
                    <span class="text-success ms-1" style="font-size: 12px;">— Siap dipublikasikan</span>
                @elseif($totalBobot < 100)
                    <span class="text-danger ms-1" style="font-size: 12px;">— Kurang {{ 100 - $totalBobot }} bobot lagi</span>
                @else
                    <span class="text-danger ms-1" style="font-size: 12px;">— Kelebihan {{ $totalBobot - 100 }} bobot</span>
                @endif
            </span>
        </div>
        <div class="flex-grow-1">
            <div class="progress" style="height: 8px; border-radius: 4px; background: rgba(0,0,0,0.08);">
                <div class="progress-bar {{ $totalBobot == 100 ? 'bg-success' : ($totalBobot < 100 ? 'bg-warning' : 'bg-danger') }}"
                     style="width: {{ min($totalBobot, 100) }}%; border-radius: 4px; transition: width 0.4s ease;"></div>
            </div>
        </div>
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

    @if(session('import_errors'))
    <div class="alert alert-warning rounded-4 border-0 shadow-sm p-3 mb-4">
        <div class="d-flex align-items-center mb-2">
            <i class="fa-solid fa-triangle-exclamation fs-5 me-2"></i>
            <strong>Baris yang dilewati saat import:</strong>
        </div>
        <ul class="mb-0 ps-4" style="font-size: 13px; max-height: 180px; overflow-y: auto;">
            @foreach(session('import_errors') as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Filter & Pencarian --}}
    @if($soals->isNotEmpty())
    <div class="filter-bar">
        <div class="filter-search-wrap">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="filterSearch" class="filter-search-input" placeholder="Cari teks soal...">
        </div>
        <select id="filterJenis" class="filter-select">
            <option value="">Semua Jenis</option>
            <option value="pilihan_ganda">Pilihan Ganda</option>
            <option value="essay">Essay</option>
            <option value="isian">Isian Singkat</option>
        </select>
        <button type="button" id="filterReset" class="filter-reset" title="Reset filter">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <span class="filter-count" id="filterCount">
            Menampilkan <strong>{{ $soals->count() }}</strong> dari <strong>{{ $soals->count() }}</strong> soal
        </span>
    </div>
    @endif

    {{-- Daftar Soal --}}
    @forelse($soals as $index => $soal)
        <div class="soal-card"
             data-jenis="{{ $soal->jenis_soal }}"
             data-teks="{{ strtolower(strip_tags($soal->teks_soal)) }}">
            <div class="soal-card-top">
                <div class="d-flex align-items-center gap-3">
                    <span class="soal-number">{{ $index + 1 }}</span>
                    <div class="soal-badges">
                        <span class="badge-jenis {{ $soal->jenis_soal }}">
                            {{ \App\Models\Soal::jenisLabel($soal->jenis_soal) }}
                        </span>
                        <span class="badge-bobot">Bobot {{ $soal->bobot }}</span>
                        @if($soal->jenis_soal === 'pilihan_ganda')
                            <span class="badge-bobot" style="background:#f1f5f9;color:#475569;">
                                {{ $soal->pilihan_jawabans_count }} opsi
                            </span>
                        @endif
                    </div>
                </div>

                <div class="soal-actions">
                    <a href="{{ route('dashboard-guru.bank-soal.soal.edit', [$bank_soal->id, $soal->id]) }}"
                       class="action-icon-btn btn-icon-edit" title="Edit">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <form action="{{ route('dashboard-guru.bank-soal.soal.destroy', [$bank_soal->id, $soal->id]) }}"
                          method="POST" class="d-inline form-delete-soal">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-icon-btn btn-icon-delete" title="Hapus">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

            <p class="soal-text">{{ strip_tags($soal->teks_soal) }}</p>

            <div class="soal-meta">
                <i class="fa-regular fa-clock"></i>
                Ditambahkan {{ $soal->created_at->diffForHumans() }}
            </div>
        </div>
    @empty
        <div class="empty-state">
            <div class="empty-icon-wrap">
                <i class="fa-solid fa-file-circle-question"></i>
            </div>
            <h6 class="fw-bold text-secondary mb-1">Belum ada soal</h6>
            <p class="text-muted small mb-4">
                Bank soal ini masih kosong.<br>
                Mulai dengan menambahkan soal pertama.
            </p>
            <a href="{{ route('dashboard-guru.bank-soal.soal.create', $bank_soal->id) }}" class="header-btn">
                <i class="fa-solid fa-plus"></i>
                Tambah Soal Pertama
            </a>
        </div>
    @endforelse

    {{-- Tampil kalau filter/pencarian tidak menemukan hasil (soal tetap ada, cuma tidak cocok filter) --}}
    <div class="no-match-state" id="noMatchState">
        <div class="empty-icon-wrap">
            <i class="fa-solid fa-filter-circle-xmark"></i>
        </div>
        <h6 class="fw-bold text-secondary mb-1">Tidak ada soal yang cocok</h6>
        <p class="text-muted small mb-0">Coba ubah kata kunci pencarian atau filter jenis soal.</p>
    </div>

    <div class="mt-4">
        <a href="{{ route('dashboard-guru.bank-soal.show', $bank_soal->id) }}" class="text-decoration-none text-muted small">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Detail Bank Soal
        </a>
    </div>

</div>

<script>
document.querySelectorAll('.form-delete-soal').forEach(function (form) {
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Hapus Soal?',
            text: 'Soal ini beserta opsi jawabannya (jika ada) akan dihapus permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fa-solid fa-trash me-1"></i> Ya, Hapus',
            cancelButtonText: 'Batal',
            customClass: { popup: 'rounded-4', confirmButton: 'rounded-3', cancelButton: 'rounded-3' }
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
});

// ===== FILTER & PENCARIAN SOAL =====
(function () {
    const searchInput = document.getElementById('filterSearch');
    const jenisSelect = document.getElementById('filterJenis');
    const resetBtn = document.getElementById('filterReset');
    const countEl = document.getElementById('filterCount');
    const noMatchState = document.getElementById('noMatchState');

    if (!searchInput) return; // tidak ada soal sama sekali, filter bar tidak dirender

    const cards = Array.from(document.querySelectorAll('.soal-card'));
    const totalCount = cards.length;

    function applyFilter() {
        const keyword = searchInput.value.trim().toLowerCase();
        const jenis = jenisSelect.value;
        let visibleCount = 0;

        cards.forEach(function (card) {
            const matchTeks = !keyword || card.dataset.teks.includes(keyword);
            const matchJenis = !jenis || card.dataset.jenis === jenis;
            const match = matchTeks && matchJenis;
            card.style.display = match ? '' : 'none';
            if (match) visibleCount++;
        });

        countEl.innerHTML = `Menampilkan <strong>${visibleCount}</strong> dari <strong>${totalCount}</strong> soal`;
        noMatchState.style.display = visibleCount === 0 ? 'block' : 'none';
        resetBtn.classList.toggle('show', !!keyword || !!jenis);
    }

    searchInput.addEventListener('input', applyFilter);
    jenisSelect.addEventListener('change', applyFilter);
    resetBtn.addEventListener('click', function () {
        searchInput.value = '';
        jenisSelect.value = '';
        applyFilter();
    });
})();
</script>

{{-- Modal Import Excel --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden;">
            <div class="modal-header" style="border-bottom: 1px solid var(--border-color); padding: 20px 24px;">
                <h5 class="modal-title fw-bold" id="importModalLabel" style="color: var(--primary-dark);">
                    <i class="fa-solid fa-file-excel me-2 text-success"></i>Import Soal dari Excel
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('dashboard-guru.bank-soal.soal.import', $bank_soal->id) }}"
                  method="POST" enctype="multipart/form-data" id="formImportSoal">
                @csrf

                <div class="modal-body" style="padding: 24px;">
                    <div class="import-step mb-4">
                        <span class="import-step-num">1</span>
                        <div>
                            <p class="import-step-title mb-2">Belum punya template?</p>
                            <a href="{{ route('dashboard-guru.bank-soal.soal.template', $bank_soal->id) }}" class="btn-template-download">
                                <i class="fa-solid fa-download"></i> Unduh Template Excel
                            </a>
                        </div>
                    </div>

                    <div class="import-step">
                        <span class="import-step-num">2</span>
                        <div class="flex-grow-1">
                            <p class="import-step-title">Upload file yang sudah diisi</p>

                            <div class="upload-container">
                                <input type="file" name="file_import" id="importFileInput" class="d-none" accept=".xlsx,.xls" required>

                                <div class="upload-box" id="importUploadBox" onclick="document.getElementById('importFileInput').click()">
                                    <div class="upload-content" id="importUploadContent">
                                        <div class="upload-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                                        <div class="upload-text">Klik untuk pilih file .xlsx / .xls</div>
                                        <div class="upload-hint">Maksimal ukuran 2MB</div>
                                    </div>

                                    <div class="import-file-selected" id="importFileSelected" style="display: none;">
                                        <i class="fa-solid fa-file-excel text-success"></i>
                                        <span id="importFileName"></span>
                                        <button type="button" class="btn-remove-file" onclick="removeImportFile(event)" title="Batal pilih file">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @error('file_import')
                                <div class="field-error mt-2"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="modal-footer" style="border-top: 1px solid var(--border-color); padding: 16px 24px; gap: 10px;">
                    <button type="button" class="btn-back" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-submit-import" id="btnSubmitImport">
                        <i class="fa-solid fa-file-import"></i> Import Soal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection