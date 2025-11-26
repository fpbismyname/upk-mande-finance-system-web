<?php

use App\Enums\Admin\Status\EnumStatusPinjaman;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pinjaman_kelompok', function (Blueprint $table) {
            $table->id();
            $table->integer('tenor')->default(0);
            $table->decimal('bunga', 5, 2)->default(0);
            $table->string('status', 255)->default(EnumStatusPinjaman::BERLANGSUNG->value);
            $table->decimal('nominal_pinjaman', 15, 2);
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_jatuh_tempo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjaman_kelompok');
    }
};
