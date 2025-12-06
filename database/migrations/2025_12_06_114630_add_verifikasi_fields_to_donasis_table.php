<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donasis', function (Blueprint $table) {
            // Cek dan tambah kolom keterangan_admin jika belum ada
            if (!Schema::hasColumn('donasis', 'keterangan_admin')) {
                $table->text('keterangan_admin')->nullable()->after('status');
            }
            
            // Cek dan tambah kolom tanggal_verifikasi jika belum ada
            if (!Schema::hasColumn('donasis', 'tanggal_verifikasi')) {
                $table->timestamp('tanggal_verifikasi')->nullable()->after('keterangan_admin');
            }
            
            // Hapus kolom catatan_admin jika masih ada (karena diganti ke keterangan_admin)
            if (Schema::hasColumn('donasis', 'catatan_admin')) {
                $table->dropColumn('catatan_admin');
            }
        });
    }

    public function down(): void
    {
        Schema::table('donasis', function (Blueprint $table) {
            $table->dropColumn(['keterangan_admin', 'tanggal_verifikasi']);
        });
    }
};