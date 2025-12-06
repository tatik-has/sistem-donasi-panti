@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/donasi.css') }}">

<div class="container my-5">
    <!-- Header Section -->
    <div class="donasi-header">
        <h1>
            <i class="fas fa-hand-holding-heart"></i>
            Salurkan Donasi Terbaik Anda
        </h1>
        <p>Pilih kebutuhan yang ingin Anda bantu dari daftar di bawah</p>
    </div>

    <!-- Total Donasi Card -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="total-donasi-card">
                <div class="total-donasi-content">
                    <div class="total-donasi-info">
                        <span class="total-donasi-label">Total Donasi Uang Terkumpul Saat Ini</span>
                        <h3 class="total-donasi-amount">
                            Rp {{ number_format($totalDanaTerkumpul ?? 0, 0, ',', '.') }}
                        </h3>
                    </div>
                    <i class="fas fa-coins total-donasi-icon"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filter Section -->
    <div class="row">
        <div class="col-12">
            <form action="{{ route('donatur.donasi.index') }}" method="GET" class="filter-section">
                <select name="jenis" class="filter-select">
                    <option value="">Semua Jenis Kebutuhan</option>
                    <option value="uang" {{ request('jenis') == 'uang' ? 'selected' : '' }}>💰 Donasi Uang</option>
                    <option value="barang" {{ request('jenis') == 'barang' ? 'selected' : '' }}>📦 Donasi Barang</option>
                </select>
                <button type="submit" class="filter-button">
                    <i class="fas fa-filter"></i>
                    Filter
                </button>
            </form>
        </div>
    </div>

    <!-- Cards Grid -->
    <div class="row">
        @forelse($kebutuhans as $kebutuhan)
            <div class="col-md-4 mb-4">
                <div class="kebutuhan-card">
                    <div class="kebutuhan-card-body">
                        <h5 class="kebutuhan-title" title="{{ $kebutuhan->nama_kebutuhan }}">
                            {{ $kebutuhan->nama_kebutuhan }}
                        </h5>
                        
                        <span class="kebutuhan-badge badge-{{ $kebutuhan->jenis }}">
                            {{ $kebutuhan->jenis == 'uang' ? '💰 Donasi Uang' : '📦 Donasi Barang' }}
                        </span>
                        
                        <p class="kebutuhan-description">
                            {{ Str::limit($kebutuhan->deskripsi, 100) }}
                        </p>

                        @if($kebutuhan->jumlah_target && $kebutuhan->jenis == 'uang')
                            <div class="progress-section">
                                <div class="progress-info">
                                    <span class="progress-label">Terkumpul:</span>
                                    <strong class="progress-value">
                                        Rp {{ number_format($kebutuhan->jumlah_terkumpul, 0, ',', '.') }}
                                    </strong>
                                </div>
                                
                                @php
                                    $target = $kebutuhan->jumlah_target;
                                    $terkumpul = $kebutuhan->jumlah_terkumpul;
                                    $persentase = ($target > 0) ? round(($terkumpul / $target) * 100, 1) : 0;
                                @endphp
                                
                                <div class="progress-bar-container">
                                    <div class="progress-bar-fill" 
                                        style="width: {{ min($persentase, 100) }}%;"
                                        role="progressbar" 
                                        aria-valuenow="{{ $persentase }}" 
                                        aria-valuemin="0" 
                                        aria-valuemax="100">
                                    </div>
                                </div>
                                
                                <div class="progress-details">
                                    <span>{{ number_format($persentase, 1) }}% Tercapai</span>
                                    <span>Target: Rp {{ number_format($kebutuhan->jumlah_target, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @elseif($kebutuhan->jumlah_target && $kebutuhan->jenis == 'barang')
                            <div class="progress-section">
                                <div class="progress-info">
                                    <span class="progress-label">Terkumpul:</span>
                                    <strong class="progress-value">
                                        {{ $kebutuhan->jumlah_terkumpul }} {{ $kebutuhan->satuan }}
                                    </strong>
                                </div>
                                
                                @php
                                    $target = $kebutuhan->jumlah_target;
                                    $terkumpul = $kebutuhan->jumlah_terkumpul;
                                    $persentase = ($target > 0) ? round(($terkumpul / $target) * 100, 1) : 0;
                                @endphp
                                
                                <div class="progress-bar-container">
                                    <div class="progress-bar-fill" 
                                        style="width: {{ min($persentase, 100) }}%;"
                                        role="progressbar" 
                                        aria-valuenow="{{ $persentase }}" 
                                        aria-valuemin="0" 
                                        aria-valuemax="100">
                                    </div>
                                </div>
                                
                                <div class="progress-details">
                                    <span>{{ number_format($persentase, 1) }}% Tercapai</span>
                                    <span>Target: {{ $kebutuhan->jumlah_target }} {{ $kebutuhan->satuan }}</span>
                                </div>
                            </div>
                        @else
                            <div class="no-target-info">
                                <small>Donasi dibuka tanpa target spesifik</small>
                            </div>
                        @endif
                        
                        <a href="{{ route('donatur.donasi.create', $kebutuhan->id) }}" class="donate-button">
                            <i class="fas fa-donate"></i>
                            Beri Donasi
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="empty-state">
                    <i class="fas fa-inbox empty-state-icon"></i>
                    <h4>Belum Ada Kebutuhan Aktif</h4>
                    <p>Saat ini belum ada kebutuhan yang tersedia. Silakan cek kembali nanti.</p>
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if(isset($kebutuhans) && $kebutuhans->hasPages())
    <div class="mt-4">
        {{ $kebutuhans->links() }}
    </div>
    @endif
    
</div>
@endsection