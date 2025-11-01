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
        Schema::table('jadwal_pencairan', function (Blueprint $table) {
            $table->foreignId('status_id')
                ->nullable()
                ->constrained('status_jadwal_pencairan')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('pengajuan_id')
                ->constrained('pengajuan_pinjaman')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('kelompok_id')
                ->constrained('kelompok')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_pencairan', function (Blueprint $table) {
            $table->dropForeign(['status_id']);
            $table->dropForeign(['pengajuan_id']);
            $table->dropForeign(['kelompok_id']);
            $table->dropColumn(['status_id', 'pengajuan_id', 'kelompok_id']);
        });
    }
};
