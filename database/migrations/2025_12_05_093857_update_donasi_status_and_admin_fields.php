// database/migrations/xxxx_xx_xx_update_donasi_status_and_admin_fields.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Tambahkan ini untuk operasi raw query

return new class extends Migration
{
    public function up(): void
    {
        // PENTING: Lakukan perubahan kolom status terlebih dahulu
        Schema::table('donasis', function (Blueprint $table) {
            // Hapus foreign key/index jika ada sebelum mengubah tipe kolom
            // Walaupun kolom 'status' jarang memiliki index/foreign key.
        });

        // 1. Ubah Kolom 'status' dan SET NILAI LAMA ke 'menunggu'
        // Karena status lama adalah string('pending'), kita ubah ke ENUM,
        // dan memastikan semua data lama 'pending' dikonversi menjadi 'menunggu'.
        
        // Catatan: 'change()' membutuhkan paket `doctrine/dbal` jika versi Laravel Anda lebih lama.
        Schema::table('donasis', function (Blueprint $table) {
            // Ubah tipe kolom dan ganti default. Kita gunakan ENUM untuk konsistensi.
            $table->enum('status', ['menunggu', 'berhasil', 'ditolak'])->default('menunggu')->change();
        });
        
        // Jika ada status lama 'pending', ubah secara eksplisit di database
        DB::table('donasis')
            ->where('status', 'pending')
            ->update(['status' => 'menunggu']);


        // 2. Tambahkan kolom baru yang dibutuhkan admin
        Schema::table('donasis', function (Blueprint $table) {
            if (!Schema::hasColumn('donasis', 'keterangan_admin')) {
                $table->text('keterangan_admin')->nullable()->after('bukti_transfer');
            }
            if (!Schema::hasColumn('donasis', 'tanggal_verifikasi')) {
                $table->timestamp('tanggal_verifikasi')->nullable()->after('keterangan_admin');
            }
        });
    }

    public function down(): void
    {
        Schema::table('donasis', function (Blueprint $table) {
            // Rollback penambahan kolom
            if (Schema::hasColumn('donasis', 'keterangan_admin')) {
                $table->dropColumn('keterangan_admin');
            }
            if (Schema::hasColumn('donasis', 'tanggal_verifikasi')) {
                $table->dropColumn('tanggal_verifikasi');
            }
            
            // Rollback perubahan status (Opsional, karena data sudah dimigrasi)
            // Rollback ke string('pending') bisa menghilangkan data ENUM yang baru.
            // Jika harus di-rollback:
            // $table->string('status')->default('pending')->change();
        });
    }
};