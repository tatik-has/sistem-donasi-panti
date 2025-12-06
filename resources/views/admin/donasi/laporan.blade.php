@extends('layouts.admin')

@section('title', 'Laporan Donasi')

@section('content')

<!-- ============================================== -->
<!-- Topbar -->
<div class="topbar no-print">
    <div class="topbar-left">
        <button class="mobile-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <h5>Laporan Donasi</h5>
    </div>
    <div class="topbar-right">
        <div class="user-profile">
            <div class="user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="user-info">
                <div class="name">{{ Auth::user()->name ?? 'Admin Panti' }}</div>
                <div class="role">Administrator</div>
            </div>
            <i class="fas fa-chevron-down" style="color:#6c757d;font-size:0.8rem;"></i>
        </div>
    </div>
</div>
<!-- ============================================== -->

<div class="content-area">

    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h2 class="fw-bold">
                    <i class="fas fa-file-alt text-primary-green"></i> Laporan Donasi
                </h2>
                <p class="text-muted">Rekapitulasi donasi yang telah berhasil diverifikasi</p>
            </div>

            <button onclick="window.print()" class="btn btn-primary-custom no-print">
                <i class="fas fa-print"></i> Cetak Laporan
            </button>
        </div>
    </div>

    <!-- Summary Card -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card-custom">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="card-stats-icon bg-success-subtle text-success"
                             style="background: rgba(16,185,129,.1); color:#10b981;">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <div class="ms-3">
                            <small class="text-muted">Total Donasi Berhasil</small>
                            <h3 class="fw-bold text-primary-green mb-0">
                                Rp {{ number_format($totalDonasi,0,',','.') }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-custom">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="card-stats-icon"
                             style="background: linear-gradient(135deg,#3b82f6,#1d4ed8);">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="ms-3">
                            <small class="text-muted">Jumlah Transaksi</small>
                            <h3 class="fw-bold mb-0">{{ $donasisBerhasil->total() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-custom">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="card-stats-icon"
                             style="background: linear-gradient(135deg,#8b5cf6,#6d28d9);">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="ms-3">
                            <small class="text-muted">Rata-rata Donasi</small>
                            <h3 class="fw-bold mb-0">
                                Rp {{ $donasisBerhasil->total() > 0 
                                        ? number_format($totalDonasi / $donasisBerhasil->total(),0,',','.') 
                                        : 0 }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Laporan -->
    <div class="card-custom">
        <div class="card-body">
            <h5 class="fw-bold mb-4">
                <i class="fas fa-table text-primary-green"></i> Daftar Donasi Berhasil
            </h5>

            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="12%">Tanggal</th>
                            <th>Donatur</th>
                            <th>Kebutuhan</th>
                            <th width="15%">Jumlah</th>
                            <th width="12%">Verifikasi</th>
                            <th width="10%" class="no-print">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($donasisBerhasil as $index => $donasi)
                        <tr>
                            <td>{{ $donasisBerhasil->firstItem() + $index }}</td>
                            <td>{{ $donasi->created_at->format('d/m/Y') }}</td>

                            <td>
                                <strong>{{ $donasi->user->name ?? 'Anonim (ID:'.$donasi->user_id.')' }}</strong><br>
                                <small class="text-muted">{{ $donasi->user->email ?? 'Email tidak tersedia' }}</small>
                            </td>

                            <td>
                                {{ $donasi->kebutuhan->nama_kebutuhan ?? 'N/A' }}
                                @if($donasi->kebutuhan && $donasi->kebutuhan->jenis == 'uang')
                                    <span class="badge bg-success ms-1">💰</span>
                                @elseif($donasi->kebutuhan && $donasi->kebutuhan->jenis == 'barang')
                                    <span class="badge bg-info ms-1">📦</span>
                                @endif
                            </td>

                            <td>
                                <strong class="text-primary-green">
                                    Rp {{ number_format($donasi->jumlah_donasi,0,',','.') }}
                                </strong>
                            </td>

                            <td>
                                <small class="text-muted">
                                    {{ $donasi->tanggal_verifikasi->format('d/m/Y H:i') }}
                                </small>
                            </td>

                            <td class="no-print">
                                <a href="{{ route('admin.donasi.show',$donasi) }}"
                                   class="btn btn-sm btn-outline-primary"
                                   data-bs-toggle="tooltip" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <button type="button"
                                        class="btn btn-sm btn-outline-danger btn-delete-donasi ms-1"
                                        data-id="{{ $donasi->id }}"
                                        data-bs-toggle="tooltip"
                                        title="Hapus Permanen">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                Belum ada donasi yang berhasil diverifikasi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                    @if($donasisBerhasil->total() > 0)
                    <tfoot>
                        <tr class="table-active">
                            <td colspan="4" class="text-end"><strong>TOTAL KESELURUHAN:</strong></td>
                            <td colspan="3">
                                <strong class="text-primary-green fs-5">
                                    Rp {{ number_format($totalDonasi,0,',','.') }}
                                </strong>
                            </td>
                        </tr>
                    </tfoot>
                    @endif

                </table>
            </div>

            <!-- Pagination -->
            @if($donasisBerhasil->hasPages())
            <div class="mt-4 no-print">
                {{ $donasisBerhasil->appends(request()->except('page'))->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Hapus -->
<div class="modal fade" id="deleteDonasiLaporanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-trash-alt"></i> Hapus Donasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                Apakah Anda yakin ingin menghapus transaksi donasi ID 
                <strong id="donasiIdLaporanText" class="text-danger"></strong> ini secara permanen?
                <p class="mt-3 text-warning">
                    <small><i class="fas fa-exclamation-triangle"></i> Donasi berstatus 'Berhasil', jumlah terkumpul akan otomatis dikurangi.</small>
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">Batal</button>

                <form id="deleteDonasiLaporanForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus Sekarang</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Print Style -->
<style>
@media print {
    .sidebar, .topbar, .btn, nav, .no-print { display: none !important; }
    .main-content { margin-left: 0 !important; }
    .card-custom { box-shadow: none !important; border: 1px solid #dee2e6 !important; }
    body { background: white !important; }
    .table-custom thead th { background:#f0f0f0 !important; color:#333 !important; }
}
</style>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // Tooltip
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el);
    });

    // Delete Donasi
    document.querySelectorAll('.btn-delete-donasi').forEach(btn => {
        btn.addEventListener('click', function() {

            const id = this.dataset.id;
            const modal = new bootstrap.Modal('#deleteDonasiLaporanModal');

            document.getElementById('donasiIdLaporanText').textContent = '#' + id.padStart(4, '0');

            const route = '{{ route('admin.donasi.destroy', ':id') }}';
            document.getElementById('deleteDonasiLaporanForm').action = route.replace(':id', id);

            modal.show();
        });
    });
});
</script>
@endpush
