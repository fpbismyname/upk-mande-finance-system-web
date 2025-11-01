<?php

namespace Database\Seeders\Status;

use App\Models\Status\StatusKelompok;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusKelompokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            ['name' => 'non-aktif', 'slug' => 'inactive'],
            ['name' => 'aktif', 'slug' => 'active']
        ];

        foreach ($datas as $data) {
            StatusKelompok::createOrFirst($data);
        }
    }
}
