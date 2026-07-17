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
        .soal-card { padding: 16px; border-radius: 16px; }
        .soal-actions { width: 100%; justify-content: flex-end; margin-top: 8px; }
        .soal-card-top { flex-direction: column; }
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
            <a href="{{ route('dashboard-guru.bank-soal.soal.create', $bank_soal->id) }}" class="header-btn">
                <i class="fa-solid fa-plus"></i>
                Tambah Soal
            </a>
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

    {{-- Daftar Soal --}}
    @forelse($soals as $index => $soal)
        <div class="soal-card">
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
</script>

@endsection