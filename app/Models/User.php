<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens; // Dihapus/dibiarkan tidak terpakai jika tidak diinstal

class User extends Authenticatable
{
    // Pastikan hanya menggunakan trait yang sudah terinstal/dibutuhkan.
    use HasFactory, Notifiable; 

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Penting: Pastikan kolom 'role' ada di database.
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Cek apakah user memiliki role 'admin'.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user memiliki role 'donatur'.
     * Ini adalah metode yang dibutuhkan oleh DonaturMiddleware.
     */
    public function isDonatur(): bool
    {
        return $this->role === 'donatur';
    }

    // --- RELASI DAN HELPER NOTIFIKASI ---

    /**
     * Relasi ke Donasi (satu user bisa memiliki banyak donasi).
     */
    public function donasis()
    {
        return $this->hasMany(Donasi::class);
    }

    /**
     * Relasi ke Notifications (satu user bisa memiliki banyak notifikasi).
     */
    public function notifications()
    {
        // Asumsikan model Notification sudah didefinisikan (App\Models\Notification)
        return $this->hasMany(Notification::class)->orderBy('created_at', 'desc');
    }

    /**
     * Mendapatkan notifikasi yang belum dibaca.
     */
    public function unreadNotifications()
    {
        return $this->notifications()->where('is_read', false);
    }

    /**
     * Menghitung jumlah notifikasi yang belum dibaca.
     */
    public function unreadNotificationsCount(): int
    {
        return $this->unreadNotifications()->count();
    }
}