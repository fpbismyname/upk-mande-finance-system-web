<?php

use App\Enums\Admin\Status\EnumStatusKelompok;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kelompok', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique();
            $table->decimal('limit_per_anggota', 15, 2)->default(10000000.00);
            $table->string('status', 255)->default(EnumStatusKelompok::AKTIF->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompok');
    }
};
