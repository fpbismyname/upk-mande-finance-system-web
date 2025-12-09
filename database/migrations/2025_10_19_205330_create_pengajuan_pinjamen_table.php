<?php

use App\Enums\Admin\Status\EnumStatusPengajuanPinjaman;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuan_pinjaman', function (Blueprint $table) {
            $table->id();
            $table->string('file_proposal', 255)->nullable();
            $table->decimal('nominal_pinjaman', 15, 2);
            $table->integer('tenor');
            $table->longText('catatan')->nullable();
            $table->dateTime('tanggal_pengajuan')->nullable();
            $table->dateTime('tanggal_disetujui')->nullable();
            $table->dateTime('tanggal_ditolak')->nullable();
            $table->string('status', 255)->default(EnumStatusPengajuanPinjaman::PROSES_PENGAJUAN->value);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_pinjaman');
    }
};
