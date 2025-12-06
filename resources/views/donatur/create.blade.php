@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/donasi-create.css') }}">

<div class="container my-5">
    <!-- Alert Errors -->
    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <strong>Terjadi Kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mb-4">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <!-- Form Donasi -->
        <div class="col-lg-7">
            <div class="form-donasi-card">
                <h4 class="form-title">
                    <i class="fas fa-hand-holding-heart"></i>
                    Form Donasi
                </h4>

                <form method="POST" action="{{ route('donatur.donasi.store') }}" enctype="multipart/form-data" id="donasiForm">
                    @csrf
                    <input type="hidden" name="kebutuhan_id" value="{{ $kebutuhan->id }}">

                    <!-- Kebutuhan yang Dipilih -->
                    <div class="form-group-custom">
                        <label class="form-label-custom">Kebutuhan yang Dipilih</label>
                        <div class="kebutuhan-alert">
                            <span class="kebutuhan-name">{{ $kebutuhan->nama_kebutuhan }}</span>
                            <span class="kebutuhan-badge {{ $kebutuhan->jenis }}">
                                {{ $kebutuhan->jenis == 'uang' ? '💰 Uang' : '📦 Barang' }}
                            </span>
                        </div>
                    </div>

                    @if($kebutuhan->jenis == 'uang')
                        {{-- Form untuk Donasi Uang --}}
                        <div class="form-group-custom">
                            <label class="form-label-custom">
                                Jumlah Donasi (Rp) <span class="required">*</span>
                            </label>
                            <div class="input-group-custom">
                                <span class="input-group-text">Rp</span>
                                <input type="text" 
                                       name="jumlah_donasi" 
                                       id="jumlah_donasi"
                                       class="form-control-custom @error('jumlah_donasi') is-invalid @enderror" 
                                       value="{{ old('jumlah_donasi') }}"
                                       placeholder="50.000"
                                       required
                                       oninput="formatRupiah(this)">
                            </div>
                            @error('jumlah_donasi')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            <small class="form-text-muted">
                                <i class="fas fa-info-circle"></i> Minimal donasi Rp 1.000
                            </small>
                        </div>
                    @else
                        {{-- Form untuk Donasi Barang --}}
                        <div class="form-group-custom">
                            <label class="form-label-custom">
                                Jumlah Donasi <span class="required">*</span>
                            </label>
                            <div class="input-group-custom">
                                <input type="number" 
                                       name="jumlah_donasi" 
                                       class="form-control-custom @error('jumlah_donasi') is-invalid @enderror" 
                                       value="{{ old('jumlah_donasi') }}"
                                       placeholder="Masukkan jumlah"
                                       min="1"
                                       step="1"
                                       required>
                                <span class="input-group-text">
                                    {{ $kebutuhan->satuan ?? 'pcs' }}
                                </span>
                            </div>
                            @error('jumlah_donasi')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            <small class="form-text-muted">
                                <i class="fas fa-info-circle"></i> Satuan: {{ $kebutuhan->satuan ?? 'pcs' }}
                            </small>
                        </div>

                        {{-- Nilai Estimasi untuk Barang --}}
                        <div class="form-group-custom">
                            <label class="form-label-custom">
                                Nilai Estimasi (Rp) <span class="text-muted">(Opsional)</span>
                            </label>
                            <div class="input-group-custom">
                                <span class="input-group-text">Rp</span>
                                <input type="text" 
                                       name="nilai_barang" 
                                       id="nilai_barang"
                                       class="form-control-custom" 
                                       placeholder="Estimasi nilai barang"
                                       oninput="formatRupiah(this)">
                            </div>
                            <small class="form-text-muted">
                                <i class="fas fa-info-circle"></i> Isi jika ingin mencatat nilai pembelian barang
                            </small>
                        </div>
                    @endif

                    <!-- Pesan -->
                    <div class="form-group-custom">
                        <label class="form-label-custom">Pesan (Opsional)</label>
                        <textarea name="pesan" 
                                  class="form-control-custom @error('pesan') is-invalid @enderror" 
                                  rows="4"
                                  placeholder="Tuliskan pesan atau doa Anda (maksimal 500 karakter)"
                                  maxlength="500">{{ old('pesan') }}</textarea>
                        @error('pesan')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                        <small class="form-text-muted">Pesan Anda akan dilihat oleh admin</small>
                    </div>

                    <!-- Bukti Transfer -->
                    <div class="form-group-custom">
                        <label class="form-label-custom">
                            Bukti Transfer/Pembelian <span class="required">*</span>
                        </label>
                        <input type="file" 
                               name="bukti_transfer" 
                               class="form-control-custom @error('bukti_transfer') is-invalid @enderror" 
                               accept="image/jpeg,image/png,image/jpg"
                               required
                               onchange="previewImage(event)">
                        @error('bukti_transfer')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                        <small class="form-text-muted">
                            <i class="fas fa-info-circle"></i> Format: JPG, JPEG, PNG | Maksimal: 2MB
                        </small>
                        
                        <!-- Preview Image -->
                        <div id="imagePreview" class="image-preview-container">
                            <p class="preview-label">Preview:</p>
                            <img id="preview" src="" class="preview-image">
                        </div>
                    </div>

                    <!-- Warning -->
                    <div class="alert-warning-custom">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Penting!</strong> Pastikan bukti {{ $kebutuhan->jenis == 'uang' ? 'transfer' : 'pembelian/donasi' }} sudah benar sebelum mengirim. Donasi Anda akan diverifikasi oleh admin.
                    </div>

                    <!-- Buttons -->
                    <div class="button-group">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-paper-plane"></i>
                            Kirim Donasi
                        </button>
                        <a href="{{ route('donatur.donasi.index') }}" class="btn-cancel">
                            <i class="fas fa-times"></i>
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-5">
            <!-- Detail Kebutuhan -->
            <div class="info-card">
                <h6 class="info-card-title">
                    <i class="fas fa-info-circle"></i>
                    Detail Kebutuhan
                </h6>
                <p class="info-card-text">{{ $kebutuhan->deskripsi }}</p>
                
                @if($kebutuhan->jumlah_target)
                <hr>
                <div class="progress-info-item">
                    <small class="progress-label">Target:</small>
                    <h5 class="progress-value">
                        @if($kebutuhan->jenis == 'uang')
                            Rp {{ number_format($kebutuhan->jumlah_target, 0, ',', '.') }}
                        @else
                            {{ number_format($kebutuhan->jumlah_target, 0, ',', '.') }} {{ $kebutuhan->satuan ?? 'pcs' }}
                        @endif
                    </h5>
                </div>
                <div class="progress-info-item">
                    <small class="progress-label">Terkumpul:</small>
                    <h5 class="progress-value primary">
                        @if($kebutuhan->jenis == 'uang')
                            Rp {{ number_format($kebutuhan->jumlah_terkumpul, 0, ',', '.') }}
                        @else
                            {{ number_format($kebutuhan->jumlah_terkumpul, 0, ',', '.') }} {{ $kebutuhan->satuan ?? 'pcs' }}
                        @endif
                    </h5>
                </div>
                @php
                    $persentase = $kebutuhan->jumlah_target > 0 
                        ? round(($kebutuhan->jumlah_terkumpul / $kebutuhan->jumlah_target) * 100, 1) 
                        : 0;
                @endphp
                <div class="progress-bar-wrapper">
                    <div class="progress-bar-fill" style="width: {{ min($persentase, 100) }}%"></div>
                </div>
                <small class="progress-percentage">{{ number_format($persentase, 1) }}% tercapai</small>
                @endif
            </div>

            <!-- Panduan Donasi -->
            <div class="info-card">
                <h6 class="info-card-title">
                    <i class="fas fa-book"></i>
                    Panduan Donasi
                </h6>
                @if($kebutuhan->jenis == 'uang')
                    <ol class="guide-list">
                        <li>Transfer ke rekening panti asuhan</li>
                        <li>Screenshot/foto bukti transfer</li>
                        <li>Isi form dan upload bukti transfer</li>
                        <li>Tunggu verifikasi dari admin</li>
                        <li>Cek status di halaman riwayat donasi</li>
                    </ol>

                    <div class="bank-info-box">
                        <strong><i class="fas fa-university"></i> Rekening Panti:</strong>
                        <small>
                            Bank Mandiri<br>
                            1234567890<br>
                            a.n. Yayasan Panti Asuhan
                        </small>
                    </div>
                @else
                    <ol class="guide-list">
                        <li>Beli/siapkan barang yang dibutuhkan</li>
                        <li>Foto barang atau nota pembelian</li>
                        <li>Isi form dengan jumlah barang</li>
                        <li>Upload bukti foto</li>
                        <li>Tunggu verifikasi dari admin</li>
                        <li>Kirim barang ke alamat panti</li>
                    </ol>

                    <div class="address-info-box">
                        <strong><i class="fas fa-map-marker-alt"></i> Alamat Panti:</strong>
                        <small>
                            Jl. Contoh No. 123<br>
                            Kota, Provinsi 12345<br>
                            <em>Hubungi admin untuk jadwal pengiriman</em>
                        </small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
// Preview Image
function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('preview');
    const previewDiv = document.getElementById('imagePreview');
    
    if (file) {
        // Validasi ukuran file (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Maksimal 2MB');
            event.target.value = '';
            previewDiv.style.display = 'none';
            return;
        }
        
        // Validasi tipe file
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!validTypes.includes(file.type)) {
            alert('Format file tidak valid! Gunakan JPG, JPEG, atau PNG');
            event.target.value = '';
            previewDiv.style.display = 'none';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewDiv.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
}

// Format Rupiah
function formatRupiah(input) {
    let value = input.value.replace(/[^0-9]/g, '');
    
    if (value) {
        let formatted = new Intl.NumberFormat('id-ID').format(value);
        input.value = formatted;
    }
}

// Submit form: convert formatted rupiah back to number
document.getElementById('donasiForm').addEventListener('submit', function(e) {
    const jumlahInput = document.getElementById('jumlah_donasi');
    if (jumlahInput) {
        // Hapus format titik sebelum submit
        jumlahInput.value = jumlahInput.value.replace(/\./g, '');
    }
    
    const nilaiInput = document.getElementById('nilai_barang');
    if (nilaiInput && nilaiInput.value) {
        // Hapus format titik sebelum submit
        nilaiInput.value = nilaiInput.value.replace(/\./g, '');
    }
});
</script>
@endsection