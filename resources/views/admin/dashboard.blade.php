@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="content-area">
        <div class="mb-4">
            <h2 class="fw-bold">
                <i class="fas fa-tachometer-alt" style="color: var(--primary-green);"></i> Dashboard Admin
            </h2>
            <p class="text-muted">Selamat datang di panel admin sistem donasi panti</p>
        </div>

        <!-- Stats Cards (DIPERBAIKI UNTUK DATA DINAMIS) -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card-stats">
                    <div class="card-body d-flex align-items-center">
                        <div class="card-stats-icon">
                            <i class="fas fa-list-ul"></i>
                        </div>
                        <div class="ms-3">
                            <h3>{{ number_format($totalKebutuhan) }}</h3>
                            <p>Total Kebutuhan</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-stats">
                    <div class="card-body d-flex align-items-center">
                        <div class="card-stats-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="ms-3">
                            <h3>{{ number_format($totalDonatur) }}</h3>
                            <p>Total Donatur</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-stats">
                    <div class="card-body d-flex align-items-center">
                        <div class="card-stats-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <div class="ms-3">
                            <h3>Rp {{ number_format($totalDonasiBerhasil, 0, ',', '.') }}</h3>
                            <p>Total Donasi Berhasil</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-stats">
                    <div class="card-body d-flex align-items-center">
                        <div class="card-stats-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="ms-3">
                            <h3>{{ number_format($donasiMenunggu) }}</h3>
                            <p>Menunggu Verifikasi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Donasi Terbaru (DIPERBAIKI DENGAN DATA DINAMIS) -->
        <div class="card-custom">
            <div class="card-body">
                <h5 class="fw-bold mb-4">
                    <i class="fas fa-history" style="color: var(--primary-green);"></i> Donasi Terbaru
                </h5>

                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>Donatur</th>
                                <th>Kebutuhan</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($donasiTerbaru as $donasi)
                                <tr>
                                    <td>{{ $donasi->user->name ?? 'Anonim' }}</td>
                                    <td>{{ $donasi->kebutuhan->nama_kebutuhan ?? 'N/A' }}</td>
                                    <td>Rp {{ number_format($donasi->jumlah_donasi, 0, ',', '.') }}</td>
                                    <td>
                                        @if($donasi->status === 'menunggu')
                                            <span class="badge badge-warning-custom">Menunggu</span>
                                        @elseif($donasi->status === 'berhasil')
                                            <span class="badge badge-success-custom">Berhasil</span>
                                        @else
                                            <span class="badge badge-danger-custom">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>{{ $donasi->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        Belum ada donasi terbaru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 text-end">
                    <a href="{{ route('admin.donasi.index') }}" class="btn btn-sm btn-outline-secondary">
                        Lihat Semua Donasi
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }
    </script>
@endpush
