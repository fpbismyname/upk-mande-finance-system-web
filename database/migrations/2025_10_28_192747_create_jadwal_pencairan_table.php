<?php

use App\Enums\Admin\Status\EnumStatusJadwalPencairan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jadwal_pencairan', function (Blueprint $table) {
            $table->id();
            $table->string('status', 255)->default(EnumStatusJadwalPencairan::BELUM_TERJADWAL->value);
            $table->dateTime('tanggal_pencairan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_pencairan');
    }
};
