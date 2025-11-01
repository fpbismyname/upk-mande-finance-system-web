<?php

namespace Database\Seeders\Status;

use App\Models\Status\StatusJadwalPencairan;
use Illuminate\Database\Seeder;

class StatusJadwalPencairanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'name' => 'belum_terjadwal',
                'slug' => 'not-scheduled'
            ],
            [
                'name' => 'telah_dicairkan',
                'slug' => 'has-been-disbursed'
            ],
            [
                'name' => 'proses_pencairan',
                'slug' => 'disbursement-process'
            ],
        ];

        foreach ($datas as $data) {
            StatusJadwalPencairan::createOrFirst($data);
        }

    }
}
