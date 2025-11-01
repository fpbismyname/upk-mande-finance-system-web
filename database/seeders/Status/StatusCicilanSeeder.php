<?php

namespace Database\Seeders\Status;

use App\Models\Status\StatusCicilan;
use Illuminate\Database\Seeder;

class StatusCicilanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'name' => 'belum_bayar',
                'slug' => 'unpaid'
            ],
            [
                'name' => 'telat_bayar',
                'slug' => 'late_payment'
            ],
            [
                'name' => 'lunas',
                'slug' => 'paid'
            ],
        ];

        foreach ($datas as $data) {
            StatusCicilan::createOrFirst($data);
        }
    }
}
