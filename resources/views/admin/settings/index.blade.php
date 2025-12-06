@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')

{{-- Link CSS Eksternal --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin_settings.css') }}">
@endpush

@section('content')
   
<div class="content-area">
    <!-- Header Section with Better Spacing -->
    <div class="page-header">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
            <div>
                <h2 class="page-title mb-2">
                    <i class="fas fa-cog text-primary-green"></i> Pengaturan Aplikasi
                </h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Pengaturan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    {{-- Pesan Status --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2 fs-5"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    {{-- Pesan Error Validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
            <div class="d-flex align-items-start">
                <i class="fas fa-exclamation-circle me-2 fs-5"></i>
                <div>
                    <strong>Gagal menyimpan pengaturan!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Main Settings Card -->
    <div class="row">
        <div class="col-lg-10 col-xl-9 mx-auto">
            <div class="settings-card">
                <div class="settings-card-header">
                    <h5 class="settings-card-title mb-0">
                        <i class="fas fa-sliders-h me-2"></i> Konfigurasi Dasar
                    </h5>
                    <p class="settings-card-subtitle mb-0">Atur informasi dasar aplikasi dan kontak organisasi</p>
                </div>

                <div class="settings-card-body">
                    <form method="POST" action="{{ route('admin.settings.update') }}">
                        @csrf
                        @method('PUT')
                        
                        {{-- Input Nama Panti --}}
                        <div class="form-group-custom">
                            <label for="nama_panti" class="form-label-custom">
                                <i class="fas fa-home text-muted me-2"></i>
                                Nama Organisasi/Panti <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                id="nama_panti"
                                name="nama_panti" 
                                class="form-control-custom @error('nama_panti') is-invalid @enderror" 
                                value="{{ old('nama_panti', $settings['nama_panti'] ?? '') }}" 
                                placeholder="Masukkan nama organisasi atau panti"
                                required>
                            @error('nama_panti')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Input Rekening Bank --}}
                        <div class="form-group-custom">
                            <label for="rekening_bank" class="form-label-custom">
                                <i class="fas fa-university text-muted me-2"></i>
                                Informasi Rekening Bank <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                id="rekening_bank"
                                name="rekening_bank" 
                                class="form-control-custom @error('rekening_bank') is-invalid @enderror" 
                                value="{{ old('rekening_bank', $settings['rekening_bank'] ?? '') }}" 
                                placeholder="Contoh: Bank Mandiri 1234567890 a.n. YPHB"
                                required>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Rekening ini akan ditampilkan sebagai tujuan donasi
                            </small>
                            @error('rekening_bank')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Input Email Kontak --}}
                        <div class="form-group-custom">
                            <label for="email_kontak" class="form-label-custom">
                                <i class="fas fa-envelope text-muted me-2"></i>
                                Email Kontak <span class="text-danger">*</span>
                            </label>
                            <input type="email" 
                                id="email_kontak"
                                name="email_kontak" 
                                class="form-control-custom @error('email_kontak') is-invalid @enderror" 
                                value="{{ old('email_kontak', $settings['email_kontak'] ?? '') }}" 
                                placeholder="contoh@email.com"
                                required>
                            @error('email_kontak')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Input WhatsApp Number --}}
                        <div class="form-group-custom">
                            <label for="whatsapp_number" class="form-label-custom">
                                <i class="fab fa-whatsapp text-muted me-2"></i>
                                Nomor WhatsApp Admin <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                id="whatsapp_number"
                                name="whatsapp_number" 
                                class="form-control-custom @error('whatsapp_number') is-invalid @enderror" 
                                value="{{ old('whatsapp_number', $settings['whatsapp_number'] ?? '') }}" 
                                placeholder="6281234567890"
                                required>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Format: 62xxxxxxxxxx (tanpa tanda + atau 0 di awal)
                            </small>
                            @error('whatsapp_number')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Input Footer Text --}}
                        <div class="form-group-custom mb-0">
                            <label for="footer_text" class="form-label-custom">
                                <i class="fas fa-text-height text-muted me-2"></i>
                                Teks Footer (Opsional)
                            </label>
                            <textarea 
                                id="footer_text"
                                name="footer_text" 
                                class="form-control-custom @error('footer_text') is-invalid @enderror" 
                                rows="3"
                                placeholder="Masukkan teks yang akan ditampilkan di bagian bawah website">{{ old('footer_text', $settings['footer_text'] ?? '') }}</textarea>
                            @error('footer_text')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="settings-card-footer">
                            <button type="submit" class="btn btn-primary-custom">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary-custom">
                                <i class="fas fa-times me-2"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Card -->
            <div class="info-card mt-4">
                <i class="fas fa-lightbulb text-warning me-2"></i>
                <span><strong>Tips:</strong> Pastikan semua informasi yang dimasukkan sudah benar sebelum menyimpan. Informasi ini akan ditampilkan di halaman publik website.</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap tooltips
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(el => new bootstrap.Tooltip(el));

        // Auto-hide alerts after 5 seconds
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });

        // Format WhatsApp number input (remove non-numeric)
        const waInput = document.getElementById('whatsapp_number');
        if (waInput) {
            waInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }

        // Form validation feedback
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...';
            });
        }
    });
</script>
@endpush