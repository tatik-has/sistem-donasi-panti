@extends('layouts.app')

@section('title', 'Dashboard Donatur')

{{-- Link CSS Eksternal --}}
<link rel="stylesheet" href="{{ asset('css/donatur_dashboard.css') }}">

@section('content')
<div class="donatur-dashboard">
    <div class="container-fluid px-4 py-4">
        
        <div class="welcome-section">
            <div class="welcome-content">
                <h1 class="dashboard-title">Dashboard</h1>
                <p class="welcome-text">Selamat datang kembali, <span class="user-name">{{ Auth::user()->name ?? 'Donatur' }}</span></p>
            </div>
            <div class="date-info">
                <span class="date-label">Hari Ini</span>
                <span class="date-value">{{ now()->format('d M Y') }}</span>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card stat-success">
                <div class="stat-icon-wrapper">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="stat-content">
                    <p class="stat-label">Donasi Berhasil</p>
                    <h3 class="stat-value">{{ number_format($donasiBerhasilCount ?? 0) }}</h3>
                    <span class="stat-subtitle">Transaksi selesai</span>
                </div>
            </div>

            <div class="stat-card stat-warning">
                <div class="stat-icon-wrapper">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="stat-content">
                    <p class="stat-label">Menunggu Verifikasi</p>
                    <h3 class="stat-value">{{ number_format($donasiMenungguCount ?? 0) }}</h3>
                    <span class="stat-subtitle">Menanti konfirmasi</span>
                </div>
            </div>

            <div class="stat-card stat-primary">
                <div class="stat-icon-wrapper">
                    <div class="stat-icon">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                </div>
                <div class="stat-content">
                    <p class="stat-label">Total Kontribusi</p>
                    <h3 class="stat-value">Rp {{ number_format($totalDonasiUang ?? 0, 0, ',', '.') }}</h3>
                    <span class="stat-subtitle">Dampak Anda sejauh ini</span>
                </div>
            </div>
        </div>

        <div class="content-grid">
            
            <div class="content-card recent-donations">
                <div class="card-header-custom">
                    <div class="header-left">
                        <h2 class="card-title">Aktivitas Terbaru</h2>
                        <p class="card-subtitle">Catatan donasi terbaru Anda</p>
                    </div>
                    <a href="{{ route('donatur.riwayat.index') }}" class="btn-link">
                        Lihat Semua <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <div class="table-container">
                    <table class="donations-table">
                        <thead>
                            <tr>
                                <th>Kebutuhan</th>
                                <th>Jumlah</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayatTerbaru ?? [] as $donasi)
                                <tr>
                                    <td>
                                        <div class="need-info">
                                            <span class="need-name">{{ $donasi->kebutuhan->nama_kebutuhan ?? 'Kebutuhan Dihapus' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="amount-value">
                                            @if(isset($donasi->kebutuhan) && $donasi->kebutuhan->jenis == 'uang')
                                                Rp {{ number_format($donasi->jumlah_donasi, 0, ',', '.') }}
                                            @elseif(isset($donasi->kebutuhan))
                                                {{ number_format($donasi->jumlah_donasi, 0, ',', '.') }} {{ $donasi->kebutuhan->satuan ?? 'unit' }}
                                            @else
                                                Rp {{ number_format($donasi->jumlah_donasi, 0, ',', '.') }}
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <span class="date-text">{{ $donasi->created_at->format('d M Y') }}</span>
                                    </td>
                                    <td>
                                        @if($donasi->status === 'menunggu')
                                            <span class="status-badge status-pending">Menunggu</span>
                                        @elseif($donasi->status === 'berhasil')
                                            <span class="status-badge status-success">Selesai</span>
                                        @else
                                            <span class="status-badge status-rejected">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('donatur.riwayat.show', $donasi) }}" class="btn-icon" title="Lihat Detail">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="empty-state">
                                        <div class="empty-content">
                                            <i class="fas fa-inbox"></i>
                                            <p>Belum ada donasi terbaru</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="content-card quick-actions">
                <div class="card-header-custom">
                    <h2 class="card-title">Aksi Cepat</h2>
                    <p class="card-subtitle">Navigasi ke fitur utama</p>
                </div>

                <div class="actions-list">
                    <a href="{{ route('donatur.donasi.index') }}" class="action-item action-primary">
                        <div class="action-icon">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <div class="action-content">
                            <h4 class="action-title">Lakukan Donasi</h4>
                            <p class="action-description">Mulai berkontribusi sekarang</p>
                        </div>
                        <i class="fas fa-arrow-right action-arrow"></i>
                    </a>

                    <a href="{{ route('donatur.riwayat.index') }}" class="action-item action-secondary">
                        <div class="action-icon">
                            <i class="fas fa-history"></i>
                        </div>
                        <div class="action-content">
                            <h4 class="action-title">Riwayat Donasi</h4>
                            <p class="action-description">Lihat semua catatan</p>
                        </div>
                        <i class="fas fa-arrow-right action-arrow"></i>
                    </a>

                    <a href="mailto:admin@panti.com" class="action-item action-secondary">
                        <div class="action-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="action-content">
                            <h4 class="action-title">Hubungi Dukungan</h4>
                            <p class="action-description">Kami siap membantu</p>
                        </div>
                        <i class="fas fa-arrow-right action-arrow"></i>
                    </a>
                </div>

                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <p>Donasi Anda membawa perubahan nyata. Terima kasih atas dukungan Anda!</p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scroll for internal links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });

        // Add animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.stat-card, .content-card').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            observer.observe(el);
        });
    });
</script>
@endpush