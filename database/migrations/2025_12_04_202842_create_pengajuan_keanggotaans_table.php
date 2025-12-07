<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuan_keanggotaan', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->nullable();
            $table->string('ktp')->nullable();
            $table->string('nama_lengkap')->nullable();
            $table->string('alamat')->nullable();
            $table->string('nomor_rekening')->nullable();
            $table->string('nomor_telepon')->nullable();
            $table->string('status');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_keanggotaan');
    }
};
