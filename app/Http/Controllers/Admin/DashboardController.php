<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use App\Models\Kebutuhan;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalKebutuhan = Kebutuhan::count();
        // Asumsi donatur adalah user yang role-nya BUKAN admin
        $totalDonatur = User::where('role', '!=', 'admin')->count(); 
        
        // Statistik Donasi
        $totalDonasiBerhasil = Donasi::where('status', 'berhasil')->sum('jumlah_donasi');
        $donasiMenunggu = Donasi::where('status', 'menunggu')->count();

        $donasiTerbaru = Donasi::with(['user', 'kebutuhan'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalKebutuhan',
            'totalDonatur',
            'totalDonasiBerhasil', // Nama variabel diperbarui
            'donasiMenunggu',
            'donasiTerbaru'
        ));
    }
}