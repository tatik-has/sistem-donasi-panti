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
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mengambil notifikasi yang belum dibaca (max 7) dan jumlah yang belum dibaca.
     * Digunakan oleh JavaScript untuk dropdown di layout.
     */
    public function unread(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['notifications' => [], 'unread_count' => 0]);
        }
        
        // Ambil notifikasi terbaru (maks 7) untuk dropdown
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(7)
            ->get()
            ->map(function($notif) {
                return [
                    'id' => $notif->id,
                    'type' => $notif->type,
                    'title' => $notif->title,
                    'message' => $notif->message,
                    'link' => $notif->link,
                    'is_read' => $notif->is_read,
                    'created_at' => $notif->created_at->diffForHumans(),
                    'icon' => $this->getNotificationIcon($notif->type),
                    'color' => $this->getNotificationColor($notif->type)
                ];
            });

        // Hitung jumlah notifikasi yang belum dibaca
        $unreadCount = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Menandai notifikasi tertentu sebagai sudah dibaca dan mengarahkan ke tautan yang ditentukan.
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
     */
    public function markAllAsRead(Request $request)
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Semua notifikasi telah ditandai sebagai sudah dibaca']);
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

    /**
     * Helper function untuk mendapatkan icon notifikasi berdasarkan type
     */
    private function getNotificationIcon($type)
    {
        $icons = [
            'donasi_baru' => 'fa-hand-holding-usd',
            'donasi_berhasil' => 'fa-check-circle',
            'donasi_ditolak' => 'fa-times-circle',
            'donasi_menunggu' => 'fa-clock',
            'kebutuhan_baru' => 'fa-bullhorn',
            'kebutuhan_tercapai' => 'fa-trophy'
        ];
        return $icons[$type] ?? 'fa-bell';
    }

    /**
     * Helper function untuk mendapatkan warna notifikasi berdasarkan type
     */
    private function getNotificationColor($type)
    {
        $colors = [
            'donasi_baru' => 'info',
            'donasi_berhasil' => 'success',
            'donasi_ditolak' => 'danger',
            'donasi_menunggu' => 'warning',
            'kebutuhan_baru' => 'primary',
            'kebutuhan_tercapai' => 'success'
        ];
        return $colors[$type] ?? 'secondary';
    }
}