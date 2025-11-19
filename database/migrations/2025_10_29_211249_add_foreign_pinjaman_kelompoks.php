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
        Schema::table('pinjaman_kelompok', function (Blueprint $table) {
            $table->foreignId('kelompok_id')
                ->constrained('kelompok')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('jadwal_pencairan_id')
                ->constrained('jadwal_pencairan')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('pengajuan_pinjaman_id')
                ->constrained('pengajuan_pinjaman')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pinjaman_kelompok', function (Blueprint $table) {
            $table->dropForeign(['kelompok_id']);
            $table->dropForeign(['jadwal_pencairan_id']);
            $table->dropForeign(['pengajuan_pinjaman_id']);
            $table->dropColumn(['kelompok_id', 'jadwal_pencairan_id', 'pengajuan_pinjaman_id']);
        });
    }
};
