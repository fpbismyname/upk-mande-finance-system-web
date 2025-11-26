<?php

use App\Enums\Admin\Rekening\EnumTipeTransaksi;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaksi_rekening', function (Blueprint $table) {
            $table->foreignId('rekening_id')
                ->nullable()
                ->constrained('rekening')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_rekening', function (Blueprint $table) {
            $table->dropForeign(['rekening_id']);
            $table->dropColumn(['rekening_id']);
        });
    }
};
