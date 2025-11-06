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
        Schema::table('cicilan_kelompok', function (Blueprint $table) {
            $table->foreignId('pinjaman_kelompok_id')
                ->constrained('pinjaman_kelompok')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cicilan_kelompok', function (Blueprint $table) {
            $table->dropForeign(['pinjaman_kelompok_id']);
            $table->dropColumn(['pinjaman_kelompok_id']);
        });
    }
};
