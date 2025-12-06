@extends('layouts.admin')

@section('title', 'Edit Kebutuhan')

@section('content')
    <div class="topbar">
        </div>
    <div class="content-area">
        <div class="mb-4">
            <h2 class="fw-bold">
                <i class="fas fa-edit text-primary-green"></i> Edit Kebutuhan
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.kebutuhan.index') }}">Kebutuhan</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
        
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-times-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-8">
                <div class="card-custom">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.kebutuhan.update', $kebutuhan) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label-custom">Nama Kebutuhan <span class="text-danger">*</span></label>
                                <input type="text" 
                                        name="nama_kebutuhan" 
                                        class="form-control form-control-custom @error('nama_kebutuhan') is-invalid @enderror" 
                                        value="{{ old('nama_kebutuhan', $kebutuhan->nama_kebutuhan) }}" 
                                        required>
                                @error('nama_kebutuhan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label-custom">Jenis Kebutuhan <span class="text-danger">*</span></label>
                                <select name="jenis" 
                                        id="jenis_kebutuhan_edit" 
                                        class="form-control form-control-custom @error('jenis') is-invalid @enderror" 
                                        required>
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="uang" {{ old('jenis', $kebutuhan->jenis) == 'uang' ? 'selected' : '' }}>💰 Uang</option>
                                    <option value="barang" {{ old('jenis', $kebutuhan->jenis) == 'barang' ? 'selected' : '' }}>📦 Barang</option>
                                </select>
                                @error('jenis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label-custom">Deskripsi <span class="text-danger">*</span></label>
                                <textarea name="deskripsi" 
                                        class="form-control form-control-custom @error('deskripsi') is-invalid @enderror" 
                                        rows="5" 
                                        required>{{ old('deskripsi', $kebutuhan->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6" id="target-field-group-edit">
                                    </div>
                                <div class="col-md-6" id="satuan-field-group-edit">
                                    </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label-custom">Status <span class="text-danger">*</span></label>
                                <select name="status" 
                                        class="form-control form-control-custom @error('status') is-invalid @enderror" 
                                        required>
                                    <option value="aktif" {{ old('status', $kebutuhan->status) == 'aktif' ? 'selected' : '' }}>✅ Aktif</option>
                                    <option value="nonaktif" {{ old('status', $kebutuhan->status) == 'nonaktif' ? 'selected' : '' }}>❌ Non-aktif</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary-custom">
                                    <i class="fas fa-save"></i> Update Kebutuhan
                                </button>
                                <a href="{{ route('admin.kebutuhan.index') }}" class="btn btn-secondary-custom">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const jenisKebutuhan = document.getElementById('jenis_kebutuhan_edit');
        const targetGroup = document.getElementById('target-field-group-edit');
        const satuanGroup = document.getElementById('satuan-field-group-edit');

        // Data awal dari PHP
        const currentJenis = "{{ old('jenis', $kebutuhan->jenis) }}";
        const currentTarget = "{{ old('jumlah_target', $kebutuhan->jumlah_target) }}";
        const currentSatuan = "{{ old('satuan', $kebutuhan->satuan) }}";
        
        // Data satuan barang yang umum
        const satuanBarangOptions = [
            'Pcs', 'Kg', 'Liter', 'Kotak', 'Unit', 'Buah', 'Pak', 'Karung', 'Lainnya'
        ];

        function renderFields(jenis, targetValue = '', satuanValue = '') {
            if (jenis === 'uang') {
                targetGroup.innerHTML = `
                    <div class="mb-3">
                        <label class="form-label-custom">Jumlah Target (Rupiah)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" 
                                   name="jumlah_target_display" 
                                   id="jumlah_target_rupiah_edit"
                                   class="form-control form-control-custom @error('jumlah_target') is-invalid @enderror" 
                                   value="${targetValue ? formatRupiah(targetValue) : ''}" 
                                   placeholder="Contoh: 500.000"
                                   min="0">
                            <input type="hidden" name="jumlah_target" id="jumlah_target_hidden_edit" value="${targetValue}">
                        </div>
                        <small class="text-muted">Kosongkan jika tidak ada target spesifik</small>
                        @error('jumlah_target')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                `;
                satuanGroup.innerHTML = `
                    <div class="mb-3">
                        <label class="form-label-custom">Satuan</label>
                        <input type="text" 
                               name="satuan" 
                               class="form-control form-control-custom" 
                               value="Rupiah" 
                               readonly>
                        <small class="text-muted">Satuan wajib 'Rupiah' untuk donasi uang.</small>
                    </div>
                `;

            } else if (jenis === 'barang') {
                targetGroup.innerHTML = `
                    <div class="mb-3">
                        <label class="form-label-custom">Jumlah Target (Kuantitas)</label>
                        <input type="number" 
                               name="jumlah_target" 
                               class="form-control form-control-custom @error('jumlah_target') is-invalid @enderror" 
                               value="${targetValue}" 
                               placeholder="Contoh: 100"
                               step="1"
                               min="0">
                        <small class="text-muted">Masukkan kuantitas barang yang dibutuhkan.</small>
                        @error('jumlah_target')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                `;
                
                let satuanOptionsHtml = satuanBarangOptions.map(option => `
                    <option value="${option}" ${satuanValue === option ? 'selected' : ''}>${option}</option>
                `).join('');

                // Jika satuanValue tidak ada di opsi default, tambahkan sebagai 'Lainnya' yang dipilih
                if (satuanValue && !satuanBarangOptions.includes(satuanValue) && satuanValue !== 'Rupiah') {
                    satuanOptionsHtml += `<option value="${satuanValue}" selected>${satuanValue}</option>`;
                }
                
                satuanGroup.innerHTML = `
                    <div class="mb-3">
                        <label class="form-label-custom">Satuan Barang</label>
                        <select name="satuan" id="satuan_barang_select_edit" class="form-control form-control-custom @error('satuan') is-invalid @enderror" required>
                            <option value="">-- Pilih Satuan --</option>
                            ${satuanOptionsHtml}
                        </select>
                        <small class="text-muted">Pilih satuan: Pcs, Kg, Liter, dll.</small>
                        @error('satuan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                `;
            } else {
                targetGroup.innerHTML = '';
                satuanGroup.innerHTML = '';
            }
            
            // Re-attach event listener untuk format Rupiah
            if (jenis === 'uang') {
                const rupiahInput = document.getElementById('jumlah_target_rupiah_edit');
                const hiddenInput = document.getElementById('jumlah_target_hidden_edit');
                
                rupiahInput.addEventListener('keyup', function(e) {
                    const cleanValue = rupiahInput.value.replace(/\./g, '');
                    rupiahInput.value = formatRupiah(cleanValue);
                    hiddenInput.value = cleanValue;
                });
            }
        }
        
        function formatRupiah(angka, prefix) {
            var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return rupiah;
        }

        // Event listener saat jenis kebutuhan diubah
        jenisKebutuhan.addEventListener('change', function() {
            renderFields(this.value);
        });

        // Inisialisasi awal menggunakan data yang sudah ada (dari $kebutuhan atau old)
        renderFields(currentJenis, currentTarget, currentSatuan);
    });
</script>

<script>
// Skrip untuk Modal Hapus (dipertahankan dari kode Anda)
// ... (Skrip Modal Hapus) ...
</script>
@endpush