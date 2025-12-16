<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Helper methods untuk role
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isDonatur()
    {
        return $this->role === 'donatur';
    }

    // RELASI KE NOTIFIKASI KUSTOM
    public function notifications()
    {
        return $this->hasMany(Notification::class)->orderBy('created_at', 'desc');
    }

    // Relasi ke donasi
    public function donasis()
    {
        return $this->hasMany(Donasi::class);
    }

    // Accessor untuk mendapatkan jumlah notifikasi yang belum dibaca
    public function getUnreadNotificationsCountAttribute()
    {
        return $this->notifications()->where('is_read', false)->count();
    }
}