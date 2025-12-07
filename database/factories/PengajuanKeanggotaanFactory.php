<?php

namespace Database\Factories;

use App\Enums\Admin\Status\EnumStatusPengajuanKeanggotaan;
use App\Models\User;
use Arr;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PengajuanKeanggotaan>
 */
class PengajuanKeanggotaanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'users_id' => User::factory(),
            'nik' => fake()->numerify(str_repeat('#', 16)),
            'ktp' => 'default/ktp.jpg',
            'alamat' => fake()->unique()->address(),
            'nomor_telepon' => fake()->unique()->numerify('08##########'),
            'nomor_rekening' => $this->faker->numerify('##############'),
            'status' => Arr::random([EnumStatusPengajuanKeanggotaan::DISETUJUI, EnumStatusPengajuanKeanggotaan::PROSES_PENGAJUAN])
        ];
    }
}
