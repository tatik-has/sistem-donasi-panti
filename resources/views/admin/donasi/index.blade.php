@extends('layouts.admin')

@section('title', 'Manajemen Donasi')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-donasi.css') }}">

<div class="content-area">
    <!-- Alert Success -->
    @if(session('success'))
        <div class="alert alert-success mb-4">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
        </div>
    @endif
    
    <!-- Page Header -->
    <div class="page-header mb-4">
        <h2 class="page-title">
            <i class="fas fa-money-check-alt"></i>
            Manajemen Donasi
        </h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Daftar Donasi</li>
            </ol>
        </nav>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="donasi-stats-card warning">
                <div class="d-flex align-items-center">
                    <div class="donasi-stats-icon warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="donasi-stats-info">
                        <span class="donasi-stats-label">Menunggu Verifikasi</span>
                        <h3 class="donasi-stats-value warning">{{ $totalMenunggu }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="donasi-stats-card success">
                <div class="d-flex align-items-center">
                    <div class="donasi-stats-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="donasi-stats-info">
                        <span class="donasi-stats-label">Total Donasi Berhasil</span>
                        <h3 class="donasi-stats-value success">{{ $totalBerhasil }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="donasi-stats-card danger">
                <div class="d-flex align-items-center">
                    <div class="donasi-stats-icon danger">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="donasi-stats-info">
                        <span class="donasi-stats-label">Donasi Ditolak</span>
                        <h3 class="donasi-stats-value danger">{{ $totalDitolak }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="donasi-table-card">
        <div class="donasi-table-header">
            <h5 class="donasi-table-title">
                <i class="fas fa-list-alt"></i>
                Semua Data Donasi
            </h5>
        </div>
        
        <div class="donasi-table-body">
            <!-- Filter Toolbar -->
            <div class="filter-toolbar">
                <form action="{{ route('admin.donasi.index') }}" method="GET" class="filter-form">
                    <select name="status" class="filter-select">
                        <option value="">Semua Status</option>
                        <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>
                            Menunggu Verifikasi
                        </option>
                        <option value="berhasil" {{ request('status') == 'berhasil' ? 'selected' : '' }}>
                            Berhasil
                        </option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>
                            Ditolak
                        </option>
                    </select>
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-filter"></i>
                        Filter
                    </button>
                    <a href="{{ route('admin.donasi.index') }}" class="btn-reset">
                        Reset
                    </a>
                </form>
                
                <a href="{{ route('admin.donasi.laporan') }}" class="btn-report">
                    <i class="fas fa-file-export"></i>
                    Lihat Laporan
                </a>
            </div>
            
            <!-- Table -->
            <div class="table-responsive">
                <table class="donasi-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 13%;">Tanggal</th>
                            <th style="width: 20%;">Donatur</th>
                            <th style="width: 22%;">Kebutuhan</th>
                            <th style="width: 13%;">Jumlah</th>
                            <th style="width: 12%;">Status</th>
                            <th style="width: 12%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($donasis as $index => $donasi)
                            <tr>
                                <td>{{ $donasis->firstItem() + $index }}</td>
                                <td>{{ $donasi->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="donatur-name">{{ $donasi->user->name ?? 'Anonim' }}</span>
                                    <span class="donatur-email">{{ $donasi->user->email ?? '-' }}</span>
                                </td>
                                <td>
                                    {{ $donasi->kebutuhan->nama_kebutuhan ?? 'N/A' }}
                                    @if($donasi->kebutuhan)
                                        @if($donasi->kebutuhan->jenis == 'uang')
                                            <span class="kebutuhan-badge uang">💰 Uang</span>
                                        @else
                                            <span class="kebutuhan-badge barang">📦 Barang</span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <strong class="amount-value">
                                        Rp {{ number_format($donasi->jumlah_donasi, 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td>
                                    @if($donasi->status === 'menunggu')
                                        <span class="status-badge menunggu">Menunggu</span>
                                    @elseif($donasi->status === 'berhasil')
                                        <span class="status-badge berhasil">Berhasil</span>
                                    @else
                                        <span class="status-badge ditolak">Ditolak</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-group">
                                        <a href="{{ route('admin.donasi.show', $donasi) }}" 
                                            class="btn-table-action view"
                                            data-bs-toggle="tooltip" 
                                            data-bs-placement="top"
                                            title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($donasi->status === 'menunggu')
                                        <a href="{{ route('admin.donasi.show', $donasi) }}#verifikasi" 
                                            class="btn-table-action verify"
                                            data-bs-toggle="tooltip" 
                                            data-bs-placement="top"
                                            title="Verifikasi">
                                            <i class="fas fa-clipboard-check"></i>
                                        </a>
                                        @endif
                                        
                                        <button type="button" 
                                            class="btn-table-action delete btn-delete-donasi"
                                            data-id="{{ $donasi->id }}"
                                            data-bs-toggle="tooltip" 
                                            data-bs-placement="top"
                                            title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state-donasi">
                                        <i class="fas fa-inbox empty-icon"></i>
                                        <p class="empty-text">
                                            Tidak ada data donasi ditemukan.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(isset($donasis) && $donasis->hasPages())
            <div class="mt-4">
                {{ $donasis->appends(request()->except('page'))->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteDonasiModal" tabindex="-1" aria-labelledby="deleteDonasiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header danger">
                <h5 class="modal-title" id="deleteDonasiModalLabel">
                    <i class="fas fa-trash-alt"></i>
                    Hapus Donasi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus transaksi donasi <strong id="donasiIdText" class="text-danger"></strong> ini secara permanen?</p>
                <div class="delete-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    Jika donasi ini berstatus 'Berhasil', jumlah terkumpul di Kebutuhan akan otomatis dikurangi.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-filter" data-bs-dismiss="modal">Batal</button>
                <form id="deleteDonasiForm" method="POST" action="" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-filter" style="background: var(--danger);">
                        <i class="fas fa-trash"></i>
                        Hapus Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi Tooltip
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Event listener untuk tombol hapus
        const deleteButtons = document.querySelectorAll('.btn-delete-donasi');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const donasiId = this.getAttribute('data-id');
                const modal = new bootstrap.Modal(document.getElementById('deleteDonasiModal'));
                
                // Isi ID donasi di modal
                document.getElementById('donasiIdText').textContent = '#' + donasiId.padStart(4, '0');
                
                // Set action form delete
                const deleteForm = document.getElementById('deleteDonasiForm');
                const deleteRoute = '{{ route('admin.donasi.destroy', ['donasi' => ':id']) }}'; 
                deleteForm.action = deleteRoute.replace(':id', donasiId);
                
                modal.show();
            });
        });
    });
</script>
@endpush