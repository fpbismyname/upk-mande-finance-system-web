<?php

namespace Database\Seeders\Status;

use App\Models\Status\StatusPinjaman;
use Illuminate\Database\Seeder;

class StatusPinjamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            ['name' => 'menunggak', 'slug' => 'delinquent'],
            ['name' => 'berjalan', 'slug' => 'ongoing'],
            ['name' => 'lunas', 'slug' => 'paid-off'],
        ];

        foreach ($datas as $data) {
            StatusPinjaman::createOrFirst($data);
        }
    }
}
