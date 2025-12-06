<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil bulan & tahun dari request atau default bulan/tahun sekarang
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        // Data donasi berdasarkan filter bulan & tahun
        $donasis = Donasi::with(['user', 'kebutuhan'])
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Statistik donasi
        $jumlahDonasi = Donasi::where('status', 'berhasil')->count();
        $jumlahMenunggu = Donasi::where('status', 'menunggu')->count();
        $jumlahDitolak = Donasi::where('status', 'ditolak')->count();

        // Total nominal donasi berhasil
        $totalDonasi = Donasi::where('status', 'berhasil')->sum('jumlah_donasi');

        return view('admin.laporan.index', compact(
            'donasis',
            'bulan',
            'tahun',
            'jumlahDonasi',
            'jumlahMenunggu',
            'jumlahDitolak',
            'totalDonasi'
        ));
    }
}
