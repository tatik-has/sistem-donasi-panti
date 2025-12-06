@extends('layouts.app')

@section('content')
<div class="container my-5">
    {{-- Breadcrumb --}}
    <div class="mb-4">
        <h1 class="fw-bold text-primary-green">
            <i class="fas fa-receipt"></i> Detail Donasi
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('donatur.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('donatur.riwayat') }}">Riwayat Donasi</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        {{-- Kolom Kiri: Detail Donasi --}}
        <div class="col-lg-8 mb-4">
            <div class="card-custom">
                <div class="card-body">
                    <h5 class="fw-bold mb-4 border-bottom pb-2">
                        <i class="fas fa-info-circle text-primary-green"></i> Status Donasi
                    </h5>
                    
                    <div class="mb-3">
                        <strong>ID Donasi:</strong> #{{ str_pad($donasi->id, 6, '0', STR_PAD_LEFT) }}
                    </div>

                    {{-- Status Alert --}}
                    @if($donasi->status === 'berhasil')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> <strong>Terima Kasih!</strong> Donasi Anda telah berhasil diverifikasi pada {{ $donasi->tanggal_verifikasi ? \Carbon\Carbon::parse($donasi->tanggal_verifikasi)->format('d F Y, H:i') : '-' }} WIB.
                        </div>
                    @elseif($donasi->status === 'menunggu')
                        <div class="alert alert-warning">
                            <i class="fas fa-clock"></i> <strong>Menunggu Verifikasi</strong> Admin sedang memproses donasi Anda.
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle"></i> <strong>Ditolak</strong> Donasi Anda tidak dapat diverifikasi.
                        </div>
                    @endif

                    <hr>

                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-donate text-primary-green"></i> Informasi Donasi
                    </h6>

                    <table class="table table-borderless table-sm">
                        <tr>
                            <th width="35%">Kebutuhan</th>
                            <td>
                                <strong>{{ $donasi->kebutuhan->nama_kebutuhan ?? 'Kebutuhan Dihapus' }}</strong>
                                <span class="badge bg-info ms-2">
                                    {{ $donasi->kebutuhan->jenis == 'uang' ? '💰 Uang' : '📦 Barang' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Jumlah Donasi</th>
                            <td><strong class="text-success">Rp {{ number_format($donasi->jumlah_donasi, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr>
                            <th>Tanggal Donasi</th>
                            <td>{{ $donasi->created_at->format('d F Y, H:i') }} WIB</td>
                        </tr>
                        <tr>
                            <th>Tanggal Verifikasi</th>
                            <td>{{ $donasi->tanggal_verifikasi ? \Carbon\Carbon::parse($donasi->tanggal_verifikasi)->format('d F Y, H:i') : '-' }} WIB</td>
                        </tr>
                    </table>

                    <hr>

                    <div class="mb-3">
                        <strong>Pesan Anda:</strong>
                        <blockquote class="blockquote-custom mt-2">
                            {{ $donasi->pesan ?? '(Tidak ada pesan)' }}
                        </blockquote>
                    </div>

                    @if($donasi->keterangan_admin)
                    <div class="alert alert-info">
                        <strong>Keterangan dari Admin:</strong><br>
                        <i class="fas fa-user-shield"></i> {{ $donasi->keterangan_admin }}
                    </div>
                    @endif

                    <hr>

                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-image text-primary-green"></i> Bukti Transfer
                    </h6>

                    @if($donasi->bukti_transfer)
                        <img src="{{ asset('storage/' . $donasi->bukti_transfer) }}" 
                             alt="Bukti Transfer" 
                             class="img-fluid rounded shadow-sm" 
                             style="max-height: 400px; object-fit: contain;">
                    @else
                        <p class="text-muted">Tidak ada bukti transfer.</p>
                    @endif

                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Info Kebutuhan & Kontak Admin --}}
        <div class="col-lg-4">
            {{-- Card: Tentang Kebutuhan --}}
            <div class="card-custom mb-4">
                <div class="card-body">
                    <h6 class="fw-bold text-primary-green mb-3">
                        <i class="fas fa-bullseye"></i> Tentang Kebutuhan
                    </h6>
                    
                    <div class="mb-2">
                        <small class="text-muted">{{ $donasi->kebutuhan->jenis == 'uang' ? 'Target:' : 'Jumlah Dibutuhkan:' }}</small><br>
                        <strong class="text-dark">Rp {{ number_format($donasi->kebutuhan->target_jumlah, 0, ',', '.') }}</strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Terkumpul:</small><br>
                        <strong class="text-success">Rp {{ number_format($donasi->kebutuhan->jumlah_terkumpul, 0, ',', '.') }}</strong>
                    </div>

                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-success" 
                             role="progressbar" 
                             style="width: {{ $donasi->kebutuhan->persentase }}%">
                            {{ number_format($donasi->kebutuhan->persentase, 1) }}% tercapai
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card: Butuh Bantuan? --}}
            <div class="card-custom">
                <div class="card-body">
                    <h6 class="fw-bold text-primary-green mb-3">
                        <i class="fas fa-question-circle"></i> Butuh Bantuan?
                    </h6>
                    
                    <p class="text-muted small">
                        Jika ada pertanyaan tentang donasi Anda, silakan hubungi admin melalui:
                    </p>

                    @php
                        // Ambil data email dan WhatsApp dari Setting
                        $emailKontak = \App\Models\Setting::getValue('email_kontak', 'kontak@panti.org');
                        $whatsappNumber = \App\Models\Setting::getValue('whatsapp_number', '6281234567890');
                    @endphp

                    <a href="mailto:{{ $emailKontak }}" 
                       class="btn btn-outline-primary btn-sm w-100 mb-2">
                        <i class="fas fa-envelope"></i> Email Admin ({{ $emailKontak }})
                    </a>

                    <a href="https://wa.me/{{ $whatsappNumber }}?text=Halo%20Admin,%20saya%20ingin%20bertanya%20tentang%20donasi%20ID%20%23{{ $donasi->id }}" 
                       target="_blank"
                       class="btn btn-success btn-sm w-100">
                        <i class="fab fa-whatsapp"></i> WhatsApp Admin
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Tombol Kembali --}}
    <div class="text-center mt-4">
        <a href="{{ route('donatur.riwayat') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
        </a>
    </div>
</div>
@endsection

@push('styles')
<style>
    .blockquote-custom {
        background-color: #f8f9fa;
        border-left: 4px solid #10b981;
        padding: 15px 20px;
        font-style: italic;
        color: #555;
    }
</style>
@endpush