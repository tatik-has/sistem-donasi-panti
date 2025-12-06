@extends('layouts.admin')

@section('title', 'Detail Donasi')

@section('content')

    <!-- Custom Style Injection (Simulasi untuk memperbaiki tampilan) -->
    <style>
        /* Gaya Kustom Tambahan */
        .card-custom {
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
            border: none;
            transition: all 0.3s ease;
        }

        .text-theme-primary {
            color: #10B981;
            /* Hijau Primary yang Khas */
        }

        /* Badge Status */
        .badge-status-menunggu {
            background-color: #fcd34d;
            color: #92400e;
            padding: 0.5em 0.7em;
            border-radius: 0.5rem;
            font-weight: 600;
        }

        .badge-status-berhasil {
            background-color: #34d399;
            color: #065f46;
            padding: 0.5em 0.7em;
            border-radius: 0.5rem;
            font-weight: 600;
        }

        .badge-status-ditolak {
            background-color: #f87171;
            color: #7f1d1d;
            padding: 0.5em 0.7em;
            border-radius: 0.5rem;
            font-weight: 600;
        }

        /* Detail List */
        .table-detail td {
            padding: 0.75rem 0;
        }

        .table-detail tr:not(:last-child) td {
            border-bottom: 1px solid #f3f4f6;
        }
    </style>

    <div class="content-area">
        <div class="mb-5">
            <h1 class="fw-bolder display-6">
                <i class="fas fa-file-invoice text-theme-primary"></i> Detail Donasi
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                            class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.donasi.index') }}"
                            class="text-decoration-none">Donasi</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail</li>
                </ol>
            </nav>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <!-- Informasi Donasi -->
                <div class="card card-custom mb-4">
                    <div class="card-header bg-white border-bottom-0 py-3 px-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0">
                                <i class="fas fa-info-circle text-theme-primary me-2"></i> Informasi Donasi
                            </h5>
                            @if($donasi->status === 'menunggu')
                                <span class="badge badge-status-menunggu">Menunggu Verifikasi</span>
                            @elseif($donasi->status === 'berhasil')
                                <span class="badge badge-status-berhasil">Berhasil Diverifikasi</span>
                            @else
                                <span class="badge badge-status-ditolak">Ditolak</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body p-4">

                        <table class="table table-borderless table-detail mb-0">
                            <tr>
                                <td width="200" class="text-muted">ID Transaksi</td>
                                <td><strong>#{{ str_pad($donasi->id, 6, '0', STR_PAD_LEFT) }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Kebutuhan</td>
                                <td><strong>{{ $donasi->kebutuhan->nama_kebutuhan }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Jenis Donasi</td>
                                <td>
                                    @if($donasi->kebutuhan->jenis == 'uang')
                                        <span class="badge bg-success py-1 px-2"><i class="fas fa-money-bill-wave"></i>
                                            Uang</span>
                                    @else
                                        <span class="badge bg-info py-1 px-2"><i class="fas fa-box"></i> Barang</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Jumlah Donasi</td>
                                <td>
                                    <h3 class="text-theme-primary fw-bolder mb-0">Rp
                                        {{ number_format($donasi->jumlah_donasi, 0, ',', '.') }}</h3>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tanggal Donasi</td>
                                <td>{{ $donasi->created_at->format('d F Y') }} <span
                                        class="text-muted small">({{ $donasi->created_at->format('H:i') }} WIB)</span></td>
                            </tr>
                            @if($donasi->tanggal_verifikasi)
                                <tr>
                                    <td class="text-muted">Tanggal Verifikasi</td>
                                    <td>{{ $donasi->tanggal_verifikasi->format('d F Y') }} <span
                                            class="text-muted small">({{ $donasi->tanggal_verifikasi->format('H:i') }}
                                            WIB)</span></td>
                                </tr>
                            @endif
                        </table>

                        @if($donasi->pesan)
                            <div class="mt-4 border-top pt-3">
                                <label class="text-muted fw-bold mb-2">Pesan dari Donatur:</label>
                                <blockquote
                                    class="blockquote border-start border-5 border-success ps-3 pt-1 bg-light p-3 rounded">
                                    <p class="mb-0 fst-italic">{{ $donasi->pesan }}</p>
                                    <footer class="blockquote-footer mt-1">Donatur</footer>
                                </blockquote>
                            </div>
                        @endif

                        @if($donasi->keterangan_admin)
                            <div class="mt-3">
                                <label class="text-muted fw-bold mb-2">Keterangan Admin (Pesan Verifikasi):</label>
                                <div class="alert alert-info border-info border-2">
                                    <i class="fas fa-user-shield me-2"></i> {{ $donasi->keterangan_admin }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Bukti Transfer -->
                <div class="card card-custom mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3 border-bottom pb-2">
                            <i class="fas fa-image text-theme-primary me-2"></i> Bukti Transfer
                        </h5>
                        @if($donasi->bukti_transfer)
                            <div class="text-center p-3 bg-light rounded">
                                <img src="{{ asset('storage/' . $donasi->bukti_transfer) }}" alt="Bukti Transfer"
                                    class="img-fluid rounded shadow-sm"
                                    style="max-height: 400px; width: auto; cursor: pointer; border: 1px solid #ddd;"
                                    onclick="window.open(this.src, '_blank')">
                                <p class="text-muted mt-3 mb-0">
                                    <small><i class="fas fa-search-plus me-1"></i> Klik gambar untuk memperbesar resolusi
                                        penuh</small>
                                </p>
                            </div>
                        @else
                            <div class="alert alert-warning text-center">
                                <i class="fas fa-exclamation-triangle me-2"></i> Bukti transfer tidak tersedia untuk donasi ini.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Form Verifikasi / Aksi Lanjutan -->
                @if($donasi->status === 'menunggu')
                    <div class="card card-custom border-success" id="verifikasi" style="border-left: 5px solid #10B981 !important;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3 text-theme-primary">
                                <i class="fas fa-check-circle me-2"></i> Proses Verifikasi Donasi
                            </h5>

                            <form method="POST" action="{{ route('admin.donasi.verifikasi', $donasi->id) }}">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Keputusan Verifikasi <span
                                            class="text-danger">*</span></label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="">-- Pilih Keputusan --</option>
                                        <option value="berhasil">✅ Terima (Berhasil)</option>
                                        <option value="ditolak">❌ Tolak (Ditolak)</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Keterangan (Opsional)</label>
                                    <textarea name="keterangan_admin"
                                        class="form-control @error('keterangan_admin') is-invalid @enderror" rows="3"
                                        placeholder="Berikan keterangan jika donasi ditolak, atau pesan terima kasih jika diterima..."></textarea>
                                    @error('keterangan_admin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Keterangan ini (jika diisi) akan ditampilkan kepada
                                        donatur.</small>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-success fw-bold">
                                        <i class="fas fa-paper-plane me-1"></i> Kirim dan Simpan Keputusan
                                    </button>
                                    <a href="{{ route('admin.donasi.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Kembali
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Aksi Lanjutan (Hapus Donasi) -->
                    <div class="card card-custom mb-4 bg-light">
                        <div class="card-body text-center">
                            <p class="text-muted mb-3">Status donasi sudah <strong>FINAL</strong> ({{ ucwords($donasi->status) }}).
                                Aksi lanjutan yang tersedia:</p>
                            <button type="button" class="btn btn-outline-danger fw-bold btn-delete-donasi-detail"
                                data-id="{{ $donasi->id }}">
                                <i class="fas fa-trash me-1"></i> Hapus Donasi Permanen
                            </button>
                        </div>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('admin.donasi.index') }}" class="btn btn-secondary fw-bold">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Donasi
                        </a>
                    </div>
                @endif
            </div>

            <!-- Sidebar Info Donatur -->
            <div class="col-lg-4">
                <div class="card card-custom mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 border-bottom pb-2">
                            <i class="fas fa-user-circle text-theme-primary me-2"></i> Detail Donatur
                        </h5>

                        <div class="text-center mb-4">
                            <div class="bg-light text-muted rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 100px; height: 100px;">
                                <i class="fas fa-user fa-3x"></i>
                            </div>
                            <h4 class="mt-3 mb-1 fw-bold">{{ $donasi->user->name ?? 'Donatur Anonim' }}</h4>
                            <a href="{{ route('admin.donatur.show', $donasi->user->id) }}"
                                class="small text-theme-primary text-decoration-none fw-bold">
                                Lihat Profil Donatur <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>

                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted"><i class="fas fa-envelope"></i> Email</td>
                                <td>{{ $donasi->user->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted"><i class="fas fa-phone"></i> Telepon</td>
                                <td>{{ $donasi->user->no_telepon ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted" valign="top"><i class="fas fa-map-marker-alt"></i> Alamat
                                </td>
                                <td>{{ $donasi->user->alamat ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($donasi->status === 'menunggu')
                    <div class="card card-custom border-warning p-3"
                        style="background: rgba(251, 191, 36, 0.05); border-left: 5px solid #f59e0b;">
                        <div class="card-body p-0">
                            <h6 class="fw-bold text-warning" style="color: #f59e0b !important;">
                                <i class="fas fa-clock me-2"></i> Perhatian Verifikasi!
                            </h6>
                            <p class="mb-0 small text-dark">
                                Anda harus memverifikasi donasi ini. Pastikan Anda telah melihat dan mencocokkan bukti
                                transfer dengan data bank yang relevan sebelum mengambil keputusan.
                            </p>
                            <a href="#verifikasi" class="small text-decoration-none fw-bold mt-2 d-block">Lanjutkan ke
                                Verifikasi <i class="fas fa-arrow-down"></i></a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus Donasi -->
    <div class="modal fade" id="deleteDonasiModal" tabindex="-1" aria-labelledby="deleteDonasiModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header bg-danger text-white border-0 rounded-top-4">
                    <h5 class="modal-title fw-bold" id="deleteDonasiModalLabel"><i
                            class="fas fa-trash-alt me-2"></i> Konfirmasi Hapus Donasi</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="lead">Apakah Anda yakin ingin menghapus transaksi donasi ID <strong
                            id="donasiIdDetailText" class="text-danger"></strong> ini secara permanen?</p>
                    <div class="alert alert-warning border-warning">
                        <h6 class="alert-heading fw-bold text-danger"><i
                                class="fas fa-exclamation-triangle me-2"></i> Peringatan Keras!</h6>
                        <p class="mb-0 small">Jika donasi ini berstatus <strong>'Berhasil'</strong>, jumlah yang terkumpul untuk
                            Kebutuhan terkait akan otomatis dikurangi. Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4"
                        data-bs-dismiss="modal">Batal</button>
                    <form id="deleteDonasiForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Ya, Hapus
                            Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Cek apakah Bootstrap ada di scope
            if (typeof bootstrap === 'undefined') {
                console.error("Bootstrap JS not loaded. Modal functionality will be broken.");
                return;
            }

            // Event listener untuk tombol hapus donasi di halaman Detail
            const deleteButtonDetail = document.querySelector('.btn-delete-donasi-detail');

            if (deleteButtonDetail) {
                deleteButtonDetail.addEventListener('click', function () {
                    const donasiId = this.getAttribute('data-id');
                    const modal = new bootstrap.Modal(document.getElementById('deleteDonasiModal'));

                    // Isi ID donasi di modal
                    document.getElementById('donasiIdDetailText').textContent = '#' + donasiId.padStart(6, '0');

                    // Set action form delete
                    const deleteForm = document.getElementById('deleteDonasiForm');
                    // Menggunakan route helper Laravel di Blade untuk mendapatkan URL yang benar
                    const deleteRoute = '{{ route('admin.donasi.destroy', ['donasi' => ':id']) }}';
                    deleteForm.action = deleteRoute.replace(':id', donasiId);

                    modal.show();
                });
            }
        });
    </script>
@endpush