<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kebutuhan_id',
        'jumlah_donasi',
        'nilai_barang',
        'pesan',
        'bukti_transfer',
        'status',
        'keterangan_admin',     // PENTING: ganti dari catatan_admin
        'tanggal_verifikasi'    // PENTING: tambahkan ini
    ];

    // Cast tanggal sebagai datetime
    protected $casts = [
        'tanggal_verifikasi' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi ke User (Donatur)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Kebutuhan
    public function kebutuhan()
    {
        return $this->belongsTo(Kebutuhan::class);
    }
}