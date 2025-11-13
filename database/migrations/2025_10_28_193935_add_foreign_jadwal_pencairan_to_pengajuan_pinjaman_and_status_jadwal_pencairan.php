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
        Schema::table('jadwal_pencairan', function (Blueprint $table) {
            $table->dropForeign(['pengajuan_pinjaman_id']);
            $table->dropColumn(['pengajuan_pinjaman_id']);
        });
    }
};
