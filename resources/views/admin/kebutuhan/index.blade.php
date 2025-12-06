@extends('layouts.admin')

@section('title', 'Manajemen Kebutuhan')

@section('content')
   
    <!-- Konten Utama Halaman -->
    <div class="content-area">
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold">
                        <i class="fas fa-box-open text-primary-green"></i> Manajemen Kebutuhan
                    </h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Kebutuhan</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('admin.kebutuhan.create') }}" class="btn btn-primary-custom">
                        <i class="fas fa-plus"></i> Tambah Kebutuhan Baru
                    </a>
                </div>
            </div>
        </div>

        <!-- Summary Cards (Jika ada, disesuaikan dengan data kebutuhan) -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card-custom border-start border-success border-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <!-- Contoh CSS inline yang mungkin perlu Anda definisikan di style.css -->
                            <div class="card-stats-icon bg-success-subtle text-success" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="ms-3">
                                <small class="text-muted">Kebutuhan Aktif (Dapat didonasi)</small>
                                <h3 class="fw-bold mb-0 text-success" style="color: #10b981 !important;">{{ number_format($totalAktif ?? 0, 0, '.', ',') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card-custom border-start border-secondary border-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                             <!-- Contoh CSS inline yang mungkin perlu Anda definisikan di style.css -->
                            <div class="card-stats-icon bg-secondary-subtle text-secondary" style="background: rgba(107, 114, 128, 0.1); color: #6b7280;">
                                <i class="fas fa-minus-circle"></i>
                            </div>
                            <div class="ms-3">
                                <small class="text-muted">Kebutuhan Non-Aktif (Selesai/Ditunda)</small>
                                <h3 class="fw-bold mb-0 text-secondary">{{ number_format($totalNonAktif ?? 0, 0, '.', ',') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Table -->
        <div class="card-custom">
            <div class="card-body">
                <h5 class="fw-bold mb-4">
                    <i class="fas fa-table text-primary-green"></i> Daftar Kebutuhan Tersedia
                </h5>

                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="30%">Nama Kebutuhan</th>
                                <th width="15%">Jenis</th>
                                <th width="20%">Target & Terkumpul</th>
                                <th width="10%">Status</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Asumsi menggunakan variabel $kebutuhans yang sudah dipaginasi dari controller -->
                            @forelse($kebutuhans as $index => $kebutuhan)
                                <tr>
                                    <td>{{ $kebutuhans->firstItem() + $index }}</td>
                                    <td>
                                        <strong>{{ $kebutuhan->nama_kebutuhan }}</strong><br>
                                        <small class="text-muted">{{ Str::limit($kebutuhan->deskripsi, 50) }}</small>
                                    </td>
                                    <td>
                                        @if($kebutuhan->jenis == 'uang')
                                            <!-- Menggunakan kelas badge kustom dari CSS tambahan -->
                                            <span class="badge badge-jenis uang">💰 Uang</span>
                                        @else
                                            <span class="badge badge-jenis barang" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white;">📦 Barang</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($kebutuhan->jumlah_target)
                                            <!-- Terkumpul -->
                                            <small class="text-muted">Terkumpul:</small>
                                            <strong class="text-primary-green d-block">
                                                {{ $kebutuhan->jenis == 'uang' ? 'Rp ' . number_format($kebutuhan->jumlah_terkumpul, 0, ',', '.') : $kebutuhan->jumlah_terkumpul . ' ' . $kebutuhan->satuan }}
                                            </strong>
                                            <!-- Target -->
                                            <small class="text-muted">dari </small>
                                            <small>
                                                {{ $kebutuhan->jenis == 'uang' ? 'Rp ' . number_format($kebutuhan->jumlah_target, 0, ',', '.') : $kebutuhan->jumlah_target . ' ' . $kebutuhan->satuan }}
                                            </small>
                                            <div class="progress mt-1" style="height: 5px;">
                                                <div class="progress-bar bg-primary-green" 
                                                    role="progressbar" 
                                                    style="width: {{ $kebutuhan->persentase ?? 0 }}%; background: linear-gradient(90deg, var(--primary-green) 0%, var(--dark-green) 100%) !important;" 
                                                    aria-valuenow="{{ $kebutuhan->persentase ?? 0 }}" 
                                                    aria-valuemin="0" 
                                                    aria-valuemax="100">
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">Tanpa Target</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($kebutuhan->status === 'aktif')
                                            <span class="badge badge-success-custom">Aktif</span>
                                        @else
                                            <span class="badge badge-danger-custom">Non-aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('admin.kebutuhan.edit', $kebutuhan) }}" 
                                                class="btn btn-sm btn-outline-warning"
                                                data-bs-toggle="tooltip" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <!-- Tombol Delete menggunakan form -->
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete-kebutuhan" 
                                                    data-id="{{ $kebutuhan->id }}"
                                                    data-nama="{{ $kebutuhan->nama_kebutuhan }}"
                                                    data-bs-toggle="tooltip" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i class="fas fa-box-open fa-4x mb-3 d-block"></i>
                                        Belum ada kebutuhan yang terdaftar. Silakan tambahkan satu!
                                        <div class="mt-3">
                                            <a href="{{ route('admin.kebutuhan.create') }}" class="btn btn-primary-custom">
                                                <i class="fas fa-plus"></i> Tambah Kebutuhan
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(isset($kebutuhans) && $kebutuhans->hasPages())
                <div class="mt-4">
                    {{ $kebutuhans->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteKebutuhanModal" tabindex="-1" aria-labelledby="deleteKebutuhanModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteKebutuhanModalLabel"><i class="fas fa-trash-alt"></i> Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus kebutuhan: <br>
                    <strong id="kebutuhanNama" class="text-danger"></strong>?
                    <p class="mt-3 text-warning"><small><i class="fas fa-exclamation-triangle"></i> Menghapus kebutuhan akan menghapus data kebutuhan ini secara permanen, namun tidak akan memengaruhi data donasi yang sudah tercatat.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteKebutuhanForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus Permanen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi Tooltip Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Event listener untuk tombol hapus
        const deleteButtons = document.querySelectorAll('.btn-delete-kebutuhan');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const kebutuhanId = this.getAttribute('data-id');
                const kebutuhanNama = this.getAttribute('data-nama');
                const modal = new bootstrap.Modal(document.getElementById('deleteKebutuhanModal'));
                
                // Isi nama kebutuhan di modal
                document.getElementById('kebutuhanNama').textContent = kebutuhanNama;
                
                // Set action form delete
                const deleteForm = document.getElementById('deleteKebutuhanForm');
                const deleteRoute = '{{ route('admin.kebutuhan.destroy', ['kebutuhan' => ':id']) }}'; 
                deleteForm.action = deleteRoute.replace(':id', kebutuhanId);
                
                modal.show();
            });
        });
        
        // Memastikan fungsi toggleSidebar tersedia jika Topbar ada di konten view ini.
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }
    });
</script>
@endpush