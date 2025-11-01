<?php

namespace Database\Factories;

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
            'file_proposal' => "https://www.rd.usda.gov/sites/default/files/pdf-sample_0.pdf",
            'nominal_pinjaman' => floor($this->faker->numberBetween(1000000, 10000000) / 500000) * 500000,
            'tenor' => $this->faker->randomElement([3, 6, 12]),
            'pengajuan_pada' => now(),
            'disetujui_pada' => null,
            'ditolak_pada' => null,
            'status_id' => 2,
        ];
    }
}
