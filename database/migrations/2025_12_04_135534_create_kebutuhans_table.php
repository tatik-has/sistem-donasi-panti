<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kebutuhans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kebutuhan');
            $table->enum('jenis', ['barang', 'uang']);
            $table->text('deskripsi');
            $table->decimal('jumlah_target', 15, 2)->nullable();
            $table->decimal('jumlah_terkumpul', 15, 2)->default(0);
            $table->string('satuan')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kebutuhans');
    }
};