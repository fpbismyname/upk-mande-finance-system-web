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
                'key' => 'toleransi_telat_bayar',
                'value' => '7',
                'type' => 'integer',
                'group' => 'cicilan'
            ],
            [
                'key' => 'denda_telat_bayar',
                'value' => '1',
                'type' => 'decimal',
                'group' => 'cicilan'
            ],
            [
                'key' => 'limit_pinjaman_maksimal',
                'value' => '50000000',
                'type' => 'decimal',
                'group' => 'pinjaman'
            ],
            [
                'key' => 'kenaikan_limit_per_jumlah_pinjaman',
                'value' => '2',
                'type' => 'decimal',
                'group' => 'pinjaman'
            ],
        ];

        foreach ($datas as $data) {
            Settings::createOrFirst($data);
        }
    }
}
