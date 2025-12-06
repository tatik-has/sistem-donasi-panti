<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('kebutuhan_id')->nullable()->constrained('kebutuhans')->onDelete('set null');
            $table->decimal('jumlah_donasi', 15, 2);
            $table->string('status')->default('pending');
            $table->string('payment_method')->nullable();
            $table->text('bukti_transfer')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donasis');
    }
};