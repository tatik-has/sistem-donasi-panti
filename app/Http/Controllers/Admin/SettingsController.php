<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'nama_panti' => Cache::get('nama_panti', 'Yayasan Panti Asuhan'),
            'rekening_bank' => Cache::get('rekening_bank', ''),
            'email_kontak' => Cache::get('email_kontak', ''),
            'whatsapp_number' => Cache::get('whatsapp_number', ''),
            'footer_text' => Cache::get('footer_text', ''),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_panti' => 'required|string|max:255',
            'rekening_bank' => 'required|string|max:255',
            'email_kontak' => 'required|email|max:255',
            'whatsapp_number' => 'required|string|max:20',
            'footer_text' => 'nullable|string|max:1000',
        ]);

        Cache::put('nama_panti', $request->nama_panti);
        Cache::put('rekening_bank', $request->rekening_bank);
        Cache::put('email_kontak', $request->email_kontak);
        Cache::put('whatsapp_number', $request->whatsapp_number);
        Cache::put('footer_text', $request->footer_text);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan berhasil disimpan!');
    }
}