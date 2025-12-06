<?php

namespace App\Http\Controllers\Donatur;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use App\Models\Kebutuhan;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DonasiController extends Controller
{
    public function index(Request $request)
    {
        $jenisFilter = $request->input('jenis');
        
        $query = Kebutuhan::belumTercapai();

        if ($jenisFilter) {
            $query->where('jenis', $jenisFilter);
        }

        $kebutuhans = $query
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        $totalDanaTerkumpul = Kebutuhan::where('jenis', 'uang')->sum('jumlah_terkumpul');

        return view('donatur.index', compact('kebutuhans', 'totalDanaTerkumpul')); 
    }

    public function create(Kebutuhan $kebutuhan)
    {
        if ($kebutuhan->status != 'aktif' || $kebutuhan->jumlah_terkumpul >= $kebutuhan->jumlah_target) {
            return redirect()->route('donatur.donasi.index')
                ->with('error', 'Maaf, donasi untuk kebutuhan "' . $kebutuhan->nama_kebutuhan . '" sudah tercapai atau tidak aktif lagi.');
        }

        return view('donatur.create', compact('kebutuhan'));
    }

    public function store(Request $request)
    {
        $kebutuhan = Kebutuhan::find($request->kebutuhan_id);
        
        if (!$kebutuhan) {
            return redirect()->back()->with('error', 'Kebutuhan donasi tidak ditemukan.')->withInput();
        }

        // Bersihkan format rupiah sebelum validasi
        if ($request->has('jumlah_donasi')) {
            $request->merge([
                'jumlah_donasi' => str_replace('.', '', $request->jumlah_donasi)
            ]);
        }
        
        if ($request->has('nilai_barang') && $request->nilai_barang) {
            $request->merge([
                'nilai_barang' => str_replace('.', '', $request->nilai_barang)
            ]);
        }

        // Validasi dinamis
        $rules = [
            'kebutuhan_id' => 'required|exists:kebutuhans,id',
            'jumlah_donasi' => 'required|numeric|min:1',
            'pesan' => 'nullable|string|max:500',
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];

        if ($kebutuhan->jenis == 'barang') {
            $rules['nilai_barang'] = 'nullable|numeric|min:0';
        } else {
            $request->request->remove('nilai_barang');
        }

        $validated = $request->validate($rules);

        // Pengecekan ganda status
        if ($kebutuhan->status != 'aktif' || $kebutuhan->jumlah_terkumpul >= $kebutuhan->jumlah_target) {
            return redirect()->route('donatur.donasi.index')
                ->with('error', 'Donasi gagal. Kebutuhan "' . $kebutuhan->nama_kebutuhan . '" telah mencapai target atau tidak aktif.');
        }

        // GUNAKAN DB TRANSACTION untuk mencegah duplikasi
        DB::beginTransaction();
        try {
            // Upload file
            $path = null;
            if ($request->hasFile('bukti_transfer')) {
                $file = $request->file('bukti_transfer');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('bukti_transfer', $filename, 'public'); 
            }

            // Simpan donasi
            $donasiData = [
                'user_id' => Auth::id(), 
                'kebutuhan_id' => $validated['kebutuhan_id'],
                'jumlah_donasi' => $validated['jumlah_donasi'],
                'nilai_barang' => $validated['nilai_barang'] ?? null,
                'pesan' => $validated['pesan'],
                'bukti_transfer' => $path,
                'status' => 'menunggu',
            ];

            $donasi = Donasi::create($donasiData);

            // Format jumlah untuk notifikasi
            if ($kebutuhan->jenis == 'uang') {
                $jumlahFormatted = 'Rp ' . number_format($validated['jumlah_donasi'], 0, ',', '.');
            } else {
                $jumlahFormatted = number_format($validated['jumlah_donasi'], 0, ',', '.') . ' ' . ($kebutuhan->satuan ?? 'pcs');
            }

            // NOTIFIKASI #1: Notifikasi untuk Donatur
            Notification::create([
                'user_id' => Auth::id(),
                'type' => 'donasi_menunggu',
                'title' => 'Donasi Berhasil Dikirim',
                'message' => 'Donasi Anda untuk "' . $kebutuhan->nama_kebutuhan . '" sebesar ' . $jumlahFormatted . ' sedang menunggu verifikasi admin.',
                'link' => route('donatur.riwayat.show', $donasi->id),
                'is_read' => false
            ]);

            // NOTIFIKASI #2: Notifikasi untuk Admin
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'donasi_baru',
                    'title' => 'Donasi Baru Masuk',
                    'message' => 'Donasi baru dari ' . Auth::user()->name . ' untuk "' . $kebutuhan->nama_kebutuhan . '" menunggu verifikasi.',
                    'link' => route('admin.donasi.show', $donasi->id),
                    'is_read' => false
                ]);
            }

            // Commit transaction
            DB::commit();

            return redirect()->route('donatur.riwayat.index') 
                ->with('success', 'Donasi berhasil dikirim! Menunggu verifikasi admin.');

        } catch (\Exception $e) {
            // Rollback jika ada error
            DB::rollback();
            
            // Hapus file jika sudah terupload
            if ($path) {
                Storage::disk('public')->delete($path);
            }
            
            Log::error("Donasi Store Failed for User " . Auth::id() . ": " . $e->getMessage(), [
                'line' => $e->getLine(),
                'stack' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan donasi. Silakan coba lagi.')
                ->withInput();
        }
    }

    public function riwayat()
    {
        $donasis = Donasi::where('user_id', Auth::id())
            ->with('kebutuhan')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('donatur.riwayat', compact('donasis'));
    }

    public function show(Donasi $donasi)
    {
        if ($donasi->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        return view('donatur.show', compact('donasi'));
    }
}