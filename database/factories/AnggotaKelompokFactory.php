<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AnggotaKelompok>
 */
class AnggotaKelompokFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nik' => $this->faker->unique()->numerify(str_repeat('#', 16)),
            'name' => $this->faker->name,
            'alamat' => $this->faker->unique()->address(),
            'nomor_telepon' => $this->faker->unique()->numerify('08#########'),
        ];
    }
}
