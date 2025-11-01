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
        Schema::create('pengajuan_pinjaman', function (Blueprint $table) {
            $table->id();
            $table->string('file_proposal')->nullable();
            $table->decimal('nominal_pinjaman', 15, 2);
            $table->integer('tenor');
            $table->dateTime('pengajuan_pada')->nullable();
            $table->dateTime('disetujui_pada')->nullable();
            $table->dateTime('ditolak_pada')->nullable();
            $table->longText('catatan')->nullable();
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
