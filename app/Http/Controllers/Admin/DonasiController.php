<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use App\Models\Kebutuhan;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class DonasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Donasi::with(['user', 'kebutuhan']);

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q2) use ($search) {
                    $q2->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('kebutuhan', function($q2) use ($search) {
                    $q2->where('nama_kebutuhan', 'like', '%' . $search . '%');
                });
            });
        }

        $donasis = $query->orderBy('created_at', 'desc')->paginate(15);

        // HITUNG STATISTIK UNTUK CARD
        $totalMenunggu = Donasi::where('status', 'menunggu')->count();
        $totalBerhasil = Donasi::where('status', 'berhasil')->count();
        $totalDitolak = Donasi::where('status', 'ditolak')->count();

        return view('admin.donasi.index', compact(
            'donasis',
            'totalMenunggu',
            'totalBerhasil',
            'totalDitolak'
        ));
    }

    public function show(Donasi $donasi)
    {
        $donasi->load(['user', 'kebutuhan']);
        return view('admin.donasi.show', compact('donasi'));
    }

    public function verifikasi(Request $request, $id)
    {
        // Log untuk debugging
        Log::info('Verifikasi dimulai', [
            'donasi_id' => $id,
            'request_data' => $request->all()
        ]);

        // Cari donasi berdasarkan ID
        $donasi = Donasi::findOrFail($id);

        // Validasi input
        $validated = $request->validate([
            'status' => 'required|in:berhasil,ditolak',
            'keterangan_admin' => 'nullable|string|max:500'
        ]);

        $statusLama = $donasi->status;
        $statusBaru = $validated['status'];

        // Validasi: Jika sudah berhasil, tidak bisa diubah lagi
        if ($statusLama === 'berhasil') {
            Log::warning('Percobaan mengubah donasi yang sudah berhasil', ['donasi_id' => $id]);
            return redirect()->route('admin.donasi.show', $donasi->id)
                ->with('error', 'Donasi yang sudah berhasil tidak dapat diubah statusnya.');
        }

        // Validasi: Jika status tidak berubah
        if ($statusLama === $statusBaru) {
            return redirect()->route('admin.donasi.show', $donasi->id)
                ->with('info', 'Status donasi tidak berubah.');
        }

        DB::beginTransaction();
        try {
            // Update status donasi dengan tanggal verifikasi
            $donasi->update([
                'status' => $statusBaru,
                'keterangan_admin' => $validated['keterangan_admin'] ?? null,
                'tanggal_verifikasi' => now()
            ]);

            $kebutuhan = $donasi->kebutuhan;

            // FORMAT JUMLAH DONASI
            if ($kebutuhan->jenis == 'uang') {
                $jumlahFormatted = 'Rp ' . number_format($donasi->jumlah_donasi, 0, ',', '.');
            } else {
                $jumlahFormatted = number_format($donasi->jumlah_donasi, 0, ',', '.') . ' ' . ($kebutuhan->satuan ?? 'pcs');
            }

            // === JIKA STATUS BERHASIL ===
            if ($statusBaru === 'berhasil') {
                // Update jumlah terkumpul
                $kebutuhan->increment('jumlah_terkumpul', $donasi->jumlah_donasi);
                $kebutuhan->refresh();

                // Notifikasi untuk DONATUR
                Notification::create([
                    'user_id' => $donasi->user_id,
                    'type' => 'donasi_berhasil',
                    'title' => '✅ Donasi Diverifikasi',
                    'message' => 'Donasi Anda untuk "' . $kebutuhan->nama_kebutuhan . '" sebesar ' . $jumlahFormatted . ' telah diverifikasi dan diterima. Terima kasih atas kontribusi Anda!',
                    'link' => route('donatur.riwayat'),
                    'is_read' => false
                ]);

                // Cek apakah target tercapai
                if ($kebutuhan->jumlah_target > 0 && $kebutuhan->jumlah_terkumpul >= $kebutuhan->jumlah_target) {
                    // Notifikasi untuk SEMUA USER (target tercapai)
                    $users = User::all();
                    foreach ($users as $user) {
                        Notification::create([
                            'user_id' => $user->id,
                            'type' => 'kebutuhan_tercapai',
                            'title' => '🎉 Target Tercapai!',
                            'message' => 'Kebutuhan "' . $kebutuhan->nama_kebutuhan . '" telah mencapai target 100%! Terima kasih atas semua donasi!',
                            'link' => route('donatur.donasi.index'),
                            'is_read' => false
                        ]);
                    }
                    
                    // Nonaktifkan kebutuhan karena target sudah tercapai
                    $kebutuhan->update(['status' => 'nonaktif']); 
                }

                Log::info('Donasi berhasil diverifikasi', ['donasi_id' => $id]);
            }
            // === JIKA STATUS DITOLAK ===
            elseif ($statusBaru === 'ditolak') {
                // Jika sebelumnya sudah berhasil, kembalikan jumlah terkumpul
                if ($statusLama === 'berhasil') {
                    $kebutuhan->decrement('jumlah_terkumpul', $donasi->jumlah_donasi);
                    $kebutuhan->refresh();
                }

                // Notifikasi untuk DONATUR
                Notification::create([
                    'user_id' => $donasi->user_id,
                    'type' => 'donasi_ditolak',
                    'title' => '❌ Donasi Ditolak',
                    'message' => 'Maaf, donasi Anda untuk "' . $kebutuhan->nama_kebutuhan . '" sebesar ' . $jumlahFormatted . ' tidak dapat diverifikasi. ' . 
                                ($validated['keterangan_admin'] ? 'Alasan: ' . $validated['keterangan_admin'] : 'Silakan hubungi admin untuk informasi lebih lanjut.'),
                    'link' => route('donatur.riwayat'),
                    'is_read' => false
                ]);

                Log::info('Donasi ditolak', ['donasi_id' => $id]);
            }

            DB::commit();

            return redirect()->route('admin.donasi.show', $donasi->id)
                ->with('success', 'Status donasi berhasil diperbarui menjadi ' . $statusBaru . ' dan notifikasi telah dikirim ke donatur.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error saat verifikasi donasi', [
                'donasi_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.donasi.show', $donasi->id)
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Donasi $donasi)
    {
        DB::beginTransaction();
        try {
            // Hapus bukti transfer jika ada
            if ($donasi->bukti_transfer) {
                Storage::disk('public')->delete($donasi->bukti_transfer);
            }

            // Jika donasi berhasil, kurangi jumlah terkumpul
            if ($donasi->status === 'berhasil') {
                $kebutuhan = $donasi->kebutuhan;
                $kebutuhan->decrement('jumlah_terkumpul', $donasi->jumlah_donasi);
            }

            $donasi->delete();

            DB::commit();

            return redirect()->route('admin.donasi.index')
                ->with('success', 'Donasi berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('admin.donasi.index')
                ->with('error', 'Gagal menghapus donasi: ' . $e->getMessage());
        }
    }

    public function laporan(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $bulan = $request->input('bulan', date('m'));

        $query = Donasi::with(['user', 'kebutuhan'])
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->orderBy('created_at', 'desc');

        $donasis = $query->paginate(15);

        // Hitung statistik untuk bulan dan tahun yang dipilih
        $donasisBerhasil = Donasi::with(['user', 'kebutuhan'])
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->where('status', 'berhasil')
            ->get();

        $jumlahMenunggu = Donasi::whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->where('status', 'menunggu')
            ->count();

        $jumlahDitolak = Donasi::whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->where('status', 'ditolak')
            ->count();

        $totalDonasi = $donasisBerhasil->sum('jumlah_donasi');
        $jumlahDonasi = $donasisBerhasil->count();

        return view('admin.donasi.laporan', compact(
            'donasis', 
            'totalDonasi', 
            'jumlahDonasi', 
            'jumlahMenunggu',
            'jumlahDitolak',
            'tahun', 
            'bulan', 
            'donasisBerhasil'
        ));
    }

    public function exportPdf(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $bulan = $request->input('bulan', date('m'));

        // Ambil semua data tanpa pagination untuk export
        $donasis = Donasi::with(['user', 'kebutuhan'])
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung statistik
        $donasisBerhasil = $donasis->where('status', 'berhasil');
        $donasisMenunggu = $donasis->where('status', 'menunggu');
        $donasisDitolak = $donasis->where('status', 'ditolak');

        $totalDonasi = $donasisBerhasil->sum('jumlah_donasi');
        $jumlahDonasi = $donasisBerhasil->count();
        $jumlahMenunggu = $donasisMenunggu->count();
        $jumlahDitolak = $donasisDitolak->count();

        // Nama bulan
        $namaBulan = [
            '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $data = [
            'donasis' => $donasis,
            'totalDonasi' => $totalDonasi,
            'jumlahDonasi' => $jumlahDonasi,
            'jumlahMenunggu' => $jumlahMenunggu,
            'jumlahDitolak' => $jumlahDitolak,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'namaBulan' => $namaBulan[$bulan],
            'tanggalCetak' => now()->format('d/m/Y H:i')
        ];

        $pdf = Pdf::loadView('admin.donasi.laporan-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        
        $filename = 'Laporan_Donasi_' . $namaBulan[$bulan] . '_' . $tahun . '.pdf';
        
        return $pdf->download($filename);
    }
}