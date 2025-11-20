<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'key' => 'bunga_pinjaman',
                'value' => '18.00',
                'type' => 'decimal',
                'group' => 'pinjaman'
            ],
            [
                'key' => 'maksimal_limit_pinjaman',
                'value' => '50000000',
                'type' => 'decimal',
                'group' => 'pinjaman'
            ],
            [
                'key' => 'minimal_limit_pinjaman',
                'value' => '2000000',
                'type' => 'decimal',
                'group' => 'pinjaman'
            ],
            [
                'key' => 'kenaikan_limit_per_jumlah_pinjaman',
                'value' => '1',
                'type' => 'decimal',
                'group' => 'pinjaman'
            ],
            [
                'key' => 'toleransi_telat_bayar',
                'value' => '7',
                'type' => 'integer',
                'group' => 'pinjaman'
            ],
            [
                'key' => 'minimal_anggota_kelompok',
                'value' => '4',
                'type' => 'integer',
                'group' => 'kelompok'
            ],
            [
                'key' => 'maksimal_anggota_kelompok',
                'value' => '50',
                'type' => 'integer',
                'group' => 'kelompok'
            ],
        ];

        foreach ($datas as $data) {
            Settings::createOrFirst($data);
        }
    }
}
