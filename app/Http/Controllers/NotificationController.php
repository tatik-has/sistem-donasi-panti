<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Menampilkan semua notifikasi untuk pengguna yang sedang login.
     */
    public function index()
    {
        // Menggunakan relasi `notifications` pada user model untuk notifikasi yang menggunakan sistem notifikasi bawaan Laravel.
        // Namun, karena Anda menggunakan model `Notification` kustom dengan kolom `user_id` dan `is_read`,
        // kita akan tetap menggunakan query kustom Anda.
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('notifications.index', compact('notifications'));
        // Pastikan Anda sudah membuat view: resources/views/notifications/index.blade.php
    }

    /**
     * Mengambil notifikasi yang belum dibaca (max 5) dan jumlah yang belum dibaca.
     * Digunakan oleh JavaScript untuk dropdown di layout.
     */
    public function unread(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['notifications' => [], 'unread_count' => 0]);
        }
        
        // Ambil notifikasi terbaru (maks 7) untuk dropdown
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(7) // Batasi jumlah di dropdown
            ->get();

        // Hitung jumlah notifikasi yang belum dibaca
        // Catatan: Jika Anda menggunakan model `Notification` kustom, pastikan relasi `notifications` di model `User` mengarah ke model ini.
        // Jika tidak, Anda bisa menggunakan query langsung ke model Notification:
        $unreadCount = Notification::where('user_id', Auth::id())->where('is_read', false)->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Menandai notifikasi tertentu sebagai sudah dibaca dan mengarahkan ke tautan yang ditentukan.
     * NAMA METHOD INI TELAH DIPERBAIKI DARI 'read' MENJADI 'markAsRead'
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Tandai sebagai sudah dibaca
        $notification->update(['is_read' => true]);

        // Redirect ke link yang ada di notifikasi, atau ke halaman utama jika tidak ada link
        return redirect()->to($notification->link ?? route('notifications.index'));
    }

    /**
     * Menandai SEMUA notifikasi pengguna sebagai sudah dibaca.
     * NAMA METHOD INI TELAH DIPERBAIKI DARI 'markAllRead' MENJADI 'markAllAsRead'
     */
    public function markAllAsRead(Request $request)
    {
        Notification::where('user_id', Auth::id())->update(['is_read' => true]);
        // Catatan: Menggunakan query langsung ke model Notification lebih eksplisit

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai sudah dibaca.');
    }

    /**
     * Menghapus notifikasi tertentu.
     */
    public function destroy($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        $notification->delete();

        return redirect()->route('notifications.index')->with('success', 'Notifikasi berhasil dihapus.');
    }
}