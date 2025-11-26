<?php

use App\Enums\Admin\Status\EnumStatusCicilanKelompok;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cicilan_kelompok', function (Blueprint $table) {
            $table->id();
            $table->string('status', 255)->default(EnumStatusCicilanKelompok::BELUM_BAYAR);
            $table->decimal('nominal_cicilan', 15, 2)->default(0);
            $table->string('bukti_pembayaran', 255)->nullable();
            $table->dateTime('tanggal_dibayar')->nullable();
            $table->string('tanggal_jatuh_tempo', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cicilan_kelompok');
    }
};
