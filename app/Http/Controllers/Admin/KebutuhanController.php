<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kebutuhan;
use Illuminate\Http\Request;

class KebutuhanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua kebutuhan dengan pagination
        $kebutuhans = Kebutuhan::orderBy('created_at', 'desc')->paginate(10);
        
        // Hitung statistik kebutuhan aktif dan non-aktif
        $totalAktif = Kebutuhan::where('status', 'aktif')->count();
        $totalNonAktif = Kebutuhan::where('status', 'nonaktif')->count();
        
        return view('admin.kebutuhan.index', compact(
            'kebutuhans',
            'totalAktif',
            'totalNonAktif'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kebutuhan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'nama_kebutuhan' => 'required|string|max:255',
            'jenis' => 'required|in:uang,barang',
            'deskripsi' => 'required|string',
            'jumlah_target' => 'nullable|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif',
        ];

        // Validasi satuan berdasarkan jenis
        if ($request->jenis == 'barang') {
            $rules['satuan'] = 'required|string|max:50';
        }

        $validated = $request->validate($rules);

        // Set satuan otomatis untuk uang
        if ($validated['jenis'] == 'uang') {
            $validated['satuan'] = 'Rupiah';
        }

        // Set default jumlah_terkumpul
        $validated['jumlah_terkumpul'] = 0;

        Kebutuhan::create($validated);

        return redirect()->route('admin.kebutuhan.index')
            ->with('success', 'Kebutuhan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kebutuhan $kebutuhan)
    {
        return view('admin.kebutuhan.show', compact('kebutuhan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kebutuhan $kebutuhan)
    {
        return view('admin.kebutuhan.edit', compact('kebutuhan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kebutuhan $kebutuhan)
    {
        $rules = [
            'nama_kebutuhan' => 'required|string|max:255',
            'jenis' => 'required|in:uang,barang',
            'deskripsi' => 'required|string',
            'jumlah_target' => 'nullable|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif',
        ];

        // Validasi satuan berdasarkan jenis
        if ($request->jenis == 'barang') {
            $rules['satuan'] = 'required|string|max:50';
        }

        $validated = $request->validate($rules);

        // Set satuan otomatis untuk uang
        if ($validated['jenis'] == 'uang') {
            $validated['satuan'] = 'Rupiah';
        }

        $kebutuhan->update($validated);

        return redirect()->route('admin.kebutuhan.index')
            ->with('success', 'Kebutuhan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kebutuhan $kebutuhan)
    {
        try {
            $kebutuhan->delete();
            
            return redirect()->route('admin.kebutuhan.index')
                ->with('success', 'Kebutuhan berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.kebutuhan.index')
                ->with('error', 'Gagal menghapus kebutuhan. Silakan coba lagi.');
        }
    }
}