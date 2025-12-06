@extends('layouts.admin')

@section('title', 'Laporan Donasi')

@section('content')
    <div class="content-area">
        <!-- Header -->
        <div class="page-header mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h2 class="page-title mb-2">
                        <i class="fas fa-file-invoice text-primary-green"></i> Laporan Transaksi Donasi
                    </h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.laporan.index') }}">Laporan</a></li>
                            <li class="breadcrumb-item active">Laporan Donasi</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card-custom mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-filter text-primary-green"></i> Filter Laporan
                    </h5>
                    <span class="badge bg-info px-3 py-2">
                        <i class="fas fa-calendar-alt me-1"></i> 
                        @php
                            $namaBulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                        @endphp
                        Periode: {{ $namaBulan[$bulan] ?? '' }} {{ $tahun }}
                    </span>
                </div>
                <form action="{{ route('admin.donasi.laporan') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="bulan" class="form-label fw-semibold">
                                <i class="fas fa-calendar text-muted me-1"></i> Bulan
                            </label>
                            <select name="bulan" id="bulan" class="form-select form-select-custom">
                                <option value="1" {{ $bulan == 1 ? 'selected' : '' }}>Januari</option>
                                <option value="2" {{ $bulan == 2 ? 'selected' : '' }}>Februari</option>
                                <option value="3" {{ $bulan == 3 ? 'selected' : '' }}>Maret</option>
                                <option value="4" {{ $bulan == 4 ? 'selected' : '' }}>April</option>
                                <option value="5" {{ $bulan == 5 ? 'selected' : '' }}>Mei</option>
                                <option value="6" {{ $bulan == 6 ? 'selected' : '' }}>Juni</option>
                                <option value="7" {{ $bulan == 7 ? 'selected' : '' }}>Juli</option>
                                <option value="8" {{ $bulan == 8 ? 'selected' : '' }}>Agustus</option>
                                <option value="9" {{ $bulan == 9 ? 'selected' : '' }}>September</option>
                                <option value="10" {{ $bulan == 10 ? 'selected' : '' }}>Oktober</option>
                                <option value="11" {{ $bulan == 11 ? 'selected' : '' }}>November</option>
                                <option value="12" {{ $bulan == 12 ? 'selected' : '' }}>Desember</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="tahun" class="form-label fw-semibold">
                                <i class="fas fa-calendar-check text-muted me-1"></i> Tahun
                            </label>
                            <select name="tahun" id="tahun" class="form-select form-select-custom">
                                @for($y = date('Y'); $y >= 2020; $y--)
                                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary-custom w-100">
                                <i class="fas fa-search me-2"></i> Tampilkan Laporan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistik Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="stat-card stat-success">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <small class="stat-label">Donasi Berhasil</small>
                        <h3 class="stat-value">{{ number_format($jumlahDonasi ?? 0) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card stat-warning">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <small class="stat-label">Menunggu Verifikasi</small>
                        <h3 class="stat-value">{{ number_format($jumlahMenunggu ?? 0) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card stat-danger">
                    <div class="stat-icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-content">
                        <small class="stat-label">Donasi Ditolak</small>
                        <h3 class="stat-value">{{ number_format($jumlahDitolak ?? 0) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card stat-primary">
                    <div class="stat-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-content">
                        <small class="stat-label">Total Nominal</small>
                        <h3 class="stat-value">Rp {{ number_format($totalDonasi ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Donasi -->
        <div class="card-custom">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                    <div>
                        <h5 class="fw-bold mb-1">
                            <i class="fas fa-table text-primary-green"></i> 
                            Data Donasi - {{ $namaBulan[$bulan] ?? '' }} {{ $tahun }}
                        </h5>
                        <p class="text-muted small mb-0">
                            <i class="fas fa-info-circle me-1"></i>
                            Total {{ $donasis->total() }} transaksi ditemukan
                        </p>
                    </div>
                    @if($donasis->count() > 0)
                        <a href="{{ route('admin.donasi.laporan.export-pdf', ['tahun' => $tahun, 'bulan' => $bulan]) }}" 
                           class="btn btn-danger btn-export shadow-sm">
                            <i class="fas fa-file-pdf me-2"></i> Export PDF
                        </a>
                    @endif
                </div>

                @if($donasis->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle modern-table">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center">No</th>
                                    <th width="12%">Tanggal</th>
                                    <th width="20%">Donatur</th>
                                    <th width="22%">Kebutuhan</th>
                                    <th width="15%" class="text-end">Jumlah</th>
                                    <th width="12%" class="text-center">Status</th>
                                    <th width="14%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($donasis as $index => $donasi)
                                    <tr>
                                        <td class="text-center fw-semibold">{{ $donasis->firstItem() + $index }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-calendar-day text-muted me-2"></i>
                                                <div>
                                                    <small class="d-block fw-semibold">{{ $donasi->created_at->format('d/m/Y') }}</small>
                                                    <small class="text-muted">{{ $donasi->created_at->format('H:i') }} WIB</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-2">
                                                    {{ strtoupper(substr($donasi->user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <strong class="d-block">{{ $donasi->user->name }}</strong>
                                                    <small class="text-muted">{{ $donasi->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong class="d-block mb-1">{{ $donasi->kebutuhan->nama_kebutuhan }}</strong>
                                            <span class="badge badge-sm {{ $donasi->kebutuhan->jenis == 'uang' ? 'bg-success' : 'bg-info' }}">
                                                <i class="fas {{ $donasi->kebutuhan->jenis == 'uang' ? 'fa-money-bill-wave' : 'fa-box' }} me-1"></i>
                                                {{ ucfirst($donasi->kebutuhan->jenis) }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            @if($donasi->kebutuhan->jenis == 'uang')
                                                <strong class="text-success fs-6">Rp {{ number_format($donasi->jumlah_donasi, 0, ',', '.') }}</strong>
                                            @else
                                                <strong class="text-info fs-6">{{ number_format($donasi->jumlah_donasi, 2, ',', '.') }}</strong>
                                                <small class="text-muted d-block">{{ $donasi->kebutuhan->satuan ?? 'pcs' }}</small>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($donasi->status == 'berhasil')
                                                <span class="badge bg-success-custom px-3 py-2">
                                                    <i class="fas fa-check-circle me-1"></i> Berhasil
                                                </span>
                                            @elseif($donasi->status == 'menunggu')
                                                <span class="badge bg-warning-custom text-dark px-3 py-2">
                                                    <i class="fas fa-clock me-1"></i> Menunggu
                                                </span>
                                            @else
                                                <span class="badge bg-danger-custom px-3 py-2">
                                                    <i class="fas fa-times-circle me-1"></i> Ditolak
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.donasi.show', $donasi) }}" 
                                               class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                               data-bs-toggle="tooltip"
                                               title="Lihat Detail">
                                                <i class="fas fa-eye me-1"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <div class="text-muted small">
                            <i class="fas fa-info-circle me-1"></i>
                            Menampilkan {{ $donasis->firstItem() }} sampai {{ $donasis->lastItem() }} dari {{ $donasis->total() }} transaksi
                        </div>
                        <div>
                            {{ $donasis->appends(['tahun' => $tahun, 'bulan' => $bulan])->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="empty-state">
                            <div class="empty-icon mb-3">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <h5 class="text-muted mb-2">Tidak Ada Data</h5>
                            <p class="text-muted mb-4">Tidak ada transaksi donasi untuk periode {{ $namaBulan[$bulan] ?? '' }} {{ $tahun }}</p>
                            <a href="{{ route('admin.donasi.laporan') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-refresh me-2"></i> Reset Filter
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    :root {
        --primary-green: #10b981;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --primary: #8b5cf6;
    }

    /* Page Header */
    .page-header {
        margin-bottom: 1.5rem;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1f2937;
    }

    .text-primary-green {
        color: var(--primary-green) !important;
    }

    .breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
    }

    .breadcrumb-item a {
        color: var(--primary-green);
        text-decoration: none;
        transition: color 0.2s;
    }

    .breadcrumb-item a:hover {
        color: #059669;
        text-decoration: underline;
    }

    .breadcrumb-item.active {
        color: #6b7280;
    }

    /* Card Custom */
    .card-custom {
        background: white;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: none;
        overflow: hidden;
    }

    .card-custom .card-body {
        padding: 1.5rem;
    }

    /* Stat Cards */
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.12);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        flex-shrink: 0;
    }

    .stat-success .stat-icon {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .stat-warning .stat-icon {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    .stat-danger .stat-icon {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }

    .stat-primary .stat-icon {
        background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
    }

    .stat-content {
        flex: 1;
    }

    .stat-label {
        display: block;
        color: #6b7280;
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
        line-height: 1.2;
    }

    /* Table */
    .modern-table {
        margin-bottom: 0;
    }

    .modern-table thead th {
        background: #f9fafb;
        font-weight: 600;
        color: #374151;
        border-bottom: 2px solid #e5e7eb;
        padding: 1rem 0.75rem;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .modern-table tbody td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6;
    }

    .modern-table tbody tr {
        transition: background-color 0.2s;
    }

    .modern-table tbody tr:hover {
        background-color: #f9fafb;
    }

    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Avatar Circle */
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-green) 0%, #059669 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1rem;
        flex-shrink: 0;
    }

    /* Badges */
    .badge-sm {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        font-weight: 600;
    }

    .bg-success-custom {
        background: #10b981 !important;
    }

    .bg-warning-custom {
        background: #fbbf24 !important;
    }

    .bg-danger-custom {
        background: #ef4444 !important;
    }

    /* Buttons */
    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary-green) 0%, #059669 100%);
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        color: white;
    }

    .btn-export {
        background: #dc2626;
        color: white;
        border: none;
        padding: 0.625rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-export:hover {
        background: #b91c1c;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        color: white;
    }

    .btn-outline-primary {
        border-color: var(--primary-green);
        color: var(--primary-green);
        transition: all 0.3s ease;
    }

    .btn-outline-primary:hover {
        background: var(--primary-green);
        border-color: var(--primary-green);
        color: white;
    }

    /* Empty State */
    .empty-state {
        padding: 3rem 2rem;
    }

    .empty-icon {
        font-size: 4rem;
        color: #d1d5db;
    }

    /* Form Elements */
    .form-select-custom, .form-control-custom {
        border-radius: 8px;
        border: 1px solid #d1d5db;
        padding: 0.625rem 0.875rem;
        font-size: 0.9375rem;
        transition: all 0.2s;
    }

    .form-select-custom:focus, .form-control-custom:focus {
        border-color: var(--primary-green);
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    .form-label {
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
        color: #374151;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stat-card {
            flex-direction: column;
            text-align: center;
        }

        .stat-value {
            font-size: 1.5rem;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .table-responsive {
            font-size: 0.875rem;
        }

        .btn-export {
            width: 100%;
            margin-top: 1rem;
        }
    }

    @media (max-width: 576px) {
        .card-custom .card-body {
            padding: 1rem;
        }

        .stat-card {
            padding: 1rem;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
        }

        .stat-value {
            font-size: 1.25rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap tooltips
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(el => new bootstrap.Tooltip(el));

        // Auto submit form on select change (optional)
        const selectBulan = document.getElementById('bulan');
        const selectTahun = document.getElementById('tahun');
        
        if (selectBulan && selectTahun) {
            // Uncomment lines below to enable auto-submit on change
            // selectBulan.addEventListener('change', function() {
            //     this.form.submit();
            // });
            
            // selectTahun.addEventListener('change', function() {
            //     this.form.submit();
            // });
        }

        // Smooth scroll to top after pagination
        const paginationLinks = document.querySelectorAll('.pagination a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function() {
                setTimeout(() => {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }, 100);
            });
        });
    });
</script>
@endpush