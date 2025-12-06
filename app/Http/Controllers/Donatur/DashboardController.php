<?php

namespace App\Http\Controllers\Donatur;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use App\Models\Kebutuhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id(); // Menggunakan Auth::id()

        // 1. Statistik Donasi
        $donasiBerhasilCount = Donasi::where('user_id', $userId)
            ->where('status', 'berhasil')
            ->count();
        
        $donasiMenungguCount = Donasi::where('user_id', $userId)
            ->where('status', 'menunggu')
            ->count();
            
        // Menghitung total dana UANG yang didonasikan (Berhasil)
        $totalDonasiUang = Donasi::where('user_id', $userId)
            ->where('status', 'berhasil')
            // Menghitung hanya donasi yang terkait dengan Kebutuhan jenis 'uang'
            ->whereHas('kebutuhan', function($query) {
                $query->where('jenis', 'uang');
            })
            ->sum('jumlah_donasi'); 
            
        // 2. Riwayat Donasi Terbaru (Diubah namanya menjadi $riwayatTerbaru agar konsisten dengan view)
        $riwayatTerbaru = Donasi::where('user_id', $userId)
            ->with('kebutuhan') 
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // 3. Kebutuhan (Dibiarkan tetap, meskipun tidak ditampilkan di dashboard yang Anda kirim)
        $kebutuhans = Kebutuhan::where('status', 'aktif')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return view('donatur.dashboard', compact(
            'donasiBerhasilCount', // <- Donasi Berhasil (Jumlah Transaksi)
            'donasiMenungguCount', // <- Donasi Menunggu (Jumlah Transaksi)
            'totalDonasiUang',     // <- Total Donasi Uang (Nilai Rupiah)
            'riwayatTerbaru',      // <- Riwayat Transaksi Terbaru
            'kebutuhans'
        ));
    }
}