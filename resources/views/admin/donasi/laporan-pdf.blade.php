<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Donasi - {{ $namaBulan }} {{ $tahun }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #10b981;
        }

        .header h1 {
            font-size: 20px;
            color: #10b981;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 16px;
            color: #374151;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 10px;
            color: #6b7280;
        }

        .info-section {
            margin-bottom: 20px;
            background: #f9fafb;
            padding: 15px;
            border-radius: 5px;
        }

        .info-grid {
            display: table;
            width: 100%;
        }

        .info-item {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 10px;
        }

        .info-item .label {
            font-size: 9px;
            color: #6b7280;
            display: block;
            margin-bottom: 5px;
        }

        .info-item .value {
            font-size: 16px;
            font-weight: bold;
            display: block;
        }

        .info-item.success .value {
            color: #10b981;
        }

        .info-item.warning .value {
            color: #f59e0b;
        }

        .info-item.danger .value {
            color: #ef4444;
        }

        .info-item.primary .value {
            color: #8b5cf6;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table thead {
            background: #10b981;
            color: white;
        }

        table thead th {
            padding: 10px 8px;
            text-align: left;
            font-size: 10px;
            font-weight: 600;
        }

        table tbody td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 9px;
        }

        table tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }

        .status-berhasil {
            background: #d1fae5;
            color: #065f46;
        }

        .status-menunggu {
            background: #fef3c7;
            color: #92400e;
        }

        .status-ditolak {
            background: #fee2e2;
            color: #991b1b;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 9px;
            color: #6b7280;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #9ca3af;
        }

        .text-success {
            color: #10b981;
            font-weight: bold;
        }

        .text-info {
            color: #3b82f6;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN TRANSAKSI DONASI</h1>
        <h2>Periode: {{ $namaBulan }} {{ $tahun }}</h2>
        <p>Dicetak pada: {{ $tanggalCetak }}</p>
    </div>

    <!-- Info Statistik -->
    <div class="info-section">
        <div class="info-grid">
            <div class="info-item success">
                <span class="label">Donasi Berhasil</span>
                <span class="value">{{ number_format($jumlahDonasi) }}</span>
            </div>
            <div class="info-item warning">
                <span class="label">Menunggu Verifikasi</span>
                <span class="value">{{ number_format($jumlahMenunggu) }}</span>
            </div>
            <div class="info-item danger">
                <span class="label">Donasi Ditolak</span>
                <span class="value">{{ number_format($jumlahDitolak) }}</span>
            </div>
            <div class="info-item primary">
                <span class="label">Total Nominal</span>
                <span class="value">Rp {{ number_format($totalDonasi, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Tabel Data -->
    @if($donasis->count() > 0)
        <table>
            <thead>
                <tr>
                    <th width="3%">No</th>
                    <th width="10%">Tanggal</th>
                    <th width="18%">Donatur</th>
                    <th width="22%">Kebutuhan</th>
                    <th width="8%">Jenis</th>
                    <th width="15%">Jumlah</th>
                    <th width="10%">Status</th>
                    <th width="14%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($donasis as $index => $donasi)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $donasi->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <strong>{{ $donasi->user->name }}</strong><br>
                            <span style="color: #6b7280; font-size: 8px;">{{ $donasi->user->email }}</span>
                        </td>
                        <td>{{ $donasi->kebutuhan->nama_kebutuhan }}</td>
                        <td class="text-center">
                            @if($donasi->kebutuhan->jenis == 'uang')
                                <span style="color: #10b981;">Uang</span>
                            @else
                                <span style="color: #3b82f6;">Barang</span>
                            @endif
                        </td>
                        <td class="text-right">
                            @if($donasi->kebutuhan->jenis == 'uang')
                                <span class="text-success">Rp {{ number_format($donasi->jumlah_donasi, 0, ',', '.') }}</span>
                            @else
                                <span class="text-info">{{ number_format($donasi->jumlah_donasi, 2, ',', '.') }}
                                    {{ $donasi->kebutuhan->satuan ?? 'pcs' }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($donasi->status == 'berhasil')
                                <span class="status-badge status-berhasil">Berhasil</span>
                            @elseif($donasi->status == 'menunggu')
                                <span class="status-badge status-menunggu">Menunggu</span>
                            @else
                                <span class="status-badge status-ditolak">Ditolak</span>
                            @endif
                        </td>
                        <td style="font-size: 8px;">
                            {{ $donasi->keterangan_admin ?? '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary -->
        <div style="background: #f9fafb; padding: 15px; border-radius: 5px; margin-top: 20px;">
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="border: none; padding: 5px;"><strong>Total Transaksi:</strong></td>
                    <td style="border: none; padding: 5px; text-align: right;">{{ $donasis->count() }} transaksi</td>
                </tr>
                <tr>
                    <td style="border: none; padding: 5px;"><strong>Total Donasi Berhasil:</strong></td>
                    <td style="border: none; padding: 5px; text-align: right; color: #10b981; font-weight: bold;">
                        Rp {{ number_format($totalDonasi, 0, ',', '.') }}
                    </td>
                </tr>
            </table>
        </div>
    @else
        <div class="no-data">
            <p style="font-size: 14px; margin-bottom: 10px;">📭</p>
            <p>Tidak ada data donasi untuk periode ini</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh Sistem Donasi Panti</p>
        <p>© {{ date('Y') }} Sistem Donasi Panti. All rights reserved.</p>
    </div>
</body>

</html>