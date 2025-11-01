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
            $table->foreignId('status_id')
                ->nullable()
                ->constrained('status_kelompok')
                ->cascadeOnUpdate()
                ->nullOnDelete();
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
        Schema::table('pinjaman_kelompok', function (Blueprint $table) {
            $table->dropForeign(['status_id']);
            $table->dropForeign(['kelompok_id']);
            $table->dropColumn(['status_id', 'kelompok_id']);
        });
    }
};
