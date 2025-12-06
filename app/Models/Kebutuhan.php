<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kebutuhan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kebutuhan',
        'jenis',
        'deskripsi',
        'jumlah_target',
        'jumlah_terkumpul',
        'satuan',
        'status'
    ];

    // Accessor untuk persentase
    public function getPersentaseAttribute()
    {
        if ($this->jumlah_target > 0) {
            return ($this->jumlah_terkumpul / $this->jumlah_target) * 100;
        }
        return 0;
    }

    // Relasi ke donasi
    public function donasis()
    {
        return $this->hasMany(Donasi::class);
    }

    // Scope untuk kebutuhan yang belum tercapai
    public function scopeBelumTercapai($query)
    {
        return $query->where('status', 'aktif')
            ->where(function($q) {
                $q->whereNull('jumlah_target')
                  ->orWhereRaw('jumlah_terkumpul < jumlah_target');
            });
    }
}