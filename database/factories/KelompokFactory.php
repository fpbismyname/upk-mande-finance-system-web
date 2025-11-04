<?php

namespace Database\Factories;

use App\Enum\Admin\Status\EnumStatusKelompok;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kelompok>
 */
class KelompokFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => "Kelompok {$this->faker->words(2, true)}",
            'limit_pinjaman' => 10000000.0,
            'status' => EnumStatusKelompok::AKTIF,
            'users_id' => User::factory(),
        ];
    }
}
