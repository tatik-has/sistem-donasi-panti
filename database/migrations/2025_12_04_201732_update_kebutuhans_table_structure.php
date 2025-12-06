<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kebutuhans', function (Blueprint $table) {
            // Hapus kolom lama
            $table->dropColumn(['target_dana', 'target_barang']);
            
            // Tambah kolom baru
            $table->enum('jenis', ['barang', 'uang'])->after('nama_kebutuhan');
            $table->decimal('jumlah_target', 15, 2)->nullable()->after('deskripsi');
            $table->decimal('jumlah_terkumpul', 15, 2)->default(0)->after('jumlah_target');
            $table->string('satuan')->nullable()->after('jumlah_terkumpul');
        });
    }

    public function down(): void
    {
        Schema::table('kebutuhans', function (Blueprint $table) {
            // Kembalikan seperti semula
            $table->dropColumn(['jenis', 'jumlah_target', 'jumlah_terkumpul', 'satuan']);
            $table->decimal('target_dana', 15, 2)->nullable();
            $table->integer('target_barang')->nullable();
        });
    }
};