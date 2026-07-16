@extends('layouts.app')
@section('title', 'Bank Soal')

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

    /* ===== PAGE HEADER ===== */
    .page-header {
        background: linear-gradient(135deg, var(--primary-dark), var(--secondary-dark));
        border-radius: 24px;
        padding: 32px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .page-header::after {
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

    /* ===== CONTENT CARD ===== */
    .content-card {
        background: var(--surface-white);
        border-radius: 24px;
        border: 1px solid var(--border-color);
        box-shadow: 0 12px 34px rgba(15, 23, 42, 0.03);
        padding: 12px;
    }

    /* ===== TABLE STYLING ===== */
    .table-responsive { border-radius: 16px; overflow: hidden; }

    .table thead th {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
        background-color: #f8fafc;
        padding: 16px;
        border-bottom: 1px solid var(--border-color);
        font-weight: 700;
        white-space: nowrap;
    }

    .table tbody td {
        padding: 16px;
        vertical-align: middle;
        border-color: #f1f5f9;
        font-size: 14px;
    }

    .table tbody tr { transition: background 0.2s ease; }
    .table tbody tr:hover { background-color: #f8fafc; }

    /* ===== ACTION BUTTONS ===== */
    .action-icon-btn {
        width: 38px;
        height: 38px;
        border: none;
        border-radius: 11px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-left: 4px;
        text-decoration: none;
        transition: all 0.2s ease;
        font-size: 14px;
        cursor: pointer;
    }

    .action-icon-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,0.12); }

    .btn-icon-view   { background: #eff6ff; color: #2563eb; }
    .btn-icon-view:hover { background: #2563eb; color: white; }

    .btn-icon-delete { background: #fff1f2; color: #e11d48; }
    .btn-icon-delete:hover { background: #e11d48; color: white; }

    /* ===== BADGE STATUS ===== */
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

    .status-badge.published {
        background: #f0fdf4;
        color: #16a34a;
        border: 1px solid #bbf7d0;
    }

    .status-badge.draft {
        background: #fffbeb;
        color: #d97706;
        border: 1px solid #fde68a;
    }

    .status-badge .dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .status-badge.published .dot { background: #16a34a; }
    .status-badge.draft .dot { background: #d97706; }

    /* ===== HEADER ACTION BUTTONS ===== */
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
    }

    .btn-add-soal {
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        color: #fff;
        box-shadow: 0 8px 20px rgba(14, 165, 233, 0.3);
    }

    .btn-add-soal:hover {
        color: #fff;
        background: linear-gradient(135deg, #0284c7, #0369a1);
        transform: translateY(-3px);
        box-shadow: 0 14px 28px rgba(14, 165, 233, 0.4);
        text-decoration: none;
    }

    .btn-import-soal {
        background: linear-gradient(135deg, #16a34a, #15803d);
        color: #fff;
        box-shadow: 0 8px 20px rgba(22, 163, 74, 0.3);
    }

    .btn-import-soal:hover {
        color: #fff;
        background: linear-gradient(135deg, #15803d, #166534);
        transform: translateY(-3px);
        box-shadow: 0 14px 28px rgba(22, 163, 74, 0.4);
        text-decoration: none;
    }

    /* ===== IMPORT MODAL ===== */
    #importSoalModal .modal-content {
        border: none;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 25px 60px rgba(15, 23, 42, 0.18);
    }

    #importSoalModal .modal-header {
        background: linear-gradient(135deg, #15803d, #16a34a);
        border: none;
        padding: 22px 28px;
    }

    #importSoalModal .modal-body {
        padding: 28px;
        background: #f8fafc;
    }

    #importSoalModal .modal-footer {
        background: #fff;
        border-top: 1px solid #edf2f7;
        padding: 18px 28px;
    }

    .upload-box {
        border: 2px dashed #16a34a;
        border-radius: 18px;
        background: white;
        padding: 32px;
        text-align: center;
        transition: 0.3s;
    }

    .upload-box:hover {
        background: #f0fdf4;
        border-color: #15803d;
    }

    .upload-box i { font-size: 48px; color: #16a34a; margin-bottom: 14px; }
    .upload-box h6 { font-weight: 700; margin-bottom: 6px; }
    .upload-box p { color: #64748b; font-size: 13px; margin-bottom: 16px; }

    .import-info-box {
        background: #eef9f1;
        border: 1px solid #b7e4c7;
        border-radius: 14px;
        padding: 16px 20px;
        margin-bottom: 20px;
    }

    .import-info-box strong { color: #166534; font-size: 13px; }
    .import-info-box ul { margin-top: 8px; padding-left: 18px; margin-bottom: 0; }
    .import-info-box li { color: #475569; font-size: 13px; margin-bottom: 4px; }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        padding: 60px 24px;
        text-align: center;
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

    /* ===== NAMA BANK SOAL ===== */
    .soal-name {
        font-weight: 700;
        color: var(--primary-dark);
        font-size: 14px;
    }

    .soal-meta {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 2px;
    }

    /* ===== MOBILE CARD LAYOUT ===== */
    @media (max-width: 767.98px) {
        .page-header {
            padding: 24px 20px;
            border-radius: 18px;
            text-align: center;
        }

        .page-header .header-inner {
            flex-direction: column;
            align-items: center;
            gap: 18px;
        }

        .btn-add-soal, .btn-import-soal { width: 100%; justify-content: center; }
        .header-btn-group { flex-direction: column; width: 100%; gap: 10px !important; }

        .content-card { padding: 4px; border-radius: 18px; }

        /* Convert table to card list on mobile */
        .table-responsive table,
        .table-responsive thead,
        .table-responsive tbody,
        .table-responsive th,
        .table-responsive td,
        .table-responsive tr { display: block; }

        .table-responsive thead tr {
            position: absolute;
            top: -9999px;
            left: -9999px;
        }

        .table-responsive tbody tr {
            border: 1px solid var(--border-color);
            border-radius: 16px;
            margin-bottom: 12px;
            padding: 14px 16px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        }

        .table-responsive tbody td {
            border: none;
            border-bottom: 1px dashed #f1f5f9;
            position: relative;
            padding: 10px 12px 10px 48% !important;
            text-align: right !important;
            min-height: 44px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            font-size: 13px;
        }

        .table-responsive tbody td:last-child {
            border-bottom: none;
        }

        .table-responsive tbody td::before {
            position: absolute;
            left: 12px;
            width: 44%;
            text-align: left;
            font-weight: 700;
            color: var(--text-muted);
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.3;
        }

        .table-responsive tbody td:nth-of-type(1)::before { content: "No"; }
        .table-responsive tbody td:nth-of-type(2)::before { content: "Nama Bank Soal"; }
        .table-responsive tbody td:nth-of-type(3)::before { content: "Mapel"; }
        .table-responsive tbody td:nth-of-type(4)::before { content: "Jenjang"; }
        .table-responsive tbody td:nth-of-type(5)::before { content: "Deskripsi"; }
        .table-responsive tbody td:nth-of-type(6)::before { content: "Dibuat"; }
        .table-responsive tbody td:nth-of-type(7)::before { content: "Status"; }
        .table-responsive tbody td:nth-of-type(8)::before { content: "Aksi"; }

        .soal-name { text-align: right; }
        .soal-meta { text-align: right; }
    }
</style>

<div class="container-fluid py-2">

    {{-- Page Header --}}
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap header-inner">
            <div>
                <span class="badge bg-info bg-opacity-25 text-info px-3 py-2 rounded-pill mb-2 fw-semibold"
                      style="font-size: 11px; letter-spacing: 0.5px;">
                    BANK SOAL
                </span>
                <h3 class="fw-bold mb-1" style="letter-spacing: -0.5px;">
                    Daftar Bank Soal
                </h3>
                <p class="text-light opacity-75 mb-0 small">
                    Kelola bank soal untuk mata pelajaran Anda.
                </p>
            </div>

            <div class="d-flex gap-2 flex-wrap header-btn-group">
                <button type="button"
                        class="header-btn btn-import-soal"
                        data-bs-toggle="modal"
                        data-bs-target="#importSoalModal">
                    <i class="fa-solid fa-file-excel"></i>
                    Import Excel
                </button>
                <a href="{{ route('dashboard-guru.bank-soal.create') }}" class="header-btn btn-add-soal">
                    <i class="fa-solid fa-plus"></i>
                    Tambah Bank Soal
                </a>
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

    {{-- Main Table Card --}}
    <div class="content-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nama Bank Soal</th>
                            <th>Mapel</th>
                            <th>Jenjang</th>
                            <th>Deskripsi</th>
                            <th>Dibuat</th>
                            <th>Status</th>
                            <th style="width: 110px;" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bankSoals as $index => $bs)
                        <tr>
                            {{-- No --}}
                            <td>
                                <span class="text-secondary fw-semibold">{{ $index + 1 }}</span>
                            </td>

                            {{-- Nama Bank Soal --}}
                            <td>
                                <div class="soal-name">{{ $bs->nama_bank_soal }}</div>
                            </td>

                            {{-- Mapel --}}
                            <td>
                                <span class="text-dark fw-medium">
                                    {{ $bs->mataPelajaran->nama_mapel ?? '-' }}
                                </span>
                            </td>

                            {{-- Jenjang --}}
                            <td>
                                <span class="badge bg-dark bg-opacity-10 text-dark px-2 py-1 rounded-3 fw-semibold" style="font-size: 12px;">
                                    {{ $bs->jenjang->nama_jenjang ?? '-' }}
                                </span>
                            </td>

                            {{-- Deskripsi --}}
                            <td>
                                @if($bs->deskripsi)
                                    <span class="text-muted" style="font-size: 13px;">
                                        {{ Str::limit($bs->deskripsi, 40, '...') }}
                                    </span>
                                @else
                                    <span class="text-muted fst-italic" style="font-size: 12px;">—</span>
                                @endif
                            </td>

                            {{-- Dibuat --}}
                            <td>
                                <span class="text-muted" style="font-size: 12px;">
                                    {{ $bs->created_at->format('d M Y') }}
                                </span>
                            </td>

                            {{-- Status --}}
                            <td>
                                @if($bs->is_publish)
                                    <span class="status-badge published">
                                        <span class="dot"></span>
                                        Publik
                                    </span>
                                @else
                                    <span class="status-badge draft">
                                        <span class="dot"></span>
                                        Draft
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="text-end">
                                <div class="d-inline-flex align-items-center">
                                    <a href="{{ route('dashboard-guru.bank-soal.show', $bs->id) }}"
                                       class="action-icon-btn btn-icon-view"
                                       title="Lihat Detail">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                    <form action="{{ route('dashboard-guru.bank-soal.destroy', $bs->id) }}"
                                          method="POST"
                                          class="d-inline form-delete">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="action-icon-btn btn-icon-delete"
                                                title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <div class="empty-icon-wrap">
                                        <i class="fa-solid fa-folder-open"></i>
                                    </div>
                                    <h6 class="fw-bold text-secondary mb-1">Belum ada bank soal</h6>
                                    <p class="text-muted small mb-4">
                                        Anda belum membuat bank soal apapun.<br>
                                        Mulai dengan menambahkan bank soal baru.
                                    </p>
                                    <a href="{{ route('dashboard-guru.bank-soal.create') }}" class="header-btn btn-add-soal">
                                        <i class="fa-solid fa-plus"></i>
                                        Tambah Bank Soal Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- Modal Import Excel --}}
<div class="modal fade" id="importSoalModal" tabindex="-1" aria-labelledby="importSoalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
        <div class="modal-content" id="importSoalModal">
            <div class="modal-header">
                <h5 class="modal-title text-white fw-bold d-flex align-items-center gap-2" id="importSoalModalLabel">
                    <span style="width:38px;height:38px;border-radius:10px;background:rgba(255,255,255,.15);display:inline-flex;align-items:center;justify-content:center;">
                        <i class="fa-solid fa-file-excel"></i>
                    </span>
                    Import Bank Soal dari Excel
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="import-info-box">
                    <strong><i class="fa-solid fa-circle-info me-2"></i>Petunjuk Import</strong>
                    <ul>
                        <li>Gunakan template Excel yang tersedia sebagai panduan format data.</li>
                        <li>Kolom yang wajib diisi: <strong>Nama Bank Soal, Mata Pelajaran, Jenjang</strong>.</li>
                        <li>Kolom <strong>Deskripsi</strong> bersifat opsional.</li>
                        <li>Format file yang diterima: <strong>.xlsx, .xls</strong>.</li>
                    </ul>
                </div>

                <form action="#" method="POST" enctype="multipart/form-data" id="form-import-soal">
                    @csrf
                    <div class="upload-box">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <h6>Pilih File Excel</h6>
                        <p>Seret & lepas file ke sini, atau klik tombol di bawah</p>
                        <input type="file"
                               name="file_excel"
                               id="file_excel"
                               accept=".xlsx,.xls"
                               class="form-control"
                               style="max-width: 340px; margin: auto;"
                               required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button"
                        class="btn btn-light border rounded-3 px-4 fw-semibold"
                        data-bs-dismiss="modal">Batal</button>
                <button type="submit"
                        form="form-import-soal"
                        class="btn fw-semibold rounded-3 px-4 text-white"
                        style="background: linear-gradient(135deg, #16a34a, #15803d); border: none;">
                    <i class="fa-solid fa-upload me-2"></i>Import Sekarang
                </button>
            </div>
        </div>
    </div>
</div>

{{-- SweetAlert2 Konfirmasi Hapus --}}
<script>
document.querySelectorAll('.form-delete').forEach(function(form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Hapus Bank Soal?',
            text: 'Seluruh soal di dalam bank soal ini juga akan dihapus. Tindakan ini tidak dapat dibatalkan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fa-solid fa-trash me-1"></i> Ya, Hapus',
            cancelButtonText: 'Batal',
            borderRadius: '16px',
            customClass: {
                popup: 'rounded-4',
                confirmButton: 'rounded-3',
                cancelButton: 'rounded-3',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>

@endsection