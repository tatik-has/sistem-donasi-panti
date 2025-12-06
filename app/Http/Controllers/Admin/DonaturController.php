<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // Asumsi Donatur adalah user dengan role donatur
use Illuminate\Http\Request;

class DonaturController extends Controller
{
    /**
     * Menampilkan daftar semua Donatur.
     * Donatur diasumsikan adalah User dengan role tertentu atau hanya User yang bukan Admin.
     */
    public function index(Request $request)
    {
        // Asumsi: Kita hanya ingin menampilkan user yang TIDAK memiliki role 'admin'
        // Jika Anda memiliki kolom 'role' pada tabel users:
        $query = User::where('role', '!=', 'admin')->orderBy('created_at', 'desc');

        // Jika Anda ingin menampilkan semua user kecuali user_id 1 (asumsi admin utama):
        // $query = User::where('id', '!=', 1)->orderBy('created_at', 'desc');

        // Tambahkan fungsi pencarian jika diperlukan
        $search = $request->get('search');
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
        }

        $donaturs = $query->paginate(15);
        
        return view('admin.donatur.index', compact('donaturs', 'search'));
    }

    /**
     * Menampilkan detail Donatur.
     */
    public function show(User $donatur)
    {
        // Pastikan Anda mendapatkan data detail donasi atau riwayatnya di sini jika perlu
        return view('admin.donatur.show', compact('donatur'));
    }

    // Metode lain (edit/update/destroy) diabaikan untuk saat ini
    // karena donatur dikelola melalui tabel User dasar.
}