@extends('layouts.app') 

@section('content')
<div class="container my-5">
    <div class="mb-5 text-center">
        <h1 class="fw-bold text-primary-green">
            <i class="fas fa-history"></i> Riwayat Donasi Anda
        </h1>
        <p class="text-muted lead">Daftar semua donasi yang telah Anda kirim, termasuk yang masih menunggu verifikasi.</p>
    </div>

    {{-- Pesan Status (Success/Error) --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-times-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card-custom">
        <div class="card-body">
            <h5 class="fw-bold mb-4">
                <i class="fas fa-list-alt text-primary-green"></i> Daftar Transaksi
            </h5>

            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="20%">Kebutuhan</th>
                            <th width="15%">Tanggal Donasi</th>
                            <th width="15%">Jumlah</th>
                            <th width="15%">Status</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- LOOPING DATA DONASI DARI CONTROLLER --}}
                        @forelse($donasis as $donasi)
                            <tr>
                                <td>#{{ str_pad($donasi->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    <strong>{{ $donasi->kebutuhan->nama_kebutuhan ?? 'Kebutuhan Dihapus' }}</strong><br>
                                    <small class="text-muted">{{ $donasi->kebutuhan->jenis == 'uang' ? 'Uang' : 'Barang' }}</small>
                                </td>
                                <td>{{ $donasi->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    Rp {{ number_format($donasi->jumlah_donasi, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if($donasi->status === 'menunggu')
                                        <span class="badge badge-warning-custom" style="background-color: #f59e0b; color: white;">Menunggu</span>
                                    @elseif($donasi->status === 'berhasil')
                                        <span class="badge badge-success-custom" style="background-color: #10b981; color: white;">Berhasil</span>
                                    @else
                                        <span class="badge badge-danger-custom" style="background-color: #ef4444; color: white;">Ditolak</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('donatur.riwayat.show', $donasi) }}" 
                                        class="btn btn-sm btn-outline-primary"
                                        title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="fas fa-inbox fa-4x mb-3 d-block"></i>
                                    Anda belum memiliki riwayat donasi. Mulai berdonasi sekarang!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if(isset($donasis) && $donasis->hasPages())
                <div class="mt-4">
                    {{ $donasis->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Tambahkan tombol kembali ke halaman donasi --}}
    <div class="text-center mt-5">
        <a href="{{ route('donatur.donasi.index') }}" class="btn btn-primary-custom">
            <i class="fas fa-plus"></i> Beri Donasi Lain
        </a>
    </div>

</div>
@endsection