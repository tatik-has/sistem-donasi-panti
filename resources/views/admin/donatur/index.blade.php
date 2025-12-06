@extends('layouts.admin')

@section('title', 'Data Donatur')

@section('content')

<div class="content-area">
    <div class="mb-4">
        <h2 class="fw-bold">
            <i class="fas fa-users text-primary-green"></i> Data Donatur
        </h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Donatur</li>
            </ol>
        </nav>
    </div>

    <!-- Main Table -->
    <div class="card-custom">
        <div class="card-body">
            <h5 class="fw-bold mb-4">
                <i class="fas fa-table text-primary-green"></i> Daftar Semua Donatur
            </h5>

            <!-- Search Form -->
            <div class="mb-3">
                <form action="{{ route('admin.donatur.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control form-control-custom w-50" 
                            placeholder="Cari nama atau email donatur..." value="{{ $search ?? '' }}">
                    <button type="submit" class="btn btn-primary-custom ms-2">Cari</button>
                    @if(isset($search))
                        <a href="{{ route('admin.donatur.index') }}" class="btn btn-outline-secondary ms-2">Reset</a>
                    @endif
                </form>
            </div>
            
            <!-- Donatur List Table -->
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="35%">Nama Donatur</th>
                            <th width="35%">Email</th>
                            <th width="20%">Tanggal Bergabung</th>
                            <th width="5%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($donaturs as $index => $donatur)
                            <tr>
                                <td>{{ $donaturs->firstItem() + $index }}</td>
                                <td><strong>{{ $donatur->name }}</strong></td>
                                <td>{{ $donatur->email }}</td>
                                {{-- Kolom No. Telepon telah dihapus sesuai permintaan --}}
                                <td>{{ $donatur->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.donatur.show', $donatur) }}" 
                                        class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="tooltip" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                {{-- Colspan disesuaikan dari 6 menjadi 5 --}}
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="fas fa-user-slash fa-4x mb-3 d-block"></i>
                                    Tidak ada data donatur ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(isset($donaturs) && $donaturs->hasPages())
            <div class="mt-4">
                {{ $donaturs->links() }}
            </div>
            @endif
        </div>
    </div>
</div>


@endsection