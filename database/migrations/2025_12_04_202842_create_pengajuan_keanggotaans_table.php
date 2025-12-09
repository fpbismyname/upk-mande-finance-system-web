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
            $table->string('nik', 255)->nullable();
            $table->string('ktp', 255)->nullable();
            $table->string('nama_lengkap', 255)->nullable();
            $table->string('alamat', 255)->nullable();
            $table->string('nomor_rekening', 255)->nullable();
            $table->string('nomor_telepon', 255)->nullable();
            $table->string('status', 255);
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
