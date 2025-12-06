<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Ditambahkan untuk seeding awal

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary(); // Kunci (seperti 'nama_panti', 'whatsapp_number')
            $table->text('value')->nullable(); // Nilai dari pengaturan
            // Kita tidak memerlukan timestamps di sini, karena data diakses cepat melalui cache
        });
        
        // Seeding Awal (Opsional tapi sangat disarankan agar form Pengaturan tidak kosong saat pertama kali diakses)
        DB::table('settings')->insert([
            ['key' => 'nama_panti', 'value' => 'Yayasan Panti Asuhan Harapan Bangsa'],
            ['key' => 'rekening_bank', 'value' => 'Bank Mandiri 1234567890 (a.n. YPHB)'],
            ['key' => 'email_kontak', 'value' => 'kontak@panti.org'],
            ['key' => 'whatsapp_number', 'value' => '6281234567890'],
            ['key' => 'footer_text', 'value' => 'Sistem Donasi Panti, Dibuat untuk Kebaikan.'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
