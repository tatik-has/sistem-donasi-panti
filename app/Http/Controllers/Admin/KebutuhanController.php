<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kebutuhan;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KebutuhanController extends Controller
{
    public function index(Request $request)
    {
        $query = Kebutuhan::query();

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan jenis
        if ($request->has('jenis') && $request->jenis != '') {
            $query->where('jenis', $request->jenis);
        }

        $kebutuhans = $query->orderBy('created_at', 'desc')->paginate(15);

        // Hitung statistik
        $totalAktif = Kebutuhan::where('status', 'aktif')->count();
        $totalNonAktif = Kebutuhan::where('status', 'nonaktif')->count();

        return view('admin.kebutuhan.index', compact('kebutuhans', 'totalAktif', 'totalNonAktif'));
    }

    public function create()
    {
        return view('admin.kebutuhan.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama_kebutuhan' => 'required|string|max:255',
            'jenis' => 'required|in:uang,barang',
            'deskripsi' => 'required|string',
            'jumlah_target' => 'nullable|numeric|min:0',
            'satuan' => 'required|string|max:50',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        DB::beginTransaction();
        try {
            // Buat kebutuhan baru
            $kebutuhan = Kebutuhan::create([
                'nama_kebutuhan' => $validated['nama_kebutuhan'],
                'jenis' => $validated['jenis'],
                'deskripsi' => $validated['deskripsi'],
                'jumlah_target' => $validated['jumlah_target'] ?? 0,
                'jumlah_terkumpul' => 0,
                'satuan' => $validated['satuan'],
                'status' => $validated['status']
            ]);

            // KIRIM NOTIFIKASI KE SEMUA DONATUR jika status aktif
            if ($kebutuhan->status === 'aktif') {
                $this->kirimNotifikasiKebutuhanBaru($kebutuhan);
            }

            DB::commit();

            return redirect()->route('admin.kebutuhan.index')
                ->with('success', 'Kebutuhan berhasil ditambahkan dan notifikasi telah dikirim ke semua donatur!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error saat membuat kebutuhan', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan kebutuhan: ' . $e->getMessage());
        }
    }

    public function edit(Kebutuhan $kebutuhan)
    {
        return view('admin.kebutuhan.edit', compact('kebutuhan'));
    }

    public function update(Request $request, Kebutuhan $kebutuhan)
    {
        // Validasi input
        $validated = $request->validate([
            'nama_kebutuhan' => 'required|string|max:255',
            'jenis' => 'required|in:uang,barang',
            'deskripsi' => 'required|string',
            'jumlah_target' => 'nullable|numeric|min:0',
            'satuan' => 'required|string|max:50',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        DB::beginTransaction();
        try {
            $statusLama = $kebutuhan->status;
            
            // Update kebutuhan
            $kebutuhan->update([
                'nama_kebutuhan' => $validated['nama_kebutuhan'],
                'jenis' => $validated['jenis'],
                'deskripsi' => $validated['deskripsi'],
                'jumlah_target' => $validated['jumlah_target'] ?? 0,
                'satuan' => $validated['satuan'],
                'status' => $validated['status']
            ]);

            // KIRIM NOTIFIKASI jika status berubah dari nonaktif ke aktif
            if ($statusLama === 'nonaktif' && $validated['status'] === 'aktif') {
                $this->kirimNotifikasiKebutuhanBaru($kebutuhan);
            }

            DB::commit();

            return redirect()->route('admin.kebutuhan.index')
                ->with('success', 'Kebutuhan berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error saat mengupdate kebutuhan', [
                'kebutuhan_id' => $kebutuhan->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui kebutuhan: ' . $e->getMessage());
        }
    }

    public function destroy(Kebutuhan $kebutuhan)
    {
        DB::beginTransaction();
        try {
            // Cek apakah ada donasi terkait
            $jumlahDonasi = $kebutuhan->donasis()->count();
            
            if ($jumlahDonasi > 0) {
                return redirect()->route('admin.kebutuhan.index')
                    ->with('error', 'Tidak dapat menghapus kebutuhan yang sudah memiliki donasi. Total donasi: ' . $jumlahDonasi);
            }

            $kebutuhan->delete();

            DB::commit();

            return redirect()->route('admin.kebutuhan.index')
                ->with('success', 'Kebutuhan berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error saat menghapus kebutuhan', [
                'kebutuhan_id' => $kebutuhan->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.kebutuhan.index')
                ->with('error', 'Gagal menghapus kebutuhan: ' . $e->getMessage());
        }
    }

    /**
     * FUNGSI BARU: Kirim notifikasi kebutuhan baru ke semua donatur
     */
    private function kirimNotifikasiKebutuhanBaru(Kebutuhan $kebutuhan)
    {
        try {
            // Ambil semua user dengan role donatur
            $donaturs = User::where('role', 'donatur')->get();

            // Format jumlah target
            if ($kebutuhan->jenis == 'uang') {
                $targetFormatted = 'Rp ' . number_format($kebutuhan->jumlah_target, 0, ',', '.');
            } else {
                $targetFormatted = number_format($kebutuhan->jumlah_target, 0, ',', '.') . ' ' . $kebutuhan->satuan;
            }

            // Buat notifikasi untuk setiap donatur
            foreach ($donaturs as $donatur) {
                Notification::create([
                    'user_id' => $donatur->id,
                    'type' => 'kebutuhan_baru',
                    'title' => '🆕 Kebutuhan Donasi Baru',
                    'message' => 'Panti membutuhkan bantuan: "' . $kebutuhan->nama_kebutuhan . '"' . 
                                ($kebutuhan->jumlah_target > 0 ? ' dengan target ' . $targetFormatted : '') . 
                                '. Mari berdonasi dan berbagi kebahagiaan!',
                    'link' => route('donatur.donasi.index'),
                    'is_read' => false
                ]);
            }

            Log::info('Notifikasi kebutuhan baru berhasil dikirim', [
                'kebutuhan_id' => $kebutuhan->id,
                'jumlah_donatur' => $donaturs->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error saat mengirim notifikasi kebutuhan baru', [
                'kebutuhan_id' => $kebutuhan->id,
                'error' => $e->getMessage()
            ]);
            // Tidak throw exception agar proses create/update tetap berhasil
        }
    }
}