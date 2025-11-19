<?php

namespace Database\Factories;

use App\Enums\Admin\Status\EnumStatusPengajuanPinjaman;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PengajuanPinjaman>
 */
class PengajuanPinjamanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'file_proposal' => 'default/base_pengajuan.pdf',
            'nominal_pinjaman' => floor($this->faker->numberBetween(1000000, 10000000) / 500000) * 500000,
            'tenor' => $this->faker->randomElement([3, 6, 9, 12]),
            'tanggal_pengajuan' => now(),
            'tanggal_disetujui' => null,
            'tanggal_ditolak' => null,
            'status' => EnumStatusPengajuanPinjaman::PROSES_PENGAJUAN,
        ];
    }
}
