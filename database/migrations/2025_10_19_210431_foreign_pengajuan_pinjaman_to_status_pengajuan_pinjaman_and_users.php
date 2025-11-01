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
        Schema::table('pengajuan_pinjaman', function (Blueprint $table) {
            $table->foreignId('status_id')
                ->nullable()
                ->constrained('status_pengajuan_pinjaman')
                ->cascadeOnUpdate()
                ->onDelete('set null');
            $table->foreignId('kelompok_id')
                ->nullable()
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
        Schema::table('pengajuan_pinjaman', function (Blueprint $table) {
            $table->dropForeign(['status_id']);
            $table->dropForeign(['kelompok_id']);
            $table->dropColumn(['status_id', 'kelompok_id']);
        });
    }
};
