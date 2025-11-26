<?php

namespace Database\Factories;

use App\Enums\Admin\Settings\EnumSettingKeys;
use App\Enums\Admin\Status\EnumStatusKelompok;
use App\Models\Settings;
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
            'limit_per_anggota' => Settings::getKeySetting(EnumSettingKeys::MINIMAL_LIMIT_PINJAMAN)->value('value'),
            'status' => EnumStatusKelompok::AKTIF,
            'users_id' => User::factory(),
        ];
    }
}
